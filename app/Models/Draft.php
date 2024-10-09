<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

/**
 * @method static whereMonth(string $string, mixed $bulan)
 * @method static where(string $string, mixed $uuid)
 * @method static create(array $validatedData)
 * @property mixed $pengajuans
 * @property mixed $uuid
 * @property mixed|string $status
 * @property mixed $created_by
 * @property mixed $creator
 * @property mixed $perihal
 * @property mixed $file_surat
 */
class Draft extends Model
{
    use HasFactory;

    protected $table = "drafts";
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
        'uuid', 'perihal', 'file_surat', 'status', 'created_by'
    ];

    public function pengajuans()
    {
        return $this->hasMany(Pengajuan::class, 'draft_id', 'uuid');
    }

    public function pengajuanLevel1()
    {
        return $this->hasOne(Pengajuan::class, 'draft_id', 'uuid')->where('pengajuan_asal', null);
    }

    public function creator()
    {
        return $this->belongsTo(MUser::class, 'created_by', 'uuid');
    }
}
