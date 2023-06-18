<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Kategori;
use App\Models\Kunjungan;
use App\Models\Pelayanan;
use Illuminate\Http\Request;
use App\Exports\KunjunganExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class KunjunganController extends Controller
{
  public function list(Request $request)
  {
    $query = Kunjungan::with(['pengunjung', 'kategori'])->orderBy('waktu_kunjungan', 'DESC');

    if ($request->keyword) {
      $query->whereHas('pengunjung', function($q) use ($request) { $q->where('nama', 'LIKE', '%'.$request->keyword.'%'); });
    }

    if ($request->instansi) {
      $query->whereHas('pengunjung', function($q) use ($request) { $q->where('instansi', 'LIKE', '%'.$request->instansi.'%'); });
    }

    if ($request->jabatan) {
      $query->whereHas('pengunjung', function($q) use ($request) { $q->where('jabatan', 'LIKE', '%'.$request->jabatan.'%'); });
    }

    if ($request->no_hp) {
      $query->whereHas('pengunjung', function($q) use ($request) { $q->where('no_hp', 'LIKE', '%'.$request->no_hp.'%'); });
    }

    if ($request->no_wa) {
      $query->whereHas('pengunjung', function($q) use ($request) { $q->where('no_wa', 'LIKE', '%'.$request->no_wa.'%'); });
    }

    if ($request->kategori) {
      $query->whereHas('kategori', function($q) use ($request) { $q->where('kategori', 'LIKE', '%'.$request->kategori.'%'); });
    }

    if ($request->waktu_kunjungan) {
      $waktu = explode("_", $request->waktu_kunjungan);
      if ($waktu[1] == "null") {
        $waktu1 = $waktu[0];
      } else {
        $waktu1 = $waktu[1];
      }
      $query->where('waktu_kunjungan', '>=', $waktu[0].' 00:00:00')->where('waktu_kunjungan', '<=', $waktu1.' 23:59:59');
    }

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

    if ($request->kategori_id == 1) {
      $pelayanan = Pelayanan::create(['kunjungan_id' => $kunjungan->id]);
    }

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

  public function count()
  {
    $kategori = Kategori::get();
    $data = array();
    
    //get count
    $kategori_jumlah = array();
    foreach ($kategori as $k) {
      $kunjungan = count(Kunjungan::where('kategori_id', $k->id)->get());
      array_push($kategori_jumlah, $kunjungan);
    }

    $kategori_label = array();
    foreach ($kategori as $k) {
      array_push($kategori_label, $k->kategori);
    }

    $data['kategori_jumlah'] = $kategori_jumlah;
    $data['kategori_label'] = $kategori_label;

    $kunjungan_pekan_ini = Kunjungan::whereBetween('waktu_kunjungan', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();
    $data['jumlah_pekan_ini'] = count($kunjungan_pekan_ini);
    
    $kunjungan_hari_ini = Kunjungan::whereDate('waktu_kunjungan', Carbon::today())->get();
    $data['jumlah_hari_ini'] = count($kunjungan_hari_ini);
    
    $kunjungan_bulan_ini = Kunjungan::whereMonth('waktu_kunjungan', Carbon::today()->month)->get();
    $data['jumlah_bulan_ini'] = count($kunjungan_bulan_ini);

    $kunjungan_tahun_ini = Kunjungan::whereYear('waktu_kunjungan', Carbon::today()->year)->get();
    $data['jumlah_tahun_ini'] = count($kunjungan_tahun_ini);

    return response()->json([
      'message' => 'kunjungan count is successfully fetched',
      'data' => $data,
    ], 200);
  }

  public function export(Request $request)
  {
    if ($request->waktu_kunjungan) {
      $waktu = explode(",", $request->waktu_kunjungan);
      if ($waktu[1] == "") {
        $waktu1 = $waktu[0];
      } else {
        $waktu1 = $waktu[1];
      }
      return Excel::download(new KunjunganExport($waktu[0].' 00:00:00', $waktu1.' 23:59:59'), 'Kunjungan.xlsx');
    }
  }
}
