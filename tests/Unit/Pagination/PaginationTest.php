<?php

namespace Tests\Unit\Pagination;

use InvalidArgumentException;
use Source\Domain\Shared\Model\Pagination;
use Source\Domain\Shared\Model\PaginationValueObjects\Limit;
use Source\Domain\Shared\Model\PaginationValueObjects\Page;
use Source\Domain\Shared\Model\PaginationValueObjects\TotalItems;
use Tests\UnitTestCase;

class PaginationTest extends UnitTestCase
{
    public function testPaginationCreate()
    {
        $paginationWithLimitAndPage = Pagination::create(
            limit: Limit::fromInteger(30),
            page: Page::fromInteger(2),
        );
        $paginationWithLimitOnly = Pagination::create(limit: Limit::fromInteger(20));
        $paginationWithPageOnly = Pagination::create(page: Page::fromInteger(4));

        $this->assertEquals(30, $paginationWithLimitAndPage->offset()->value);

        $this->assertEquals(1, $paginationWithLimitOnly->page->value);

        $this->assertEquals(20, $paginationWithPageOnly->limit->value);
        $this->assertEquals(60, $paginationWithPageOnly->offset()->value);
    }

    public function testPaginationLimitException()
    {
        $this->expectException(InvalidArgumentException::class);

        Pagination::create(limit: Limit::fromInteger(-10));
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('paginationVariations')]
    public function testPaginationLinksGeneration($limit, $page, $total)
    {
        $pagination = Pagination::create(
            Limit::fromInteger($limit),
            Page::fromInteger($page),
        );

        $pagination->generateLinks(TotalItems::fromInteger($total));

        $calcToTotal = max($total - $limit * ($page - 1), 0);
        $onPage = min($calcToTotal, $limit);

        $this->assertEquals($total, $pagination->totalItems?->value);
        ;
        $this->assertEquals($limit, $pagination->limit->value);
        $this->assertEquals($onPage, $pagination->onPage?->value);
        $this->assertEquals(
            max($page, 1),
            $pagination->current?->value,
        );

        $previousPage = null;
        if ($page > 1 && $page <= $pagination->last?->value) {
            $previousPage = $page - 1;
        }
        if ($page > $pagination->last?->value) {
            $previousPage = $pagination->last?->value;
        }

        $this->assertEquals($previousPage, $pagination->previous?->value);

        $this->assertEquals(
            $total - ($limit * $page) > 0 ? max($page, 1) + 1 : null,
            $pagination->next?->value,
        );

        $this->assertEquals(
            ceil($total / $limit),
            $pagination->last?->value,
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
