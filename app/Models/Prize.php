<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webpatser\Uuid\Uuid;

/**
 * @method static find(mixed $uuid)
 * @method static create(array $validatedData)
 * @method static where(string $string, string $string1, mixed $uuid)
 * @method static findOrFail(mixed $uuid)
 * @method static updateOrCreate(array $array, array $prize)
 * @method static whereHas(string $string, \Closure $param)
 * @property mixed $image
 * @property mixed $uuid
 * @property mixed $possibility
 */
class Prize extends Model
{
    use HasFactory;

    protected $table = 'prizes';

    protected $primaryKey = "uuid";
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name', 'possibility'
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string)Uuid::generate(4);
        });
    }

    public function historyPoints(): HasMany
    {
        return $this->hasMany(HistoryPoint::class, 'prizes_id');
    }

}
