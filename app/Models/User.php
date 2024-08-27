<?php

namespace App\Models;

use Closure;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Webpatser\Uuid\Uuid;

/**
 * @property mixed $phone_number
 * @property mixed|string $password
 * @property mixed $address
 * @property mixed|string $email
 * @property mixed $name
 * @property mixed $birthdate
 * @property mixed|string $role
 * @property mixed $uuid
 * @property int|mixed $point
 * @property mixed|true $is_verified
 * @method static updateOrCreate(array $array, array $userData)
 * @method static find($uuid)
 * @method static whereDoesntHave(string $string, Closure $param)
 * @method static where(string $string, $uuid)
 */
class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    const ROLE_USER = 'user';
    const ROLE_DRIVER = 'driver';
    const ROLE_ADMIN = 'admin';

    protected $primaryKey = "uuid";
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'phone_number', 'password', 'address', 'email', 'name', 'birthdate', 'role', 'is_verified', 'point'
    ];

    protected $casts = [
        'uuid' => 'string',
    ];

    protected $hidden = [
        'password',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Uuid::generate(4);
        });
    }

    public function getJWTIdentifier()
    {
        return $this->uuid;
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    /*public function trashPickupsAsUser(): HasMany
    {
        return $this->hasMany(Delivery::class, 'user_id');
    }

    public function trashPickupsAsDriver(): HasMany
    {
        return $this->hasMany(Delivery::class, 'driver_id');
    }*/

    public function questionerUsers(): HasMany
    {
        return $this->hasMany(QuestionerUser::class, 'user_id');
    }

    public function getUnansweredActiveQuestions()
    {
        return Questioner::whereDoesntHave('questionerUsers', function ($query) {
            $query->where('user_id', $this->uuid);
        })->where('is_active', true)->get();
    }
/*
    public function historyPoints(): HasMany
    {
        return $this->hasMany(HistoryPoint::class);
    }*/
}
