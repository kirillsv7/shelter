<?php

namespace Source\Domain\Animal\Aggregates;

use Carbon\Carbon;
use Source\Domain\Animal\Enums\AnimalGender;
use Source\Domain\Animal\Enums\AnimalType;
use Source\Domain\Animal\ValueObjects\Breed;
use Source\Domain\Animal\ValueObjects\Name;

final class AnimalInfo
{
    private function __construct(
        private Name $name,
        private AnimalType $type,
        private AnimalGender $gender,
        private Breed $breed,
        private Carbon $birthdate,
        private Carbon $entrydate,
    ) {
    }

    public static function create(
        Name $name,
        AnimalType $type,
        AnimalGender $gender,
        Breed $breed,
        Carbon $birthdate,
        Carbon $entrydate
    ): self {
        return new self(
            name: $name,
            type: $type,
            gender: $gender,
            breed: $breed,
            birthdate: $birthdate,
            entrydate: $entrydate
        );
    }

    public function change(
        ?Name $name,
        ?AnimalType $type,
        ?AnimalGender $gender,
        ?Breed $breed,
        ?Carbon $birthdate,
        ?Carbon $entrydate
    ) {
        if ($name) {
            $this->changeName($name);
        }
        if ($type) {
            $this->changeType($type);
        }
        if ($gender) {
            $this->changeGender($gender);
        }
        if ($breed) {
            $this->changeBreed($breed);
        }
        if ($birthdate) {
            $this->changeBirthdate($birthdate);
        }
        if ($entrydate) {
            $this->changeEntrydate($entrydate);
        }
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function type(): AnimalType
    {
        return $this->type;
    }

    public function gender(): AnimalGender
    {
        return $this->gender;
    }

    public function breed(): Breed
    {
        return $this->breed;
    }

    public function birthdate(): Carbon
    {
        return $this->birthdate;
    }

    public function entrydate(): Carbon
    {
        return $this->entrydate;
    }

    private function changeName(Name $name): void
    {
        $this->name = $name;
    }

    private function changeType(AnimalType $type): void
    {
        $this->type = $type;
    }

    private function changeGender(AnimalGender $gender): void
    {
        $this->gender = $gender;
    }

    private function changeBreed(Breed $breed): void
    {
        $this->breed = $breed;
    }

    private function changeBirthdate(Carbon $birthdate): void
    {
        $this->birthdate = $birthdate;
    }

    private function changeEntrydate(Carbon $entrydate): void
    {
        $this->entrydate = $entrydate;
    }

    public static function fromArray($data): self
    {
        return new self(
            name: Name::fromString($data['name']),
            type: AnimalType::tryFrom($data['type']),
            gender: AnimalGender::tryFrom($data['gender']),
            breed: Breed::fromString($data['breed']),
            birthdate: new \Illuminate\Support\Carbon($data['birthdate']),
            entrydate: new Carbon($data['entrydate']),
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name()->value(),
            'type' => $this->type(),
            'gender' => $this->gender(),
            'breed' => $this->breed()->value(),
            'birthdate' => $this->birthdate()->format('Y-m-d'),
            'entrydate' => $this->entrydate()->format('Y-m-d'),
        ];
    }
}
