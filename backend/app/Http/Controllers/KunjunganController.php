<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use Illuminate\Http\Request;

class KunjunganController extends Controller
{
    public function list(Request $request)
    {
        $query = Kunjungan::with(['pengunjung', 'kategori']);

        if ($request->perPage) {
            $perPage = $request->perPage;
        } else {
            $perPage = 15;
        }

        if ($request->paginate) {
            $kunjungan = $query->paginate($perPage);
        } else {
            $kunjungan = $query->get();
        }

        return response()->json([
            'message'=>'Kunjungan\'s list is successfully fetched',
            'data'=>$kunjungan,
        ], 200);
    }
}
