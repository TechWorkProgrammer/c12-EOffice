<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static first()
 * @method static updateOrCreate(int[] $array, float[] $array1)
 * @method static create(array $validatedData)
 */
class Server extends Model
{
    use HasFactory;

    protected $fillable = ['longitude', 'latitude'];

    public static function boot(): void
    {
        parent::boot();

        static::deleting(function () {
            return false;
        });
    }
}
