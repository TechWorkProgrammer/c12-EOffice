<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webpatser\Uuid\Uuid;

/**
 * @method static updateOrCreate(array $array, array $array1)
 * @method static where(string $string, mixed $questioner_id)
 * @method static create(array $array)
 */
class QuestionerUser extends Model
{
    use HasFactory;

    protected $primaryKey = "uuid";
    protected $keyType = 'string';
    public $incrementing = false;

    protected $casts = [
        'uuid' => 'string',
    ];

    protected $fillable = [
        'questioner_id', 'user_id', 'answer'
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Uuid::generate(4);
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'uuid');
    }

    public function questioner(): BelongsTo
    {
        return $this->belongsTo(Questioner::class, 'questioner_id', 'uuid');
    }
}
