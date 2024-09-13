<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class SuratKeluar extends Model
{
    use HasFactory;

    protected $table = "surat_keluars";
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
        'uuid', 'nomor_surat', 'klasifikasi_surat_id', 'pengirim', 'tipe', 'perihal', 'file_surat', 'tujuan', 'created_by'
    ];

    public function ekspedisi()
    {
        return $this->hasOne(Ekspedisi::class, 'surat_keluar_id', 'uuid');
    }

    public function klasifikasi_surat()
    {
        return $this->belongsTo(MKlasifikasiSurat::class, 'klasifikasi_surat_id', 'uuid');
    }

    public function pengirim()
    {
        return $this->belongsTo(MUser::class, 'pengirim', 'uuid');
    }

    public function creator()
    {
        return $this->belongsTo(MUser::class, 'created_by', 'uuid');
    }
}
