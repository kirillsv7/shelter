<?php

namespace Source\Interface\Shared\Mappers;

use Source\Domain\Shared\Model\Pagination;

final readonly class PaginationMapper
{
    public function toArray(Pagination $pagination): array
    {
        return [
            'total_items' => $pagination->totalItems->value,
            'per_page' => $pagination->limit->value,
            'on_page' => $pagination->calculateItemsOnPage()->value,
            'current' => $pagination->page->value,
            'previous' => $pagination->previousPage()?->value,
            'next' => $pagination->nextPage()?->value,
            'last' => $pagination->lastPage()->value,
        ];
    }
}
