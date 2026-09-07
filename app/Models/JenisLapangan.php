<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisLapangan extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nama_jenis',
    ];

    public function lapangans()
    {
        return $this->hasMany(Lapangan::class, 'jenis_lapangan', 'id');
    }

    public function Lapangan()
    {
        return $this->lapangans();
    }
}
