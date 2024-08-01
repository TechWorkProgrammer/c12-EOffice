<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PojokEdukasiContent extends Model
{
    use HasFactory;

    public function pojokEdukasi()
    {
        return $this->belongsTo(PojokEdukasi::class, 'pojok_edukasi_id');
    }
}
