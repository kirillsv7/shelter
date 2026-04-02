<?php

namespace Source\Application\Animal\UseCases;

use Source\Application\Animal\UseCases\Traits\LoadSlugTrait;
use Source\Domain\Animal\Aggregates\Animal;
use Source\Domain\Animal\AnimalSearchCriteria;
use Source\Domain\Animal\Enums\AnimalGender;
use Source\Domain\Animal\Enums\AnimalType;
use Source\Domain\Animal\Repositories\AnimalRepository;
use Source\Domain\Animal\ValueObjects\Name;
use Source\Domain\Shared\Model\Pagination;
use Source\Domain\Shared\ValueObjects\IntegerValueObject;
use Source\Domain\Shared\ValueObjects\StringValueObject;
use Source\Infrastructure\Animal\Models\AnimalModel;
use Source\Interface\Animal\DTOs\AnimalIndexResponseDTO;

final class AnimalIndexUseCase
{
    use LoadSlugTrait;

    public function __construct(
        protected AnimalRepository $repository,
    ) {
    }

    public function apply(
        ?Name $name,
        ?AnimalType $type,
        ?AnimalGender $gender,
        ?IntegerValueObject $ageMin,
        ?IntegerValueObject $ageMax,
        ?int $limit,
        ?int $page,
    ): AnimalIndexResponseDTO {
        $criteria = AnimalSearchCriteria::create(
            $name,
            $type,
            $gender,
            $ageMin,
            $ageMax,
        );

        $pagination = Pagination::create(
            $limit,
            $page,
        );

        $animals = $this->repository->index(
            $criteria,
            $pagination,
        );

        $animals = array_map(
            function (Animal $animal) {
                $this->loadSlug(
                    $animal,
                    StringValueObject::fromString(AnimalModel::class),
                );

                return $animal;
            },
            $animals,
        );

        $animalsTotalCount = $this->repository->totalCountByCriteria($criteria);

        $pagination->generateLinks($animalsTotalCount);

        return new AnimalIndexResponseDTO(
            animals: $animals,
            pagination: $pagination,
        );
    }
}
