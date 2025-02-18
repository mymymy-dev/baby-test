<?php

declare(strict_types = 1);

namespace My\Baby\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use My\Baby\Enums\BabyActivityTypeEnum;
use My\User\Models\User;

class BabyActivity extends Model
{
    use HasUlids;
    use SoftDeletes;

    protected $appends = [];

    protected $fillable = ['type', 'date', 'data'];

    protected $guarded = [];

    public function baby(): BelongsTo
    {
        return $this->belongsTo(User::class, 'baby_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected function casts(): array
    {
        return [
            'type' => BabyActivityTypeEnum::class,
            'date' => 'datetime',
            'data' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }
}
