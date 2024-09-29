<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webpatser\Uuid\Uuid;

/**
 * @method static firstOrCreate(array $array, null[] $array1)
 * @method static where(string $string, mixed $uuid)
 * @method static whereMonth(string $string, mixed $bulan)
 */
class UserStatus extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'surat_masuk_id', 'read_at', 'pelaksanaan_at'];

    protected $table = "user_statuses";
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(MUser::class, 'user_id', 'uuid');
    }

    public function suratMasuk(): BelongsTo
    {
        return $this->belongsTo(SuratMasuk::class, 'surat_masuk_id', 'uuid');
    }
}
