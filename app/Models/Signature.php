<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Signature extends Model
{
    use HasFactory;

    protected $table = "signatures";
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
        'uuid',
        'user_id',
        'image',
        'doc_name',
        'doc_page',
        'doc_ext'
    ];

    public function user()
    {
        return $this->belongsTo(MUser::class, 'user_id', 'uuid');
    }
}
