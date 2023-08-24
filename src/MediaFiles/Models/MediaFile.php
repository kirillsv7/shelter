<?php

namespace Source\MediaFiles\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Source\Infrastructure\Laravel\Models\BaseModel;
use Source\MediaFiles\Factories\MediaFileFactory;

/**
 * Source\MediaFiles\Models\MediaFile
 *
 * @property int $id
 * @property string $disk
 * @property string $path
 * @property string $mediable_type
 * @property string $mediable_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read BaseModel|\Eloquent $mediable
 *
 * @method static \Source\MediaFiles\Factories\MediaFileFactory factory($count = null, $state = [])
 * @method static Builder|MediaFile newModelQuery()
 * @method static Builder|MediaFile newQuery()
 * @method static Builder|MediaFile query()
 * @method static Builder|MediaFile whereCreatedAt($value)
 * @method static Builder|MediaFile whereDisk($value)
 * @method static Builder|MediaFile whereId($value)
 * @method static Builder|MediaFile whereMediableId($value)
 * @method static Builder|MediaFile whereMediableType($value)
 * @method static Builder|MediaFile wherePath($value)
 * @method static Builder|MediaFile whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class MediaFile extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'disk',
        'path',
        'mediable_type',
        'mediable_id',
    ];

    public function newEloquentBuilder($query): Builder
    {
        return new Builder($query);
    }

    public static function newFactory(): MediaFileFactory
    {
        return MediaFileFactory::new();
    }

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }
}
