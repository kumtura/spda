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
