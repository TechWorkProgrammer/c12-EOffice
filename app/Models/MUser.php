<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Webpatser\Uuid\Uuid;

/**
 * @method static create(array $validatedData)
 * @method static inRandomOrder()
 * @method static where(string $string, string $string1)
 * @property mixed $uuid
 * @property mixed $role
 * @property mixed $pejabat
 * @property mixed $name
 */
class MUser extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $table = "m_users";
    protected $primaryKey = "uuid";
    protected $keyType = 'string';
    public $incrementing = false;

    protected $casts = [
        'uuid' => 'string',
    ];

    public function getJWTIdentifier()
    {
        return $this->uuid;
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string)Uuid::generate(4);
        });
    }

    protected $fillable = [
        'name', 'email', 'role', 'pejabat_id', 'satminkal_id', 'password'
    ];

    protected $hidden = [
        'password'
    ];

    public function pejabat(): BelongsTo
    {
        return $this->belongsTo(MPejabat::class, 'pejabat_id', 'uuid');
    }

    public function suratMasuks(): HasMany
    {
        return $this->hasMany(SuratMasuk::class, 'created_by', 'id');
    }

    public function disposisis(): HasMany
    {
        return $this->hasMany(Disposisi::class, 'created_by', 'id');
    }

    public function logDisposisis(): HasMany
    {
        return $this->hasMany(LogDisposisi::class, 'pengirim', 'id');
    }
}
