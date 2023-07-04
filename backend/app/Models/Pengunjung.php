<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengunjung extends Model
{
    use HasFactory;

    protected $table = 'pengunjung';
    protected $fillable = [
        'nama',
        'instansi',
        'jabatan',
        'email',
        'no_hp',
        'no_wa',
        'descriptors',
        'wajah_pengunjung'
    ];

    public function kunjungan() {
        return $this->hasMany(Kunjungan::class);
    }
}
