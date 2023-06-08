<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function list(Request $request)
    {
      $query = User::where('id', '>', 0)->orderBy('role_id', 'ASC');

      if ($request->nip) {
        $query->where('nip', 'LIKE', '%'.$request->nip.'%');
      }

      if ($request->nama) {
        $query->where('nama', 'LIKE', '%'.$request->nama.'%');
      }

      if ($request->email) {
        $query->where('email', 'LIKE', '%'.$request->email.'%');
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
        $users = $query->paginate($perPage);
      } else {
        $users = $query->get();
      }

      return response()->json([
        'message' => 'Users\' is successfully fetched!',
        'data' => $users,
      ]);
    }

    public function add(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'nip' => 'required',
        'nama' => 'required',
        'email' => 'required',
        'no_wa' => 'required',
        'role_id' => 'required',
      ]);
  
      if ($validator->fails()) {
        return response()->json([
          'message' => 'Validation erros',
          'errors' => $validator->messages()
        ], 422);
      }

      $user = User::create([
        'nip' => $request->nip,
        'nama' => $request->nama,
        'email' => $request->email,
        'no_wa' => $request->no_wa,
        'role_id' => $request->role_id,
      ]);

      return response()->json([
        'message' => 'User is successfully created!',
        'data' => $user,
      ]);
    }

    public function edit(Request $request)
    {
      
    }

    public function del($id)
    {
      
    }
}
