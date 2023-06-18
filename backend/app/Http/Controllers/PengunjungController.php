<?php

namespace App\Http\Controllers;

use App\Models\Pengunjung;
use Illuminate\Http\Request;
use App\Exports\PengunjungExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

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

    if ($request->jabatan) {
      $query->where('jabatan', 'LIKE', '%'.$request->jabatan.'%');
    }

    if ($request->email) {
      $query->where('email', 'LIKE', '%'.$request->email.'%');
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

  public function submit(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'nama' => 'required',
      'instansi' => 'required',
      'jabatan' => 'required',
      'email' => 'required',
      'no_hp' => 'required',
      'no_wa' => 'required',
      'descriptors' => 'required'
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => 'Validation erros',
        'errors' => $validator->messages()
      ], 422);
    }

    if ($request->pengunjung_id) {
      $pengunjung = Pengunjung::where('id', $request->pengunjung_id);
      $pengunjung->update([
          'nama' => $request->nama,
          'instansi' => $request->instansi,
          'jabatan' => $request->jabatan,
          'email' => $request->email,
          'no_hp' => $request->no_hp,
          'no_wa' => $request->no_wa,
          'descriptors' => $request->descriptors
      ]);

      return response()->json([
        'message'=>'Pengunjung\'s data is successfully updated',
        'data'=>$pengunjung 
      ], 200);
    } else {
        $pengunjung = Pengunjung::create([
            'nama' => $request->nama,
            'instansi' => $request->instansi,
            'jabatan' => $request->jabatan,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'no_wa' => $request->no_wa,
            'descriptors' => $request->descriptors
        ]);

        return response()->json([
          'message'=>'Pengunjung\'s data is successfully created',
          'data'=>$pengunjung 
        ], 200);
    }
  }

  public function del($id)
  {
    $pengunjung = Pengunjung::where('id', $id);

    $pengunjung->delete();

    return response()->json([
      'message' => 'Pengunjung is successfully deleted',
    ], 200);
  }

  public function export(Request $request)
  {
    $nama = "";
    $instansi = "";
    $jabatan = "";
    
    if ($request->nama) {
      $nama = $request->nama;
    }
    if ($request->instansi) {
      $instansi = $request->instansi;
    }
    if ($request->jabatan) {
      $jabatan = $request->jabatan;
    }

    return Excel::download(new PengunjungExport($nama, $instansi, $jabatan), 'Pengunjung.xlsx');
  }
}
