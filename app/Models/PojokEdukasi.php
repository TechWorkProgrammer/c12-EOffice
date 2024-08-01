<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PojokEdukasi extends Model
{
    use HasFactory;

    public function contents()
    {
        return $this->hasMany(PojokEdukasiContent::class, 'pojok_edukasi_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
}
