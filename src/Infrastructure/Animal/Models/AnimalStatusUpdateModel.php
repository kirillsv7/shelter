<?php

namespace Source\Infrastructure\Animal\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Source\Infrastructure\Laravel\Models\BaseModel;

final class AnimalStatusUpdateModel extends BaseModel
{
    use HasUuids;
    use HasFactory;

    protected $table = 'animal_status_updates';

    protected $fillable = [
        'animal_id',
        'status',
        'notes',
    ];

    public function animal(): BelongsTo
    {
        return $this->belongsTo(AnimalModel::class, 'animal_id');
    }
}
