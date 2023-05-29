<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KunjunganController extends Controller
{
  public function list(Request $request)
  {
    $query = Kunjungan::with(['pengunjung', 'kategori'])->orderBy('waktu_kunjungan', 'DESC');

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
      'message' => 'Kunjungan\'s list is successfully fetched',
      'data' => $kunjungan,
    ], 200);
  }

  public function submit(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'pengunjung_id' => 'required',
      'tujuan' => 'required',
      'kategori_id' => 'required',
      'waktu_kunjungan' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => 'Validation erros',
        'errors' => $validator->messages()
      ], 422);
    }

    $kunjungan = Kunjungan::create([
      'pengunjung_id' => $request->pengunjung_id,
      'tujuan' => $request->tujuan,
      'kategori_id' => $request->kategori_id,
      'waktu_kunjungan' => $request->waktu_kunjungan
    ]);

    return response()->json([
      'message' => 'Kunjungan information is succesfully stored',
      'data' => $kunjungan,
    ]);
  }

  public function del($id)
  {
    $kunjungan = Kunjungan::where('id', $id);

    $kunjungan->delete();

    return response()->json([
      'message' => 'kunjungan is successfully deleted',
    ], 200);
  }
}
