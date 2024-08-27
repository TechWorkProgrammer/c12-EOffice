<?php

namespace App\Models;

use Closure;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webpatser\Uuid\Uuid;

/**
 * @method static first()
 * @method static updateOrCreate(array $array, array $questionerData)
 * @method static withCount(string[] $array)
 * @method static create(array $validatedData)
 * @method static whereDoesntHave(string $string, Closure $param)
 * @method static doesntHave(string $string, Closure $param)
 * @property mixed $question
 */
class Questioner extends Model
{
    use HasFactory;

    protected $primaryKey = "uuid";
    protected $keyType = 'string';
    public $incrementing = false;

    protected $casts = [
        'uuid' => 'string',
    ];

    protected $fillable = [
        'question', 'is_active'
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Uuid::generate(4);
        });
    }

    public function questionerUsers(): HasMany
    {
        return $this->hasMany(QuestionerUser::class, 'questioner_id');
    }
}
