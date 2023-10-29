<?php

namespace Source\Infrastructure\MediaFile\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Source\Infrastructure\Laravel\Models\BaseModel;
use Source\Infrastructure\MediaFile\Factories\MediaFileFactory;

/**
 * Source\Infrastructure\MediaFile\Models\MediaFileModel
 *
 * @property string $id
 * @property array $storage_info
 * @property array $sizes
 * @property string $mimetype
 * @property string $mediable_type
 * @property string $mediable_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $mediable
 * @method static \Source\Infrastructure\MediaFile\Factories\MediaFileFactory factory($count = null, $state = [])
 * @method static Builder|MediaFileModel newModelQuery()
 * @method static Builder|MediaFileModel newQuery()
 * @method static Builder|MediaFileModel query()
 * @method static Builder|MediaFileModel whereCreatedAt($value)
 * @method static Builder|MediaFileModel whereId($value)
 * @method static Builder|MediaFileModel whereMediableId($value)
 * @method static Builder|MediaFileModel whereMediableType($value)
 * @method static Builder|MediaFileModel whereMimetype($value)
 * @method static Builder|MediaFileModel whereSizes($value)
 * @method static Builder|MediaFileModel whereStorageInfo($value)
 * @method static Builder|MediaFileModel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
final class MediaFileModel extends BaseModel
{
    use HasUuids;
    use HasFactory;

    protected $table = 'media_files';

    protected $fillable = [
        'storage_info',
        'sizes',
        'mimetype',
        'mediable_type',
        'mediable_id',
    ];

    protected $casts = [
        'storage_info' => 'array',
        'sizes' => 'array'
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
