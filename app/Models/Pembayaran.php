<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $fillable = [
        'booking',
        'metode_pembayaran',
        'bukti_pembayaran',
        'status',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking', 'id');
    }
}
