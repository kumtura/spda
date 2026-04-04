<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Administrator\DashboardController;
use App\Http\Controllers\Administrator\AgendaController;
use App\Http\Controllers\Administrator\KategoriAgendaController;
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

Route::get('/', [LandingController::class, 'home'])->name('public.home')->middleware('public.redirect');

Route::get('/berita', [LandingController::class, 'berita'])->name('public.berita')->middleware('public.redirect');
Route::get('/berita/kategori/{id}', [LandingController::class, 'berita_kategori'])->name('public.berita.kategori')->middleware('public.redirect');
Route::get('/berita/{id}', [LandingController::class, 'berita_detail'])->name('public.berita.detail')->middleware('public.redirect');
Route::post('/berita/{id}/komentar', [LandingController::class, 'berita_komentar'])->name('public.berita.komentar');
Route::get('/punia', [LandingController::class, 'punia'])->name('public.punia')->middleware('public.redirect');
Route::get('/punia/pembayaran', [LandingController::class, 'punia_pembayaran'])->name('public.punia.pembayaran')->middleware('public.redirect');
Route::post('/punia/pembayaran/submit', [LandingController::class, 'punia_pembayaran_submit'])->name('public.punia.pembayaran.submit');
Route::get('/punia/penggunaan/{id}', [LandingController::class, 'punia_penggunaan_detail'])->name('public.punia.penggunaan')->middleware('public.redirect');
Route::get('/punia/alokasi/{id}', [LandingController::class, 'punia_alokasi_detail'])->name('public.punia.alokasi.detail')->middleware('public.redirect');
Route::get('/punia/download-laporan', [LandingController::class, 'punia_download_laporan'])->name('public.punia.download');
Route::get('/punia/bayar/{id_usaha}', [\App\Http\Controllers\PuniaBayarController::class, 'show'])->name('public.punia.bayar');
Route::post('/punia/bayar/submit', [\App\Http\Controllers\PuniaBayarController::class, 'submit'])->name('public.punia.bayar.submit');
Route::get('/donasi', [LandingController::class, 'donasi'])->name('public.donasi')->middleware('public.redirect');
Route::get('/donasi/pembayaran/{id}', [LandingController::class, 'donasi_pembayaran'])->name('public.donasi.pembayaran')->middleware('public.redirect');
Route::get('/donasi/{id}', [LandingController::class, 'donasi_detail'])->name('public.donasi.detail')->middleware('public.redirect');
Route::post('/donasi/submit', [LandingController::class, 'donasi_post'])->name('public.donasi.submit');
Route::get('/pembayaran/metode', [LandingController::class, 'payment_methods'])->name('public.payment_methods');
Route::get('/pembayaran/manual', [LandingController::class, 'payment_manual'])->name('public.payment.manual');
Route::post('/pembayaran/manual/submit', [LandingController::class, 'payment_manual_submit'])->name('public.payment.manual.submit');
Route::get('/pembayaran/manual/sukses', [LandingController::class, 'payment_manual_success'])->name('public.payment.manual.success');
Route::post('/pembayaran/proses', [\App\Http\Controllers\PaymentController::class, 'initiate'])->name('public.payment_initiate');
Route::get('/pembayaran/hasil', [\App\Http\Controllers\PaymentController::class, 'showResult'])->name('public.payment_result');
Route::post('/pembayaran/simulate', [\App\Http\Controllers\PaymentController::class, 'simulate'])->name('public.payment_simulate');
Route::get('/pembayaran/status/{order_id}', [\App\Http\Controllers\PaymentController::class, 'checkStatus'])->name('public.payment_status');
Route::get('/unit-usaha', [LandingController::class, 'unit_usaha'])->name('public.unit_usaha')->middleware('public.redirect');
Route::get('/loker', [LandingController::class, 'loker'])->name('public.loker')->middleware('public.redirect');
Route::get('/loker/{id}', [LandingController::class, 'loker_detail'])->name('public.loker.detail')->middleware('public.redirect');
Route::get('/loker/{id}/apply', [LandingController::class, 'loker_apply_form'])->name('public.loker.apply_form');
Route::post('/loker/{id}/apply', [LandingController::class, 'loker_apply'])->name('public.loker.apply');
Route::get('/wisata', [LandingController::class, 'wisata'])->name('public.wisata')->middleware('public.redirect');
Route::get('/wisata/detail/{id}', [LandingController::class, 'wisata_detail'])->name('public.wisata.detail')->middleware('public.redirect');
Route::get('/agenda', [LandingController::class, 'agenda'])->name('public.agenda')->middleware('public.redirect');
Route::get('/krama-tamiu', [LandingController::class, 'krama_tamiu'])->name('public.krama_tamiu')->middleware('public.redirect');
Route::get('/krama-tamiu/daftar', [LandingController::class, 'krama_tamiu_register'])->name('public.krama_tamiu.register')->middleware('public.redirect');
Route::post('/krama-tamiu/daftar/submit', [LandingController::class, 'krama_tamiu_register_submit'])->name('public.krama_tamiu.register.submit');
Route::get('/wisata/beli/{slug}', [LandingController::class, 'wisata_beli'])->name('public.wisata.beli');
Route::post('/wisata/beli/submit', [LandingController::class, 'wisata_beli_submit'])->name('public.wisata.beli.submit');
Route::get('/wisata/data-pengunjung', [LandingController::class, 'wisata_data_pengunjung'])->name('public.wisata.data_pengunjung');
Route::post('/wisata/data-pengunjung/submit', [LandingController::class, 'wisata_data_pengunjung_submit'])->name('public.wisata.data_pengunjung.submit');
Route::get('/wisata/payment/methods', [LandingController::class, 'wisata_payment_methods'])->name('public.wisata.payment.methods');
Route::post('/wisata/payment/proceed', [LandingController::class, 'wisata_payment_proceed'])->name('public.wisata.payment.proceed');
Route::get('/wisata/payment/xendit', [LandingController::class, 'wisata_payment_xendit'])->name('public.wisata.payment.xendit');
Route::get('/wisata/payment/manual', [LandingController::class, 'wisata_payment_manual'])->name('public.wisata.payment.manual');
Route::post('/wisata/payment/manual/submit', [LandingController::class, 'wisata_payment_manual_submit'])->name('public.wisata.payment.manual.submit');
Route::get('/wisata/payment/manual/success', [LandingController::class, 'wisata_payment_manual_success'])->name('public.wisata.payment.manual.success');
Route::get('/wisata/payment/result', [LandingController::class, 'wisata_payment_result'])->name('public.wisata.payment.result');
Route::get('/wisata/payment/status', [LandingController::class, 'wisata_payment_status'])->name('public.wisata.payment.status');
Route::post('/wisata/payment/simulate', [LandingController::class, 'wisata_payment_simulate'])->name('public.wisata.payment.simulate');
Route::get('/wisata/tiket/success', [LandingController::class, 'wisata_tiket_success'])->name('public.wisata.tiket.success');
Route::get('/wisata/tiket/download/{kode}', [LandingController::class, 'wisata_tiket_download'])->name('public.wisata.tiket.download');
Route::get('/wisata/check-availability', [LandingController::class, 'wisata_check_availability'])->name('public.wisata.check_availability');

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
        // Unit Usaha (Level 3)
        Route::group(['middleware' => 'role:3'], function() {
            Route::get('/usaha/home', function() { return view('backend.usaha.home'); });
            Route::get('/usaha/punia', function() { return view('backend.usaha.punia'); });
            Route::post('/usaha/punia/bayar', [LandingController::class, 'usaha_punia_bayar'])->name('usaha.punia.bayar');
            Route::get('/usaha/punia/print', [LandingController::class, 'usaha_punia_print'])->name('usaha.punia.print');
            Route::get('/usaha/punia/receipt', [LandingController::class, 'usaha_punia_receipt'])->name('usaha.punia.receipt');
            Route::get('/usaha/loker', function() { return view('backend.usaha.loker'); });
            Route::get('/usaha/loker/detail/{id}', [LandingController::class, 'usaha_loker_detail'])->name('usaha.loker.detail');
            Route::post('/usaha/loker/create', [LandingController::class, 'usaha_loker_create'])->name('usaha.loker.create');
            Route::post('/usaha/loker/interview', [LandingController::class, 'usaha_loker_interview'])->name('usaha.loker.interview');
            Route::post('/usaha/loker/accept', [LandingController::class, 'usaha_loker_accept'])->name('usaha.loker.accept');
            Route::post('/usaha/loker/reject', [LandingController::class, 'usaha_loker_reject'])->name('usaha.loker.reject');
            Route::post('/usaha/loker/update-tk-counts', [LandingController::class, 'usaha_update_tk_counts'])->name('usaha.loker.update_tk_counts');
            Route::get('/usaha/donasi', [LandingController::class, 'usaha_donasi']);
            Route::get('/usaha/donasi/detail/{id}', [LandingController::class, 'usaha_donasi_detail']);
            Route::get('/usaha/berita', [LandingController::class, 'usaha_berita']);
            Route::get('/usaha/berita/detail/{id}', [LandingController::class, 'usaha_berita_detail']);
        });

        // Kelian Adat (Level 2) & Bendesa (Level 1)
        Route::group(['middleware' => 'role:1,2'], function() {
            Route::get('/kelian/punia', function() { return view('backend.kelian.punia'); });
            Route::get('/kelian/data_usaha', function() { return view('backend.kelian.data_usaha'); });
            Route::get('/kelian/detail_usaha/{id}', 'Administrator\UsahaController@kelian_detailUsaha');
            Route::post('/kelian/data_usaha/store', 'Administrator\UsahaController@kelian_storeUsaha');
            Route::post('/kelian/detail_usaha/bayar-manual', 'Administrator\UsahaController@kelian_manualPayment');
            Route::get('/kelian/donasi', function() { return view('backend.kelian.donasi'); });
            Route::get('/kelian/verifikasi', [DashboardController::class, 'verifikasi_pembayaran']);
            Route::get('/kelian/usaha', function() { return view('backend.kelian.usaha'); });
            
            // Tiket Wisata for Kelian
            Route::get('/kelian/tiket', 'Administrator\TiketWisataController@index');
            Route::get('/kelian/tiket/scan', 'Administrator\TiketWisataController@scan');
            Route::get('/kelian/tiket/jual', 'Administrator\TiketWisataController@jual');
            Route::post('/kelian/tiket/jual/submit', 'Administrator\TiketWisataController@jual_submit');
            Route::get('/kelian/tiket/jual/success/{id}', 'Administrator\TiketWisataController@jual_success');
            Route::post('/kelian/tiket/scan/validate', 'Administrator\TiketWisataController@scan_validate');
            
            // Objek Wisata Management for Kelian
            Route::get('/kelian/tiket/objek', 'Administrator\ObjekWisataController@index_kelian');
            Route::get('/kelian/tiket/objek/detail/{id}', 'Administrator\ObjekWisataController@detail_kelian');
            Route::get('/kelian/tiket/objek/create', 'Administrator\ObjekWisataController@create_kelian');
            Route::post('/kelian/tiket/objek/store', 'Administrator\ObjekWisataController@store');
            Route::get('/kelian/tiket/objek/edit/{id}', 'Administrator\ObjekWisataController@edit_kelian');
            Route::put('/kelian/tiket/objek/update/{id}', 'Administrator\ObjekWisataController@update');
            Route::get('/kelian/tiket/objek/delete/{id}', 'Administrator\ObjekWisataController@destroy');
            Route::get('/kelian/tiket/objek/toggle/{id}', 'Administrator\ObjekWisataController@toggle_status');
            
            // Kategori Tiket Management
            Route::post('/kelian/tiket/kategori/store', 'Administrator\ObjekWisataController@store_kategori');
            Route::put('/kelian/tiket/kategori/update/{id}', 'Administrator\ObjekWisataController@update_kategori');
            Route::get('/kelian/tiket/kategori/delete/{id}', 'Administrator\ObjekWisataController@delete_kategori');
        });

        // Kelian Adat only (Level 2) - Mobile Pendatang Pages
        Route::group(['middleware' => 'role:2'], function() {
            Route::get('/kelian/pendatang', 'Administrator\PendatangController@index');
            Route::get('/kelian/pendatang/setting', 'Administrator\PendatangController@setting');
            Route::post('/kelian/pendatang/setting/update', 'Administrator\PendatangController@updateSetting');
            Route::get('/kelian/pendatang/create', 'Administrator\PendatangController@create');
            Route::get('/kelian/pendatang/create-acara', 'Administrator\PendatangController@createAcara');
            Route::get('/kelian/pendatang/detail/{id}', 'Administrator\PendatangController@detail');
            Route::get('/kelian/pendatang/edit/{id}', 'Administrator\PendatangController@edit');
            Route::get('/kelian/pendatang/add-punia/{id}', 'Administrator\PendatangController@addPunia');
            Route::get('/kelian/pendatang/bayar/{id}', 'Administrator\PendatangController@bayarForm');
            Route::post('/kelian/pendatang/store', 'Administrator\PendatangController@store');
            Route::put('/kelian/pendatang/update/{id}', 'Administrator\PendatangController@update');
            Route::get('/kelian/pendatang/delete/{id}', 'Administrator\PendatangController@delete');
            Route::get('/kelian/pendatang/toggle/{id}', 'Administrator\PendatangController@toggle');
            Route::get('/kelian/pendatang/generate-tagihan/{id}', 'Administrator\PendatangController@generateTagihan');
            Route::post('/kelian/pendatang/update-punia-setting/{id}', 'Administrator\PendatangController@updatePuniaSetting');
            Route::get('/kelian/pendatang/kartu-punia/print/{id}', 'Administrator\PendatangController@printKartuPunia');
            Route::get('/kelian/pendatang/kartu-punia/{id}', 'Administrator\PendatangController@kartuPunia');
            Route::post('/kelian/pendatang/kartu-punia/bayar', 'Administrator\PendatangController@bayarKartuPunia');
            Route::post('/kelian/pendatang/kartu-punia/hapus', 'Administrator\PendatangController@hapusKartuPunia');
            
            // Punia Pendatang Management
            Route::post('/kelian/pendatang/punia/store', 'Administrator\PendatangController@storePunia');
            Route::post('/kelian/pendatang/punia/bayar/{id}', 'Administrator\PendatangController@bayarPunia');
            Route::get('/kelian/pendatang/punia/delete/{id}', 'Administrator\PendatangController@deletePunia');
            
            // Acara Punia Management
            Route::post('/kelian/pendatang/acara/store', 'Administrator\PendatangController@storeAcara');
            Route::get('/kelian/pendatang/acara/delete/{id}', 'Administrator\PendatangController@deleteAcara');
            Route::get('/kelian/pendatang/acara/toggle/{id}', 'Administrator\PendatangController@toggleAcara');
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
			// Verifikasi Pembayaran Manual
			Route::get('/verifikasi_pembayaran', [DashboardController::class, 'verifikasi_pembayaran'])->name('verifikasi_pembayaran');
			Route::post('/verifikasi_pembayaran/approve', [DashboardController::class, 'verifikasi_approve'])->name('verifikasi.approve');
			Route::post('/verifikasi_pembayaran/reject', [DashboardController::class, 'verifikasi_reject'])->name('verifikasi.reject');

			// Pendatang Management for Bendesa (Desktop Admin)
			Route::get('/pendatang', 'Administrator\PendatangController@indexBendesa');
			Route::get('/pendatang/setting', 'Administrator\PendatangController@settingBendesa');
			Route::post('/pendatang/setting/update', 'Administrator\PendatangController@updateSetting');
			Route::get('/pendatang/create', 'Administrator\PendatangController@createBendesa');
			Route::get('/pendatang/create-acara', 'Administrator\PendatangController@createAcaraBendesa');
			Route::get('/pendatang/detail/{id}', 'Administrator\PendatangController@detailBendesa');
			Route::get('/pendatang/edit/{id}', 'Administrator\PendatangController@editBendesa');
			Route::get('/pendatang/add-punia/{id}', 'Administrator\PendatangController@addPuniaBendesa');
			Route::get('/pendatang/bayar/{id}', 'Administrator\PendatangController@bayarFormBendesa');
			Route::post('/pendatang/store', 'Administrator\PendatangController@storeBendesa');
			Route::put('/pendatang/update/{id}', 'Administrator\PendatangController@updateBendesa');
			Route::get('/pendatang/delete/{id}', 'Administrator\PendatangController@deleteBendesa');
			Route::get('/pendatang/toggle/{id}', 'Administrator\PendatangController@toggleBendesa');
			Route::get('/pendatang/generate-tagihan/{id}', 'Administrator\PendatangController@generateTagihanBendesa');
			Route::post('/pendatang/update-punia-setting/{id}', 'Administrator\PendatangController@updatePuniaSettingBendesa');
			Route::get('/pendatang/kartu-punia/print/{id}', 'Administrator\PendatangController@printKartuPunia');
			Route::get('/pendatang/kartu-punia/{id}', 'Administrator\PendatangController@kartuPuniaBendesa');
			Route::post('/pendatang/kartu-punia/bayar', 'Administrator\PendatangController@bayarKartuPuniaBendesa');
			Route::post('/pendatang/punia/store', 'Administrator\PendatangController@storePuniaBendesa');
			Route::post('/pendatang/punia/bayar/{id}', 'Administrator\PendatangController@bayarPuniaBendesa');
			Route::get('/pendatang/punia/delete/{id}', 'Administrator\PendatangController@deletePuniaBendesa');
			Route::post('/pendatang/acara/store', 'Administrator\PendatangController@storeAcaraBendesa');
			Route::get('/pendatang/acara/delete/{id}', 'Administrator\PendatangController@deleteAcaraBendesa');
			Route::get('/pendatang/acara/toggle/{id}', 'Administrator\PendatangController@toggleAcaraBendesa');
			
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
			
			// Objek Wisata Routes
			Route::get('/objek_wisata','Administrator\ObjekWisataController@index');
			Route::get('/objek_wisata/create','Administrator\ObjekWisataController@create');
			Route::post('/objek_wisata/store','Administrator\ObjekWisataController@store');
			Route::get('/objek_wisata/edit/{id}','Administrator\ObjekWisataController@edit');
			Route::put('/objek_wisata/update/{id}','Administrator\ObjekWisataController@update');
			Route::get('/objek_wisata/delete/{id}','Administrator\ObjekWisataController@destroy');
			Route::get('/objek_wisata/toggle/{id}','Administrator\ObjekWisataController@toggle_status');
			
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

			// Keuangan (Finance)
			Route::get('keuangan','Administrator\KeuanganController@index');
			Route::post('keuangan/store','Administrator\KeuanganController@store');
			Route::get('keuangan/hapus/{id}','Administrator\KeuanganController@destroy');

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
            
            // Verifikasi Pembayaran
            Route::get('/verifikasi_pembayaran', 'Administrator\DashboardController@verifikasi_pembayaran');
            Route::post('/verifikasi_pembayaran/approve', 'Administrator\DashboardController@verifikasi_approve');
            Route::post('/verifikasi_pembayaran/reject', 'Administrator\DashboardController@verifikasi_reject');

            // Agenda Desa Adat
            Route::get('/agenda', [AgendaController::class, 'index']);
            Route::post('/agenda/post', [AgendaController::class, 'store']);
            Route::post('/agenda/update', [AgendaController::class, 'update']);
            Route::get('/agenda/hapus/{id}', [AgendaController::class, 'destroy']);
            Route::get('/agenda/toggle/{id}', [AgendaController::class, 'toggle_status']);

            Route::get('/kategori_agenda', [KategoriAgendaController::class, 'index']);
            Route::post('/kategori_agenda/post', [KategoriAgendaController::class, 'store']);
            Route::post('/kategori_agenda/update', [KategoriAgendaController::class, 'update']);
            Route::get('/kategori_agenda/hapus/{id}', [KategoriAgendaController::class, 'destroy']);
		});
        
        // Admin Settings
        Route::group(['middleware' => 'role:1,4'], function() {
            Route::get('/settings', 'Administrator\SettingController@index');
            Route::post('/settings/update_logo', 'Administrator\SettingController@update_logo');
            Route::post('/settings/upload_hero_slide', 'Administrator\SettingController@upload_hero_slide');
            Route::post('/settings/update_hero_slide_metadata', 'Administrator\SettingController@update_hero_slide_metadata');
            Route::post('/settings/delete_hero_slide', 'Administrator\SettingController@delete_hero_slide');
            Route::post('/settings/update_village', 'Administrator\SettingController@update_village');
            Route::post('/settings/update_bank_accounts', 'Administrator\SettingController@update_bank_accounts');
            Route::get('/settings/waha', 'Administrator\SettingController@waha')->name('settings.waha');
            Route::post('/settings/update_waha', 'Administrator\SettingController@update_waha');
            
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
