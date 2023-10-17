<?php

namespace Source\Infrastructure\Animal\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Source\Domain\Animal\Enums\AnimalGender;
use Source\Domain\Animal\Enums\AnimalStatus;
use Source\Domain\Animal\Enums\AnimalType;
use Source\Domain\Animal\ValueObjects\Name;
use Source\Domain\Shared\ValueObjects\IntegerValueObject;

final class AnimalQueryBuilder extends Builder
{
    public function slug(string $slug): self
    {
        return $this->whereRelation('slug', 'slug', '=', $slug);
    }

    public function name(Name $name): self
    {
        return $this->where('name', 'LIKE', '%' . $name->value() . '%');
    }

    public function type(AnimalType $type): self
    {
        return $this->where('type', '=', $type);
    }

    public function gender(AnimalGender $gender): self
    {
        return $this->where('gender', '=', $gender);
    }

    public function ageMin(IntegerValueObject $ageMin): self
    {
        return $this->where('birthdate', '<=', Carbon::now()->subYears($ageMin->value));
    }

    public function ageMax(IntegerValueObject $ageMax): self
    {
        return $this->where('birthdate', '>=', Carbon::now()->subYears($ageMax->value));
    }

    public function status(AnimalStatus $status): self
    {
        return $this->where('status', '=', $status);
    }

    public function published($bool = true): self
    {
        return $this->where('published', '=', $bool);
    }
}
