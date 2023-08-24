<?php

namespace Source\Infrastructure\Animal\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;
use Source\Domain\Animal\Enums\AnimalGender;
use Source\Domain\Animal\Enums\AnimalStatus;
use Source\Domain\Animal\Enums\AnimalType;

final class AnimalQueryBuilder extends Builder
{
    public function slug(string $slug): self
    {
        return $this->whereRelation('slug', 'slug', '=', $slug);
    }

    public function type(AnimalType $type): self
    {
        return $this->where('type', '=', $type);
    }

    public function gender(AnimalGender $gender): self
    {
        return $this->where('gender', '=', $gender);
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
