<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questioner extends Model
{
    use HasFactory;
    
    public function questionerUsers()
    {
        return $this->hasMany(QuestionerUser::class);
    }
}
