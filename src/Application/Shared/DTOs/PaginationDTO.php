<?php

namespace Source\Application\Shared\DTOs;

use JsonSerializable;
use Source\Domain\Shared\Model\Pagination;

final readonly class PaginationDTO implements JsonSerializable
{
    public function __construct(
        protected Pagination $pagination,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'total_items' => $this->pagination->totalItems->value,
            'per_page' => $this->pagination->limit->value,
            'on_page' => $this->pagination->calculateItemsOnPage()->value,
            'current' => $this->pagination->page->value,
            'previous' => $this->pagination->previousPage()?->value,
            'next' => $this->pagination->nextPage()?->value,
            'last' => $this->pagination->lastPage()->value,
        ];
    }
}
