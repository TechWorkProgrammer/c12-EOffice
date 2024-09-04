<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webpatser\Uuid\Uuid;

/**
 * @method static find($delivery_id)
 * @method static create(array $array)
 * @method static where(string $string, $uuid)
 * @property mixed $status
 * @property mixed $user
 * @property mixed $uuid
 * @property mixed $type
 */
class Delivery extends Model
{
    use HasFactory;

    protected $primaryKey = "uuid";
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string)Uuid::generate(4);
        });
    }

    protected $fillable = [
        'user_id', 'driver_id', 'admin_id', 'longitude', 'latitude', 'distance',
        'type', 'status', 'weight', 'description', 'confirmed_time', 'estimated_time'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'uuid');
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id', 'uuid');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id', 'uuid');
    }
}
