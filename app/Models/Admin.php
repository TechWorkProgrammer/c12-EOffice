<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    public function pojokEdukasis()
    {
        return $this->hasMany(PojokEdukasi::class, 'created_by');
    }

    public function programs()
    {
        return $this->hasMany(Program::class, 'created_by');
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class, 'admin_id');
    }
}
