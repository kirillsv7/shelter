<?php

namespace Source\Infrastructure\Animal\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Source\Infrastructure\Animal\Factories\AnimalFactory;
use Source\Infrastructure\Animal\QueryBuilders\AnimalQueryBuilder;
use Source\Infrastructure\Laravel\Models\BaseModel;
use Source\Infrastructure\MediaFile\Models\MediaFileModel;
use Source\Infrastructure\Organization\Models\OrganizationModel;
use Source\Infrastructure\Slug\Models\SlugModel;

final class AnimalModel extends BaseModel
{
    use HasUuids;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'animals';

    protected $fillable = [
        'name',
        'type',
        'gender',
        'breed',
        'birthdate',
        'entrydate',
        'status',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'birthdate' => 'immutable_date',
            'entrydate' => 'immutable_date',
        ];
    }

    public function newEloquentBuilder($query): AnimalQueryBuilder
    {
        return new AnimalQueryBuilder($query);
    }

    public static function newFactory(): AnimalFactory
    {
        return AnimalFactory::new();
    }

    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(
            OrganizationModel::class,
            'animal_organization',
            'animal_id',
            'organization_id',
        );
    }

    public function animalStatus(): HasMany
    {
        return $this->hasMany(AnimalStatusModel::class, 'animal_id');
    }

    public function slug(): MorphOne
    {
        return $this->morphOne(SlugModel::class, 'sluggable');
    }

    public function mediaFiles(): MorphMany
    {
        return $this->morphMany(MediaFileModel::class, 'mediable');
    }
}
