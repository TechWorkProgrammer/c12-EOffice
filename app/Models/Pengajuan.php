<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

/**
 * @method static where(string $string, mixed $uuid)
 * @method static create(array $array)
 */
class Pengajuan extends Model
{
    use HasFactory;

    protected $table = "pengajuans";
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
        'uuid', 'draft_id', 'penerima_id', 'status', 'catatan', 'tanda_tangan', 'read_at', 'confirmed_at', 'pengajuan_asal'
    ];

    public function draft()
    {
        return $this->belongsTo(Draft::class, 'draft_id', 'uuid');
    }

    public function penerima()
    {
        return $this->belongsTo(MUser::class, 'penerima_id', 'uuid');
    }

    public function prevPengajuan() {
        return $this->belongsTo(Pengajuan::class, 'pengajuan_asal', 'uuid');
    }

    public function pengajuanLevel2() {
        return $this->hasOne(Pengajuan::class, 'pengajuan_asal', 'uuid');
    }

    public function pengajuanLevel3() {
        return $this->hasOne(Pengajuan::class, 'pengajuan_asal', 'uuid');
    }
}
