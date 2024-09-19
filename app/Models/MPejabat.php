<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

/**
 * @method static where(string $string, string $string1)
 * @property mixed $uuid
 */
class MPejabat extends Model
{
    use HasFactory;

    protected $table = "m_pejabats";
    protected $primaryKey = "uuid";
    protected $keyType = 'string';
    public $incrementing = false;

    protected $casts = [
        'uuid' => 'string',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string)Uuid::generate(4);
        });
    }

    protected $fillable = [
        'uuid', 'name', 'atasan_id'
    ];

    public function atasan()
    {
        return $this->belongsTo(MPejabat::class, 'atasan_id', 'uuid');
    }

    public function bawahans()
    {
        return $this->hasMany(MPejabat::class, 'atasan_id', 'uuid');
    }

    public function user()
    {
        return $this->hasOne(MUser::class, 'pejabat_id', 'uuid');
    }

    public function pelaksanas() {
        return $this->hasMany(MUser::class, 'pejabat_id', 'uuid');
    }
}
