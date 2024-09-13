<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class IsiDisposisi extends Model
{
    use HasFactory;

    protected $table = "isi_disposisis";
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
        'uuid', 'disposisi_id', 'isi_disposisi_id'
    ];

    public function disposisi()
    {
        return $this->belongsTo(Disposisi::class, 'disposisi_id', 'uuid');
    }

    public function isiDisposisi()
    {
        return $this->belongsTo(MIsiDisposisi::class, 'isi_disposisi_id', 'uuid');
    }
}
