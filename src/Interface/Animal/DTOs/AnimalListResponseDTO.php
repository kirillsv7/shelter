<?php

namespace Source\Interface\Animal\DTOs;

use Source\Application\Animal\DTOs\AnimalDetailsDTO;
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
