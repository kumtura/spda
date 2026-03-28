<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Administrator\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [LandingController::class, 'home'])->name('public.home');

Route::get('/berita', [LandingController::class, 'berita'])->name('public.berita');
Route::get('/berita/kategori/{id}', [LandingController::class, 'berita_kategori'])->name('public.berita.kategori');
Route::get('/berita/{id}', [LandingController::class, 'berita_detail'])->name('public.berita.detail');
Route::post('/berita/{id}/komentar', [LandingController::class, 'berita_komentar'])->name('public.berita.komentar');
Route::get('/punia', [LandingController::class, 'punia'])->name('public.punia');
Route::get('/punia/pembayaran', [LandingController::class, 'punia_pembayaran'])->name('public.punia.pembayaran');
Route::post('/punia/pembayaran/submit', [LandingController::class, 'punia_pembayaran_submit'])->name('public.punia.pembayaran.submit');
Route::get('/punia/penggunaan/{id}', [LandingController::class, 'punia_penggunaan_detail'])->name('public.punia.penggunaan');
Route::get('/punia/alokasi/{id}', [LandingController::class, 'punia_alokasi_detail'])->name('public.punia.alokasi.detail');
Route::get('/punia/download-laporan', [LandingController::class, 'punia_download_laporan'])->name('public.punia.download');
Route::get('/donasi', [LandingController::class, 'donasi'])->name('public.donasi');
Route::get('/donasi/pembayaran/{id}', [LandingController::class, 'donasi_pembayaran'])->name('public.donasi.pembayaran');
Route::get('/donasi/{id}', [LandingController::class, 'donasi_detail'])->name('public.donasi.detail');
Route::post('/donasi/submit', [LandingController::class, 'donasi_post'])->name('public.donasi.submit');
Route::get('/pembayaran/metode', [LandingController::class, 'payment_methods'])->name('public.payment_methods');
Route::post('/pembayaran/proses', [\App\Http\Controllers\PaymentController::class, 'initiate'])->name('public.payment_initiate');
Route::get('/pembayaran/hasil', [\App\Http\Controllers\PaymentController::class, 'showResult'])->name('public.payment_result');
Route::post('/pembayaran/simulate', [\App\Http\Controllers\PaymentController::class, 'simulate'])->name('public.payment_simulate');
Route::get('/pembayaran/status/{order_id}', [\App\Http\Controllers\PaymentController::class, 'checkStatus'])->name('public.payment_status');
Route::get('/unit-usaha', [LandingController::class, 'unit_usaha'])->name('public.unit_usaha');

Route::middleware(['guest'])->group(function () {
    Route::get('/register_usaha', function () {
        $banjar = App\Models\Banjar::where('aktif', '1')->get();
        $kategori = App\Models\Kategori_Usaha::where('aktif', '1')->get();
        $village = ['name' => 'SPDA']; // Fallback for layouts
        return view('front.pages.register_usaha', compact('banjar', 'kategori', 'village'));
    })->name('public.register_usaha');

    Route::post('/register_usaha/submit', function (\Illuminate\Http\Request $request) {
        \App\Models\Usaha::post_data_usaha($request);
        return redirect('/')->with('success', 'Pendaftaran usaha berhasil dikirim. Kami akan memproses permohonan Anda segera.');
    })->name('public.register_usaha.submit');
});

