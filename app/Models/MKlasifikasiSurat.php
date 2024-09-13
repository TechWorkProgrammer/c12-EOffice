<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class MKlasifikasiSurat extends Model
{
    use HasFactory;

    protected $table = "m_klasifikasi_surats";
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
        'uuid', 'name'
    ];

    public function suratMasuks()
    {
        return $this->hasMany(SuratMasuk::class, 'klasifikasi_surat_id', 'id');
    }
}
