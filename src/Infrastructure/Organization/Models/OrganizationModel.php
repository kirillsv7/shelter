<?php

namespace Source\Infrastructure\Organization\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Source\Infrastructure\Animal\Models\AnimalModel;
use Source\Infrastructure\Laravel\Models\BaseModel;
use Source\Infrastructure\Organization\Factories\OrganizationFactory;
use Source\Infrastructure\Organization\QueryBuilders\OrganizationQueryBuilder;

final class OrganizationModel extends BaseModel
{
    use HasUuids;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'organizations';

    protected $fillable = [
        'name',
        'address',
        'contacts',
        'socials',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'address' => 'array',
            'contacts' => 'array',
            'socials' => 'array',
        ];
    }

    public function newEloquentBuilder($query): OrganizationQueryBuilder
    {
        return new OrganizationQueryBuilder($query);
    }

    public static function newFactory(): OrganizationFactory
    {
        return OrganizationFactory::new();
    }

    public function animals(): BelongsToMany
    {
        return $this->belongsToMany(
            AnimalModel::class,
            'animal_organization',
            'organization_id',
            'animal_id',
        );
    }
}
