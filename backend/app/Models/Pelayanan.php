<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelayanan extends Model
{
    use HasFactory;

    protected $table = 'pelayanan';
    protected $fillable = [
        'kunjungan_id',
        'petugas_id',
        'data_diminta',
        'dokumentasi',
        'status_layanan',
        'keterangan_pelayanan',
    ];
}
