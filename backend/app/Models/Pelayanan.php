<?php

namespace App\Models;

use App\Models\Kunjungan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function kunjungan() {
      return $this->belongsTo(Kunjungan::class);
    }

    public function user() {
      return $this->belongsTo(User::class, 'petugas_id');
    }
}
