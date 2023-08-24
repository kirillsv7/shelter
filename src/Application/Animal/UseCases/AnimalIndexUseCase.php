<?php

namespace Source\Application\Animal\UseCases;

use Source\Domain\Animal\AnimalSearchCriteria;
use Source\Domain\Animal\Enums\AnimalGender;
use Source\Domain\Animal\Enums\AnimalType;
use Source\Domain\Animal\Repositories\AnimalRepository;
use Source\Domain\Shared\Model\Pagination;

final class AnimalIndexUseCase
{
    public function __construct(
        protected AnimalRepository $repository
    ) {
    }

    public function apply(
        ?AnimalType $type,
        ?AnimalGender $gender,
        ?int $page
    ): array {
        $criteria = AnimalSearchCriteria::create(
            $type,
            $gender
        );

        $pagination = Pagination::create(page: $page);

        $animals = $this->repository->index(
            $criteria,
            $pagination
        );

        $animalsTotalCount = $this->repository->criteriaTotalCount($criteria);

        $paginationLinks = $pagination->generateLinks($animalsTotalCount);

        return [
            'animals' => $animals,
            'pagination' => $paginationLinks
        ];
    }
}
