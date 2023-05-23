<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'kunjungan';
    protected $fillable = [
        'pengunjung_id',
        'tujuan',
        'kategori_id',
        'waktu_kunjungan'
    ];

    public function pengunjung() {
        return $this->belongsTo(Pengunjung::class);
    }

    public function kategori() {
        return $this->hasOne(Kategori::class);
    }
}
