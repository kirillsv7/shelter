<?php

namespace Source\Interface\Animal\Mappers;

use Source\Domain\Animal\Aggregates\AnimalInfo;

final readonly class AnimalInfoMapper
{
    public function toArray(AnimalInfo $animalInfo): array
    {
        return [
            'name' => $animalInfo->name()->value(),
            'type' => $animalInfo->type(),
            'gender' => $animalInfo->gender(),
            'breed' => $animalInfo->breed()->value(),
            'birthdate' => $animalInfo->birthdate(),
            'entrydate' => $animalInfo->entrydate(),
        ];
    }
}
