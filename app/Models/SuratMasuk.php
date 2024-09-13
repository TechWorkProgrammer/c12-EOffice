<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class SuratMasuk extends Model
{
    use HasFactory;

    protected $table = "surat_masuks";
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
        'uuid', 'klasifikasi_surat_id', 'nomor_surat', 'tanggal_surat', 'pengirim', 'perihal', 'file_surat', 'created_by', 'penerima_id', 'read_at'
    ];

    public function klasifikasiSurat()
    {
        return $this->belongsTo(MKlasifikasiSurat::class, 'klasifikasi_surat_id', 'uuid');
    }

    public function creator()
    {
        return $this->belongsTo(MUser::class, 'created_by', 'uuid');
    }

    public function penerima()
    {
        return $this->belongsTo(MUser::class, 'penerima_id', 'uuid');
    }

    public function disposisi()
    {
        return $this->hasOne(Disposisi::class, 'surat_id', 'uuid');
    }
}
