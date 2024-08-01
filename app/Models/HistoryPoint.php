<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryPoint extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function trashPickup()
    {
        return $this->belongsTo(TrashPickup::class, 'trash_pickup_id');
    }

    public function hadiah()
    {
        return $this->belongsTo(Hadiah::class, 'hadiah_id');
    }
}
