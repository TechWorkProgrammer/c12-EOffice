<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Webpatser\Uuid\Uuid;

/**
 * @method static where(string $string, $klasifikasiId)
 * @method static create(array $validatedData)
 * @method static whereMonth(string $string, mixed $bulan)
 * @property mixed $uuid
 */
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

    public function klasifikasiSurat(): BelongsTo
    {
        return $this->belongsTo(MKlasifikasiSurat::class, 'klasifikasi_surat_id', 'uuid');
    }

    public function userStatus(): HasOne
    {
        return $this->hasOne(UserStatus::class, 'surat_masuk_id', 'uuid');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(MUser::class, 'created_by', 'uuid');
    }

    public function penerima(): BelongsTo
    {
        return $this->belongsTo(MUser::class, 'penerima_id', 'uuid');
    }

    public function disposisi(): HasOne
    {
        return $this->hasOne(Disposisi::class, 'surat_id', 'uuid');
    }
}
