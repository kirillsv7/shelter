<?php

namespace Source\Infrastructure\MediaFile\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Source\Infrastructure\Laravel\Models\BaseModel;
use Source\Infrastructure\MediaFile\Factories\MediaFileFactory;

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

    protected function casts(): array
    {
        return [
            'storage_info' => 'array',
            'sizes' => 'array',
        ];
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
