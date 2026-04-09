<?php

namespace Source\Infrastructure\Slug\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Source\Infrastructure\Laravel\Models\BaseModel;

final class SlugModel extends BaseModel
{
    use HasUuids;
    use SoftDeletes;

    protected $table = 'slugs';

    protected $fillable = [
        'slug',
        'sluggable_type',
        'sluggable_id',
    ];

    protected function casts(): array
    {
        return [
            'birthdate' => 'immutable_date',
            'entrydate' => 'immutable_date',
        ];
    }

    public function newEloquentBuilder($query): Builder
    {
        return new Builder($query);
    }

    public function sluggable(): MorphTo
    {
        return $this->morphTo();
    }
}
