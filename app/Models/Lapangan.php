<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lapangan extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nama_lapangan',
        'jenis_lapangan',
        'gambar_lapangan',
        'deskripsi_lapangan',
        'harga_sewa',
        'status',
        'jam_buka',
        'jam_tutup'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function booking()
    {
        return $this->bookings();
    }

    public function jenisLapangan()
    {
        return $this->belongsTo(JenisLapangan::class, 'jenis_lapangan', 'id');
    }

    public function getGambarUrlAttribute(): ?string
    {
        if (!$this->gambar_lapangan) {
            return null;
        }

        if (str_starts_with($this->gambar_lapangan, 'http://') || str_starts_with($this->gambar_lapangan, 'https://')) {
            return $this->gambar_lapangan;
        }

        return asset('storage/' . $this->gambar_lapangan);
    }
}

