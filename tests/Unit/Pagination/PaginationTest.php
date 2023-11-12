<?php

namespace Tests\Unit\Pagination;

use Source\Domain\Shared\Model\Pagination;
use Tests\UnitTestCase;

class PaginationTest extends UnitTestCase
{
    public function testPaginationCreate()
    {
        $paginationWithLimitAndPage = Pagination::create(30, 2);
        $paginationWithLimitOnly = Pagination::create(20);
        $paginationWithPageOnly = Pagination::create(page: 4);

        $this->assertEquals(30, $paginationWithLimitAndPage->offset()->value);

        $this->assertEquals(1, $paginationWithLimitOnly->page->value);

        $this->assertEquals(20, $paginationWithPageOnly->limit->value);
        $this->assertEquals(60, $paginationWithPageOnly->offset()->value);
    }

    /**
     * @dataProvider paginationVariations
     */
    public function testPaginationLinksGeneration($limit, $page, $total)
    {
        $pagination = Pagination::create(
            $limit,
            $page,
        );

        $paginationLinks = $pagination->generateLinks($total);

        $calcToTotal = max($total - $limit * ($page - 1), 0);
        $onPage = min($calcToTotal, $limit);

        $this->assertEquals($total, $paginationLinks['total_items']);
        $this->assertEquals($limit, $paginationLinks['per_page']);
        $this->assertEquals($onPage, $paginationLinks['on_page']);
        $this->assertEquals(max($page, 1), $paginationLinks['current']);

        $previousPage = null;
        if ($page > 1 && $page <= $paginationLinks['last']) {
            $previousPage = $page - 1;
        }
        if ($page > $paginationLinks['last']) {
            $previousPage = $paginationLinks['last'];
        }

        $this->assertEquals($previousPage, $paginationLinks['previous']);

        $this->assertEquals(
            $total - ($limit * $page) > 0 ? max($page, 1) + 1 : null,
            $paginationLinks['next']
        );

        $this->assertEquals(
            ceil($total / $limit),
            $paginationLinks['last']
        );
    }

    public static function paginationVariations(): array
    {
        return [
            [20, 1, 0],
            [20, 1, 20],
            [25, 3, 65],
            [20, 4, 100],
            [50, 2, 100],
            [30, 1, 10],
            // Testing nonexistent pages, must return: on_page = 0, current = 2, next = 3, last = 3
            [20, 5, 50],
            // Testing nonexistent pages, must return: on_page = 20, current = 1, previous = null, last = 2
            [20, 0, 40],
            // Testing nonexistent pages, must return: on_page = 20, current = 1, previous = null, last = 2
            [20, -1, 40],
        ];
    }
}
