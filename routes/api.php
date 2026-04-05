<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/webhooks/xendit', [\App\Http\Controllers\XenditWebhookController::class, 'handle']);

// Upload gambar usaha
Route::post('/upload_gambar_usaha/{index}', [\App\Http\Controllers\Administrator\UsahaController::class, 'upload_gambar_usaha']);

/*
|--------------------------------------------------------------------------
| Custom API v1 - Integrasi Pihak Ketiga (Manus, OpenClaw, dll)
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->group(function () {

    // Punia
    Route::middleware('api_token:read:punia')->group(function () {
        Route::get('/punia', [\App\Http\Controllers\Api\V1\PuniaApiController::class, 'index']);
        Route::get('/punia/summary', [\App\Http\Controllers\Api\V1\PuniaApiController::class, 'summary']);
        Route::get('/punia/kategori', [\App\Http\Controllers\Api\V1\PuniaApiController::class, 'kategori']);
        Route::get('/punia/alokasi', [\App\Http\Controllers\Api\V1\PuniaApiController::class, 'alokasi']);
        Route::get('/punia/pendatang', [\App\Http\Controllers\Api\V1\PuniaApiController::class, 'puniaPendatang']);
    });

    // Krama Tamiu
    Route::middleware('api_token:read:krama-tamiu')->group(function () {
        Route::get('/krama-tamiu', [\App\Http\Controllers\Api\V1\KramaTamiuApiController::class, 'index']);
        Route::get('/krama-tamiu/count', [\App\Http\Controllers\Api\V1\KramaTamiuApiController::class, 'count']);
        Route::get('/krama-tamiu/belum-punia', [\App\Http\Controllers\Api\V1\KramaTamiuApiController::class, 'belumPunia']);
        Route::get('/krama-tamiu/acara-punia', [\App\Http\Controllers\Api\V1\KramaTamiuApiController::class, 'acaraPunia']);
        Route::get('/krama-tamiu/{id}', [\App\Http\Controllers\Api\V1\KramaTamiuApiController::class, 'show']);
    });

    // Unit Usaha
    Route::middleware('api_token:read:usaha')->group(function () {
        Route::get('/usaha', [\App\Http\Controllers\Api\V1\UsahaApiController::class, 'index']);
        Route::get('/usaha/kategori', [\App\Http\Controllers\Api\V1\UsahaApiController::class, 'kategori']);
        Route::get('/usaha/belum-punia', [\App\Http\Controllers\Api\V1\UsahaApiController::class, 'belumPunia']);
        Route::get('/usaha/{id}', [\App\Http\Controllers\Api\V1\UsahaApiController::class, 'show']);
    });

    // Donasi
    Route::middleware('api_token:read:donasi')->group(function () {
        Route::get('/donasi/program', [\App\Http\Controllers\Api\V1\DonasiApiController::class, 'program']);
        Route::get('/donasi/summary', [\App\Http\Controllers\Api\V1\DonasiApiController::class, 'summary']);
        Route::get('/donasi/kategori', [\App\Http\Controllers\Api\V1\DonasiApiController::class, 'kategori']);
        Route::get('/donasi/program/{id}', [\App\Http\Controllers\Api\V1\DonasiApiController::class, 'programDetail']);
    });

    // Tiket Wisata
    Route::middleware('api_token:read:tiket')->group(function () {
        Route::get('/tiket/objek-wisata', [\App\Http\Controllers\Api\V1\TiketApiController::class, 'objekWisata']);
        Route::get('/tiket/penjualan', [\App\Http\Controllers\Api\V1\TiketApiController::class, 'penjualan']);
        Route::get('/tiket/summary', [\App\Http\Controllers\Api\V1\TiketApiController::class, 'summary']);
        Route::get('/tiket/objek-wisata/{id}', [\App\Http\Controllers\Api\V1\TiketApiController::class, 'objekWisataDetail']);
        Route::get('/tiket/ketersediaan/{id}', [\App\Http\Controllers\Api\V1\TiketApiController::class, 'ketersediaan']);
    });

    // Keuangan
    Route::middleware('api_token:read:keuangan')->group(function () {
        Route::get('/keuangan/ringkasan', [\App\Http\Controllers\Api\V1\KeuanganApiController::class, 'ringkasan']);
        Route::get('/keuangan/pemasukan', [\App\Http\Controllers\Api\V1\KeuanganApiController::class, 'pemasukan']);
    });
});
