<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\PengunjungController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/pengunjung', [PengunjungController::class, 'list']);
Route::post('/pengunjung/submit', [PengunjungController::class, 'submit']);
Route::post('/pengunjung/delete/{id}', [PengunjungController::class, 'del']);

Route::get('/kunjungan', [KunjunganController::class, 'list']);
Route::post('/kunjungan/submit', [KunjunganController::class, 'submit']);
Route::delete('/kunjungan/delete/{id}', [KunjunganController::class, 'del']);

Route::get('/kategori', [KategoriController::class, 'list']);
Route::post('/kategori/add', [KategoriController::class, 'add']);
Route::delete('/kategori/delete', [KategoriController::class, 'del']);