<?php

namespace Source\Interface\Animal\DTOs;

use Source\Domain\Animal\Aggregates\Animal;
use Source\Domain\Shared\Model\Pagination;

final readonly class AnimalIndexResponseDTO
{
    /**
     * @param  Animal[]  $animals
     */
    public function __construct(
        public array $animals,
        public Pagination $pagination,
    ) {
    }
}
