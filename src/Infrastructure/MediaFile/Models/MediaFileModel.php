<?php

namespace Source\Infrastructure\MediaFile\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Source\Infrastructure\Laravel\Models\BaseModel;
use Source\Infrastructure\MediaFile\Factories\MediaFileFactory;

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
 * @method static \Source\Infrastructure\MediaFile\Factories\MediaFileFactory factory($count = null, $state = [])
 * @method static Builder|MediaFileModel newModelQuery()
 * @method static Builder|MediaFileModel newQuery()
 * @method static Builder|MediaFileModel query()
 * @method static Builder|MediaFileModel whereCreatedAt($value)
 * @method static Builder|MediaFileModel whereDisk($value)
 * @method static Builder|MediaFileModel whereId($value)
 * @method static Builder|MediaFileModel whereMediableId($value)
 * @method static Builder|MediaFileModel whereMediableType($value)
 * @method static Builder|MediaFileModel wherePath($value)
 * @method static Builder|MediaFileModel whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class MediaFileModel extends BaseModel
{
    use HasFactory;

    protected $table = 'media_files';

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
