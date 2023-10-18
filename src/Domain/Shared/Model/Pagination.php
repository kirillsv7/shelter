<?php

namespace Source\Domain\Shared\Model;

use Source\Domain\Shared\Model\PaginationValueObjects\TotalItems;
use Source\Domain\Shared\Model\PaginationValueObjects\Limit;
use Source\Domain\Shared\Model\PaginationValueObjects\Page;
use Source\Domain\Shared\ValueObjects\IntegerValueObject;

final class Pagination
{
    private const LIMIT = 20;

    private ?TotalItems $totalItems = null;

    private function __construct(
        public readonly Limit $limit,
        public readonly Page $page
    ) {
    }

    public static function create(?int $limit = null, ?int $page = null): self
    {
        return new self(
            Limit::fromInteger($limit ?? self::LIMIT),
            Page::fromInteger(max($page, 1))
        );
    }

    public function offset(): IntegerValueObject
    {
        return $this->page->decrement()->multiply($this->limit);
    }

    public function generateLinks(int $itemsTotal): array
    {
        $this->totalItems = TotalItems::fromInteger($itemsTotal);

        return [
            'total_items' => $this->totalItems->value,
            'per_page' => $this->limit->value,
            'on_page' => $this->calculateItemsOnPage()->value,
            'current' => $this->page->value,
            'previous' => $this->previousPage()?->value,
            'next' => $this->nextPage()?->value,
            'last' => $this->lastPage()->value
        ];
    }

    private function calculateItemsOnPage(): IntegerValueObject
    {
        return $this->totalItems
            ->subtract($this->limit->multiply($this->page->decrement()))
            ->max(IntegerValueObject::fromInteger(0))
            ->min($this->limit);
    }

    private function previousPage(): ?IntegerValueObject
    {
        if ($this->page->equals(IntegerValueObject::fromInteger(1))) {
            return null;
        }

        return $this->page->decrement();
    }

    private function nextPage(): ?IntegerValueObject
    {
        if (
            $this->page->equals($this->lastPage()) ||
            $this->lastPage()->isLessThan($this->page)
        ) {
            return null;
        }

        return $this->page->increment();
    }

    private function lastPage(): IntegerValueObject
    {
        return $this->totalItems->divideCeil($this->limit);
    }
}
