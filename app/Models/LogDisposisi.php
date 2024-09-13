<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class LogDisposisi extends Model
{
    use HasFactory;

    protected $table = "log_disposisis";
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
        'uuid', 'pengirim', 'penerima', 'disposisi_id', 'read_at', 'pelaksanaan_at'
    ];

    public function disposisi()
    {
        return $this->belongsTo(Disposisi::class, 'disposisi_id', 'uuid');
    }

    public function pengirimUser()
    {
        return $this->belongsTo(MUser::class, 'pengirim', 'uuid');
    }

    public function penerimaUser()
    {
        return $this->belongsTo(MUser::class, 'penerima', 'uuid');
    }
}
