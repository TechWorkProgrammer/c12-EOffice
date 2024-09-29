<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

/**
 * @method static create(array $array)
 * @method static whereMonth(string $string, mixed $bulan)
 * @property mixed $uuid
 * @property mixed $logDisposisis
 */
class Disposisi extends Model
{
    use HasFactory;

    protected $table = "disposisis";
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
        'uuid', 'surat_id', 'disposisi_asal', 'catatan', 'tanda_tangan', 'created_by'
    ];

    public function suratMasuk()
    {
        return $this->belongsTo(SuratMasuk::class, 'surat_id', 'uuid');
    }

    public function asalDisposisi()
    {
        return $this->belongsTo(Disposisi::class, 'disposisi_asal', 'uuid');
    }

    public function disposisiLevel2()
    {
        return $this->hasMany(Disposisi::class, 'disposisi_asal', 'uuid');
    }

    public function disposisiLevel3()
    {
        return $this->hasMany(Disposisi::class, 'disposisi_asal', 'uuid');
    }

    public function isiDisposisis()
    {
        return $this->hasMany(IsiDisposisi::class, 'disposisi_id', 'uuid');
    }

    public function creator()
    {
        return $this->belongsTo(MUser::class, 'created_by', 'uuid');
    }

    public function logDisposisis()
    {
        return $this->hasMany(LogDisposisi::class, 'disposisi_id', 'uuid');
    }
}
