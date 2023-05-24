<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use Illuminate\Http\Request;

class KunjunganController extends Controller
{
    public function list(Request $request)
    {
        $query = Kunjungan::with(['pengunjung', 'kategori']);
        $kunjungan = $query->get();

        return response()->json([
            'message'=>'Kunjungan\'s list is successfully fetched',
            'data'=>$kunjungan,
        ], 200);
    }
}
