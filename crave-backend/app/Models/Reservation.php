<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'user_id', 'name', 'phone', 'email',
        'date', 'time', 'guests',
        'table_no', 'status', 'note'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}