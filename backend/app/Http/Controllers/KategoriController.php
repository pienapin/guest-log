<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function list(Request $request)
    {
      $query = Kategori::where('id', '>', '0');
      $kategori = $query->get();

      return response()->json([
        'message' => 'Kategori\'s list is successfully fetched',
        'data' => $kategori,
      ], 200);
    }

    public function add(Request $request)
    {
      $kategori = Kategori::create([
        'kategori' => $request->kategori,
      ]);

      return response()->json([
        'message' => 'kategori is successfully added',
        'data' => $kategori,
      ], 200);
    }

    public function del(Request $request)
    {
      $kategori = Kategori::where('id', $request->kategori_id);

      $kategori->delete();

      return response()->json([
        'message' => 'kategori is successfully deleted',
      ], 200);
    }
}
