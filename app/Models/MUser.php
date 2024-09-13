<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Webpatser\Uuid\Uuid;

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
        'uuid', 'name', 'email', 'role', 'pejabat_id', 'password'
    ];

    public function pejabat()
    {
        return $this->belongsTo(MPejabat::class, 'pejabat_id', 'uuid');
    }

    public function suratMasuks()
    {
        return $this->hasMany(SuratMasuk::class, 'created_by', 'id');
    }

    public function disposisis()
    {
        return $this->hasMany(Disposisi::class, 'created_by', 'id');
    }

    public function logDisposisis()
    {
        return $this->hasMany(LogDisposisi::class, 'pengirim', 'id');
    }
}
