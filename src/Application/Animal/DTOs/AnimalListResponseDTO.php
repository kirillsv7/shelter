<?php

namespace Source\Application\Animal\DTOs;

use Source\Application\Shared\DTOs\PaginationDTO;

final readonly class AnimalListResponseDTO
{
    /**
     * @param  AnimalDetailsDTO[]  $animals
     */
    public function __construct(
        public array $animals,
        public PaginationDTO $pagination,
    ) {
    }
}
