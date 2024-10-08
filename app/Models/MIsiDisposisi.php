<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class MIsiDisposisi extends Model
{
    use HasFactory;

    protected $table = "m_isi_disposisis";
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
        'uuid', 'isi'
    ];

    public function isiDisposisis()
    {
        return $this->hasMany(IsiDisposisi::class, 'isi_disposisi_id', 'id');
    }
}
