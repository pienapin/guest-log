<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\Pelayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PelayananController extends Controller
{
  public function list(Request $request)
  {
    $query = Pelayanan::with(['kunjungan.pengunjung', 'user'])->orderBy('created_at', 'DESC');

    if ($request->petugas) {
      $query->whereHas('user', function($q) use ($request) { $q->where('nama', 'LIKE', '%'.$request->petugas.'%'); });
    }

    if ($request->instansi) {
      $query->whereHas('kunjungan.pengunjung', function($q) use ($request) { $q->where('instansi', 'LIKE', '%'.$request->instansi.'%'); });
    }

    if ($request->jabatan) {
      $query->whereHas('kunjungan.pengunjung', function($q) use ($request) { $q->where('jabatan', 'LIKE', '%'.$request->jabatan.'%'); });
    }

    if ($request->tujuan) {
      $query->whereHas('kunjungan', function($q) use ($request) { $q->where('tujuan', 'LIKE', '%'.$request->tujuan.'%'); });
    }

    if ($request->data_diminta) {
      $query->where('data_diminta', 'LIKE', '%'.$request->data_diminta.'%');
    }

    if ($request->status_layanan) {
      $query->where('status_layanan', 'LIKE', '%'.$request->status_layanan.'%');
    }

    if ($request->waktu_kunjungan) {
      $waktu = explode("_", $request->waktu_kunjungan);
      if ($waktu[1] == "null") {
        $waktu1 = $waktu[0];
      } else {
        $waktu1 = $waktu[1];
      }
      $query->whereHas('kunjungan', function($q) use ($waktu, $waktu1) { $q->where('waktu_kunjungan', '>=', $waktu[0].' 00:00:00')->where('waktu_kunjungan', '<=', $waktu1.' 23:59:59'); });
    }

    if ($request->perPage) {
      $perPage = $request->perPage;
    } else {
      $perPage = 15;
    }

    if ($request->paginate) {
      $pelayanan = $query->paginate($perPage);
    } else {
      $pelayanan = $query->get();
    }

    return response()->json([
      'message' => 'Pelayanan\'s list is successfully fetched',
      'data' => $pelayanan,
    ], 200);
  }

  public function dokumentasi($fileName)
  {
    $imageType = explode('.', $fileName)[1];
    $imgUrl = asset('pelayanan/'.$fileName);
    return response($imgUrl, 200);
  }

  public function submit(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'id' => 'required',
      'petugas_id' => 'required',
      'data_diminta' => 'required',
      'dokumentasi' => 'required|image|mimes:jpeg,png,jpg|max:5120',
      'status_layanan' => 'required',
      'keterangan_pelayanan' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => 'Validation erros',
        'errors' => $validator->messages()
      ], 422);
    }

    $pelayanan = Pelayanan::where('id', $request->id)->first();
    $kunjungan = Kunjungan::where('id', $pelayanan->kunjungan_id)->first();

    $imageName = time().'-'.$kunjungan->pengunjung_id.'.'.$request->dokumentasi->extension();
    $request->dokumentasi->move(public_path('pelayanan'), $imageName);

    $pelayanan->update([
      'petugas_id' => $request->petugas_id,
      'data_diminta' => $request->data_diminta,
      'dokumentasi' => $imageName,
      'status_layanan' => $request->status_layanan,
      'keterangan_pelayanan' => $request->keterangan_pelayanan,
    ]);

      return response()->json([
        'message'=>'Pelayanan\'s data is successfully updated',
        'data'=>$pelayanan 
      ], 200);
  }
}
