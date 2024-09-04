<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webpatser\Uuid\Uuid;

/**
 * @method static create(array $array)
 * @method static where(string $string, $uuid)
 */
class HistoryPoint extends Model
{
    use HasFactory;

    protected $primaryKey = "uuid";
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id', 'description', 'prizes_id', 'point'
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string)Uuid::generate(4);
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'uuid');
    }

    public function delivery(): BelongsTo
    {
        return $this->belongsTo(Delivery::class, 'delivery_id', 'uuid');
    }

    public function prize(): BelongsTo
    {
        return $this->belongsTo(Prize::class, 'prizes_id', 'uuid');
    }
}
