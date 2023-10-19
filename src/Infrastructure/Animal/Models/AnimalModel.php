<?php

namespace Source\Infrastructure\Animal\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Source\Infrastructure\Animal\Factories\AnimalFactory;
use Source\Infrastructure\Animal\QueryBuilders\AnimalQueryBuilder;
use Source\Infrastructure\Laravel\Models\BaseModel;
use Source\Infrastructure\MediaFile\Models\MediaFileModel;
use Source\Infrastructure\Slug\Models\SlugModel;

/**
 * Source\Infrastructure\Animal\Models\AnimalModel
 *
 * @property string $id
 * @property string $name
 * @property string $type
 * @property string $gender
 * @property string $breed
 * @property string|null $birthdate
 * @property string|null $entrydate
 * @property string $status
 * @property bool $published
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, MediaFileModel> $mediaFiles
 * @property-read int|null $media_files_count
 * @property-read SlugModel|null $slug
 * @method static AnimalQueryBuilder|AnimalModel ageMax(\Source\Domain\Shared\ValueObjects\IntegerValueObject $ageMax)
 * @method static AnimalQueryBuilder|AnimalModel ageMin(\Source\Domain\Shared\ValueObjects\IntegerValueObject $ageMin)
 * @method static \Source\Infrastructure\Animal\Factories\AnimalFactory factory($count = null, $state = [])
 * @method static AnimalQueryBuilder|AnimalModel gender(\Source\Domain\Animal\Enums\AnimalGender $gender)
 * @method static AnimalQueryBuilder|AnimalModel name(\Source\Domain\Animal\ValueObjects\Name $name)
 * @method static AnimalQueryBuilder|AnimalModel newModelQuery()
 * @method static AnimalQueryBuilder|AnimalModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AnimalModel onlyTrashed()
 * @method static AnimalQueryBuilder|AnimalModel published($bool = true)
 * @method static AnimalQueryBuilder|AnimalModel query()
 * @method static AnimalQueryBuilder|AnimalModel slug(string $slug)
 * @method static AnimalQueryBuilder|AnimalModel status(\Source\Domain\Animal\Enums\AnimalStatus $status)
 * @method static AnimalQueryBuilder|AnimalModel type(\Source\Domain\Animal\Enums\AnimalType $type)
 * @method static AnimalQueryBuilder|AnimalModel whereBirthdate($value)
 * @method static AnimalQueryBuilder|AnimalModel whereBreed($value)
 * @method static AnimalQueryBuilder|AnimalModel whereCreatedAt($value)
 * @method static AnimalQueryBuilder|AnimalModel whereDeletedAt($value)
 * @method static AnimalQueryBuilder|AnimalModel whereEntrydate($value)
 * @method static AnimalQueryBuilder|AnimalModel whereGender($value)
 * @method static AnimalQueryBuilder|AnimalModel whereId($value)
 * @method static AnimalQueryBuilder|AnimalModel whereName($value)
 * @method static AnimalQueryBuilder|AnimalModel wherePublished($value)
 * @method static AnimalQueryBuilder|AnimalModel whereStatus($value)
 * @method static AnimalQueryBuilder|AnimalModel whereType($value)
 * @method static AnimalQueryBuilder|AnimalModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnimalModel withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|AnimalModel withoutTrashed()
 * @mixin \Eloquent
 */
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
        'published',
    ];

    protected $with = [
        'slug',
    ];

    public function newEloquentBuilder($query): AnimalQueryBuilder
    {
        return new AnimalQueryBuilder($query);
    }

    public static function newFactory(): AnimalFactory
    {
        return AnimalFactory::new();
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
