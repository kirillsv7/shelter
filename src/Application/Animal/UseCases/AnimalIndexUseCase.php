<?php

namespace Source\Application\Animal\UseCases;

use Source\Domain\Animal\AnimalSearchCriteria;
use Source\Domain\Animal\Enums\AnimalGender;
use Source\Domain\Animal\Enums\AnimalType;
use Source\Domain\Animal\Repositories\AnimalRepository;
use Source\Domain\Animal\ValueObjects\Name;
use Source\Domain\Shared\Model\Pagination;
use Source\Domain\Shared\ValueObjects\IntegerValueObject;

final class AnimalIndexUseCase
{
    public function __construct(
        protected AnimalRepository $repository
    ) {
    }

    public function apply(
        ?Name $name,
        ?AnimalType $type,
        ?AnimalGender $gender,
        ?IntegerValueObject $ageMin,
        ?IntegerValueObject $ageMax,
        ?int $limit,
        ?int $page
    ): array {
        $criteria = AnimalSearchCriteria::create(
            $name,
            $type,
            $gender,
            $ageMin,
            $ageMax,
        );

        $pagination = Pagination::create(
            $limit,
            $page
        );

        $animals = $this->repository->index(
            $criteria,
            $pagination
        );

        $animalsTotalCount = $this->repository->totalCountByCriteria($criteria);

        $paginationLinks = $pagination->generateLinks($animalsTotalCount);

        return [
            'animals' => $animals,
            'pagination' => $paginationLinks
        ];
    }
}
