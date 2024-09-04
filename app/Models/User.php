<?php

namespace App\Models;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Webpatser\Uuid\Uuid;

/**
 * @property string $uuid
 * @property string $phone_number
 * @property string $password
 * @property string $address
 * @property string|null $email
 * @property string $name
 * @property string $birthdate
 * @property string $role
 * @property int $point
 * @property bool $is_verified
 * @method static Builder|User updateOrCreate(array $attributes, array $values)
 * @method static Builder|User find($uuid)
 * @method static Builder|User whereDoesntHave(string $relation, Closure $callback)
 * @method static Builder|User where(string $column, $operator = null, $value = null, $boolean = 'and')
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

    public function getJWTIdentifier(): string
    {
        return $this->uuid;
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

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
}
