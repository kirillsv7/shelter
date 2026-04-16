<?php

namespace Source\Infrastructure\Organization\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;
use Source\Domain\Shared\ValueObjects\StringValueObject;

final class OrganizationQueryBuilder extends Builder
{
    public function slug(StringValueObject $slug): self
    {
        return $this->whereRelation('slug', 'slug', '=', $slug);
    }

    public function name(StringValueObject $text): self
    {
        return $this->where('name', 'LIKE', '%' . $text->value() . '%');
    }

    public function verified(bool $bool = true): self
    {
        return $this->where('is_verified', '=', $bool);
    }

    public function active(bool $bool = true): self
    {
        return $this->where('is_active', '=', $bool);
    }
}