Route::match(['get', 'post'], '/logoutadmin', function (\Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logoutadmin');

Route::match(['get', 'post'], '/logout', function (\Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout_general');

Route::get('/dashboard', function () {
    return redirect()->route('administrator.home');
})->middleware(['auth'])->name('dashboard');

Route::group(['prefix' => 'administrator', 'middleware' => 'admin' , 'as' => 'administrator.'], function () {

		Route::get('/', [
			'as'   => 'dashboard',
			'uses' => 'Administrator\DashboardController@indexhome',
		]);

		Route::get('/home', [DashboardController::class, 'indexhome'])->name('home')->middleware('role:1,2,3,4');

		Route::get('/get_danapunia_range', [
			'uses' => 'Administrator\DashboardController@get_danapunia_range',
		]);

		// Profile & General
		Route::get('/userprofile', 'UserController@indexuser')->middleware('role:1,2,3');
        Route::post('update_user', 'UserController@updatepost_profile'); // For profile update

		// Usaha Management
		Route::get('/download_usaha_pdf', 'Administrator\UsahaController@download_usaha_pdf');
		Route::post('/post_search_usaha', 'Administrator\UsahaController@post_search_usaha');
		Route::post('/submit_post_add_usaha','Administrator\UsahaController@submit_post_add_usaha');
		Route::put('/update_post_add_usaha','Administrator\UsahaController@update_post_add_usaha');
		Route::post('/upload_gambar_usaha/{index}','Administrator\UsahaController@upload_gambar_usaha');
		Route::get('/approve_usaha/{index}','Administrator\UsahaController@approve_usaha');
		Route::get('/detail_usaha/{index}', 'Administrator\UsahaController@get_detailUsaha');
		Route::post('/post_pembayaran_baru/{index}','Administrator\UsahaController@post_pembayaran_baru');
		Route::get('/get_pembayaran_detail/{index}','Administrator\UsahaController@get_pembayaran_detail');

        // Unit Usaha Mobile Features
        Route::group(['middleware' => 'role:3'], function() {
            Route::get('/usaha/iuran', function() { return view('backend.usaha.iuran'); });
            Route::get('/usaha/loker', function() { return view('backend.usaha.loker'); });
        });

		// Karyawan / Tenaga Kerja
		Route::get('/data_tenagakerja','Administrator\KaryawanController@index');
		Route::get('/data_tenagakerja_aktif','Administrator\KaryawanController@indexAktif');
		Route::get('/data_tenagakerja_skill','Administrator\KaryawanController@index_skill');
		Route::get('/data_tenagakerja_interview','Administrator\KaryawanController@indexInterview');
		Route::get('/data_tenagakerja_approve','Administrator\KaryawanController@indexApprove');
		Route::get('/data_loker','Administrator\KaryawanController@indexLoker');

		Route::post('submit_post_add_tenagakerja','Administrator\KaryawanController@submit_post_add_tenagakerja');
		Route::put('update_post_add_tenagakerja','Administrator\KaryawanController@update_post_add_tenagakerja');
		Route::get('detail_tenaga_kerja/{index}','Administrator\KaryawanController@detail_tenaga_kerja');
		Route::post('submit_hire_tenaga','Administrator\KaryawanController@submit_hire_tenaga');
		Route::post('approve_data_karyawan','Administrator\KaryawanController@approve_data_karyawan');
		Route::post('/upload_gambar_karyawan/{index}','Administrator\KaryawanController@upload_gambar_karyawan');

		// Dana Punia & Sumbangan
		Route::group(['middleware' => 'role:1,2'], function() {
			Route::get('/datapunia_wajib','Administrator\DanaPuniaController@list_datapunia_wajib');
			Route::get('/datapunia_wajib/{index}/{tanggal}','Administrator\DanaPuniaController@list_datapunia_wajib_param');
			Route::get('/list_datapunia_wajib/{index}','Administrator\DanaPuniaController@list_datapunia_wajib');
			Route::get('download_pdf_danapunia','Administrator\DanaPuniaController@download_pdf_danapunia');
			
			// Kategori Punia Routes
			Route::get('/kategori_punia','Administrator\KategoriPuniaController@index');
			Route::post('/kategori_punia/post','Administrator\KategoriPuniaController@store');
			Route::post('/kategori_punia/update','Administrator\KategoriPuniaController@update');
			Route::get('/kategori_punia/hapus/{id}','Administrator\KategoriPuniaController@destroy');
			
			// Alokasi Punia Routes
			Route::get('/alokasi_punia','Administrator\AlokasiPuniaController@index');
			Route::post('/alokasi_punia/post','Administrator\AlokasiPuniaController@store');
			Route::post('/alokasi_punia/update','Administrator\AlokasiPuniaController@update');
			Route::get('/alokasi_punia/hapus/{id}','Administrator\AlokasiPuniaController@destroy');
			
			Route::get('/data_usaha','Administrator\UsahaController@ambil_listUsaha');
			
			Route::get('/databanjar','Administrator\BanjarController@index');
			Route::get('ambil_listbanjar','Administrator\BanjarController@ambil_listbanjar');
			Route::post('post_data_banjar','Administrator\BanjarController@post_data_banjar');
			Route::post('hapusbanjar','Administrator\BanjarController@hapusbanjar');
		});

		// Bendesa Adat, Kelian Adat & Admin Sistem (Level 1, 2 & 4)
		Route::group(['middleware' => 'role:1,2,4'], function() {
			Route::get('/datauser', function () {
                $banjar = App\Models\Banjar::where('aktif', '1')->get();
				return view('admin.pages.data_user.table', compact('banjar'));
			});
			Route::get('ambil_listuser','UserController@ambil_listuser');
			Route::post('post_user','UserController@post_user');
			Route::get('ambil_user/{index}','UserController@ambil_user');
			Route::post('updateuser','UserController@updateuser');
			Route::get('hapususer','UserController@destroy');

			Route::get('/datakategori', function () {
				return view('admin.pages.data_kategori.table');
			});
			Route::get('ambil_listkategori','KategoriController@ambil_listkategori');
			Route::get('ambil_listkategori_awal','KategoriController@ambil_listkategori_awal');
			Route::post('post_kategori','KategoriController@post_kategori');
			Route::get('ambil_kategori/{index}','KategoriController@ambil_kategori');
			Route::post('updatekategori','KategoriController@updatekategori');
			Route::get('hapuskategori','KategoriController@hapuskategori');
			Route::get('/databerita', function () {
                $kategori = App\Models\Kategori_Berita::where('aktif', '1')->get();
				return view('admin.pages.data_berita.table', compact('kategori'));
			});
			Route::get('ambil_listberita_kategori','BeritaController@ambil_listberita_kategori');
			Route::get('ambil_berita/{index}','BeritaController@ambil_berita');
			Route::post('post_berita_baru','BeritaController@tambahberita');
			Route::post('updateberita','BeritaController@updateberita');
			Route::get('hapusberita','BeritaController@hapusberita');
			
			Route::get('/datamenu','Administrator\MenuController@index');
			Route::get('ambil_listmenu','Administrator\MenuController@ambil_listmenu');
			Route::post('post_data_menu','Administrator\MenuController@post_data_menu');


			Route::get('datasumbangan','Administrator\SumbanganController@index');
			Route::post('submit_post_add_sumbangan','Administrator\SumbanganController@submit_post_add_sumbangan');
			Route::get('download_pdf_sumbangan','Administrator\SumbanganController@download_pdf_sumbangan');

			// Kategori Donasi Routes
			Route::get('/kategori_donasi','Administrator\KategoriDonasiController@index');
			Route::post('/kategori_donasi/post','Administrator\KategoriDonasiController@store');
			Route::post('/kategori_donasi/update','Administrator\KategoriDonasiController@update');
			Route::get('/kategori_donasi/hapus/{id}','Administrator\KategoriDonasiController@destroy');
			
			// Program Donasi Routes
			Route::get('/program_donasi','Administrator\ProgramDonasiController@index');
			Route::post('/program_donasi/post','Administrator\ProgramDonasiController@store');
			Route::post('/program_donasi/update','Administrator\ProgramDonasiController@update');
			Route::get('/program_donasi/hapus/{id}','Administrator\ProgramDonasiController@destroy');

			Route::get('data_kategoriberita','Administrator\KategoriBeritaController@index');
			Route::post('post_kategori_berita','Administrator\KategoriBeritaController@post_kategori_berita');
			Route::get('hapus_kategori_berita','Administrator\KategoriBeritaController@hapus_kategori_berita');

			// Financial Archives (Reports)
			Route::get('/data_laporan', function () {
				return view('admin.pages.data_laporan.table');
			});
			Route::get('ambil_listlaporan', 'LaporanController@ambil_listlaporan');
			Route::post('tambahlaporan', 'LaporanController@tambahlaporan');
			Route::post('updatelaporan', 'LaporanController@updatelaporan');
			Route::get('hapuswarta', 'LaporanController@hapus_laporan'); 

            // Tenaga Kerja Sub-pages
            Route::get('/data_tenagakerja_interview', 'Administrator\KaryawanController@indexInterview');
            Route::get('/data_tenagakerja_approve', 'Administrator\KaryawanController@indexApprove');
            Route::get('/data_tenagakerja_skill', 'Administrator\KaryawanController@index_skill');
            Route::post('/post_data_skill', 'Administrator\KaryawanController@post_data_skill');
		});
        
        // Admin Settings
        Route::group(['middleware' => 'role:1,4'], function() {
            Route::get('/settings', 'Administrator\SettingController@index');
            Route::post('/settings/update_logo', 'Administrator\SettingController@update_logo');
            Route::post('/settings/upload_hero_slide', 'Administrator\SettingController@upload_hero_slide');
            Route::post('/settings/delete_hero_slide', 'Administrator\SettingController@delete_hero_slide');
            Route::post('/settings/update_village', 'Administrator\SettingController@update_village');
            
            // Payment Gateway Settings
            Route::get('/settings/payment_gateway', 'Administrator\PaymentGatewayController@index')->name('settings.payment_gateway');
            Route::post('/settings/payment_gateway/post', 'Administrator\PaymentGatewayController@store')->name('settings.payment_gateway.post');
            Route::post('/settings/payment_gateway/channel/{id}', 'Administrator\PaymentGatewayController@updateChannel')->name('settings.payment_gateway.channel.update');
        });

		// Misc / Legacy
		Route::get('/laporan_keuangan', function () {
			return view('front.pages.data_laporan.table');
		});
		Route::get('/datakategorial', function () {
			return view('front.pages.data_kategorial.table');
		});
		Route::get('/duitkutes','Administrator\DuitkuController@pay_credit_card');

		// API Hooks (if needed in admin panel)
		Route::get('get_users_all','Api\ApiUser@get_users_all');
		Route::get('berita_all','Api\ApiBerita@get_berita_all');
		Route::get('berita_index/{index}','Api\ApiBerita@get_berita_by_id');
		Route::get('kategorial_all','Api\ApiKategorial@get_kategorial_all');
		Route::get('kategorial_index/{index}','Api\ApiKategorial@get_kategorial_by_id');


		// Api / Mobile Routes
		Route::group(['prefix' => 'api', 'middleware' => 'role:1,2,3'], function() {
			Route::get('berita_all','Api\ApiBerita@get_berita_all');
			Route::get('berita_index/{index}','Api\ApiBerita@get_berita_by_id');
			Route::get('kategorial_all','Api\ApiKategorial@get_kategorial_all');
			Route::get('kategorial_index/{index}','Api\ApiKategorial@get_kategorial_by_id');
			Route::get('get_users_all','Api\ApiUser@get_users_all');
		});
    });

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
