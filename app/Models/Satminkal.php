<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Satminkal extends Model
{
    use HasFactory;

    protected $table = "satminkals";
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
        'uuid',
        'kode_kotama',
        'kode_satminkal',
        'name',
    ];

    public function kotama()
    {
        return $this->belongsTo(Kotama::class, 'kode_kotama', 'kode');
    }
}
