<?php

namespace Source\Infrastructure\Slug\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Source\Infrastructure\Laravel\Models\BaseModel;

/**
 * Source\Infrastructure\Slug\Models\SlugModel
 *
 * @property string $id
 * @property string $slug
 * @property string $sluggable_type
 * @property string $sluggable_id
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $sluggable
 * @method static Builder|SlugModel newModelQuery()
 * @method static Builder|SlugModel newQuery()
 * @method static Builder|SlugModel query()
 * @method static Builder|SlugModel whereId($value)
 * @method static Builder|SlugModel whereSlug($value)
 * @method static Builder|SlugModel whereSluggableId($value)
 * @method static Builder|SlugModel whereSluggableType($value)
 * @mixin \Eloquent
 */
class SlugModel extends BaseModel
{
    use HasUuids;

    protected $table = 'slugs';

    protected $fillable = [
        'slug',
        'sluggable_type',
        'sluggable_id',
    ];

    public $timestamps = false;

    public function newEloquentBuilder($query): Builder
    {
        return new Builder($query);
    }

    public function sluggable(): MorphTo
    {
        return $this->morphTo();
    }
}
