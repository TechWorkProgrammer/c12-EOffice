<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Webpatser\Uuid\Uuid;

/**
 * @method static where(string $string, $klasifikasiId)
 * @method static whereMonth(string $string, mixed $bulan)
 * @method static create(array $array)
 */
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

    public function ekspedisi(): HasOne
    {
        return $this->hasOne(Ekspedisi::class, 'surat_keluar_id', 'uuid');
    }

    public function klasifikasi_surat(): BelongsTo
    {
        return $this->belongsTo(MKlasifikasiSurat::class, 'klasifikasi_surat_id', 'uuid');
    }

    public function pengirim(): BelongsTo
    {
        return $this->belongsTo(MUser::class, 'pengirim', 'uuid');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(MUser::class, 'created_by', 'uuid');
    }
}
