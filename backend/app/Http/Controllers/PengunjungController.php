<?php

namespace App\Http\Controllers;

use App\Models\Pengunjung;
use Illuminate\Http\Request;

class PengunjungController extends Controller
{
    public function list(Request $request)
    {
        $query = Pengunjung::where('id', '>', 0);
        
        if ($request->keyword) {
          $query->where('nama', 'LIKE', '%'.$request->keyword.'%');
        }
        
        if ($request->instansi) {
          $query->where('instansi', 'LIKE', '%'.$request->instansi.'%');
        }

        if ($request->no_hp) {
          $query->where('no_hp', 'LIKE', '%'.$request->no_hp.'%');
        }

        if ($request->no_wa) {
          $query->where('no_wa', 'LIKE', '%'.$request->no_wa.'%');
        }

        if ($request->perPage) {
          $perPage = $request->perPage;
        } else {
          $perPage = 15;
        }
        
        if ($request->paginate) {
          $pengunjung = $query->paginate($perPage);
        } else {
          $pengunjung = $query->get();
        }
        return response()->json([
            'message'=>'Pengunjungs\' data is successfully fetched',
            'data'=>$pengunjung 
        ], 200);
    }
}
