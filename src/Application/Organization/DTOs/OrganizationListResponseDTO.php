<?php

namespace Source\Application\Organization\DTOs;

use Source\Application\Shared\DTOs\PaginationDTO;

final readonly class OrganizationListResponseDTO
{
    /**
     * @param  OrganizationDetailsDTO[]  $organizations
     */
    public function __construct(
        public array $organizations,
        public PaginationDTO $pagination,
    ) {
    }
}
