<?php

namespace Source\Domain\Shared\Model;

use Source\Domain\Shared\Model\PaginationValueObjects\Limit;
use Source\Domain\Shared\Model\PaginationValueObjects\Page;
use Source\Domain\Shared\Model\PaginationValueObjects\TotalItems;
use Source\Domain\Shared\ValueObjects\IntegerValueObject;

final class Pagination
{
    private const int LIMIT = 20;

    public ?TotalItems $totalItems = null;
    public ?IntegerValueObject $onPage = null;
    public ?Page $current = null;
    public ?Page $previous = null;
    public ?Page $next = null;
    public ?Page $last = null;


    protected function __construct(
        public readonly Limit $limit,
        public readonly Page $page,
    ) {
    }

    public static function create(?int $limit = null, ?int $page = null): self
    {
        return new self(
            Limit::fromInteger($limit ?? self::LIMIT),
            Page::fromInteger(max($page, 1)),
        );
    }

    public function offset(): IntegerValueObject
    {
        return $this->page->decrement()->multiply($this->limit);
    }

    public function generateLinks(int $totalItems): void
    {
        $this->totalItems = TotalItems::fromInteger($totalItems);
        $this->onPage = $this->calculateItemsOnPage();
        $this->current = $this->page;
        $this->previous = $this->previousPage();
        $this->next = $this->nextPage();
        $this->last = $this->lastPage();
    }

    public function calculateItemsOnPage(): IntegerValueObject
    {
        return $this->totalItems
            ->subtract($this->limit->multiply($this->page->decrement()))
            ->max(IntegerValueObject::fromInteger(0))
            ->min($this->limit);
    }

    public function previousPage(): ?Page
    {
        if ($this->page->equals(Page::fromInteger(1))) {
            return null;
        }

        if ($this->page->isGreaterThan($this->lastPage())) {
            return $this->lastPage();
        }

        return $this->page->decrement();
    }

    public function nextPage(): ?Page
    {
        if (
            $this->page->equals($this->lastPage()) ||
            $this->lastPage()->isLessThan($this->page)
        ) {
            return null;
        }

        return $this->page->increment();
    }

    public function lastPage(): Page
    {
        return Page::fromInteger($this->totalItems->divideCeil($this->limit)->value);
    }
}
