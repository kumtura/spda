<?php

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

Route::middleware(['admin.guest'])->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    });
});

Route::get('administrator/', function () {
    return view('auth.login');
});
	
Route::get('administrator/login', function () {
	return view('auth.login');
});

Route::group(['prefix' => 'administrator', 'middleware' => 'admin' , 'as' => 'administrator'], function () {

		Route::get('/', [
			'as'   => 'dashboard',
			'uses' => 'Administrator\DashboardController@indexhome',
		]);

		Route::get('/home', [
			'as'   => 'home',
			'uses' => 'Administrator\DashboardController@indexhome',
		])->middleware('role:1,2,3');

		Route::get('/get_danapunia_range', [
			'uses' => 'Administrator\DashboardController@get_danapunia_range',
		]);

		// Profile & General
		Route::get('/userprofile', 'UserController@indexuser')->middleware('role:1,2,3');
        Route::post('update_user', 'UserController@update_user'); // For profile update

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

		// Karyawan / Tenaga Kerja
		Route::get('/data_tenagakerja','Administrator\KaryawanController@index');
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
			
			Route::get('/data_usaha','Administrator\UsahaController@ambil_listUsaha');
			
			Route::get('/databanjar','Administrator\BanjarController@index');
			Route::get('ambil_listbanjar','Administrator\BanjarController@ambil_listbanjar');
			Route::post('post_data_banjar','Administrator\BanjarController@post_data_banjar');
			Route::post('hapusbanjar','Administrator\BanjarController@hapusbanjar');
		});

		// Bendesa Adat Only (Level 1)
		Route::group(['middleware' => 'role:1'], function() {
			Route::get('/datauser', function () {
				return view('admin.pages.data_user.table');
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
				return view('admin.pages.data_berita.table');
			});
			Route::get('/datamenu','Administrator\MenuController@index');
			Route::get('ambil_listmenu','Administrator\MenuController@ambil_listmenu');
			Route::post('post_data_menu','Administrator\MenuController@post_data_menu');

			Route::get('/datakategori_slides','Administrator\GambarSlidesController@index');
			Route::get('ambil_listkategori_slides','Administrator\GambarSlidesController@ambil_listkategori_slides');
			Route::get('/datagambar_slides','Administrator\GambarSlidesController@index_gambar');
			Route::get('/get_gambar_slide','Administrator\GambarSlidesController@get_gambar_slide');
			Route::post('post_gambar_baru','Administrator\GambarSlidesController@post_data_slides');
			Route::post('post_gambar_baru_edit','Administrator\GambarSlidesController@post_gambar_baru_edit');
			Route::get('ambil_listslides/{kategori}','Administrator\GambarSlidesController@ambil_listslides');
			Route::post('post_active_slides','Administrator\GambarSlidesController@post_active_slides');

			Route::get('datasumbangan','Administrator\SumbanganController@index');
			Route::post('submit_post_add_sumbangan','Administrator\SumbanganController@submit_post_add_sumbangan');
			Route::get('download_pdf_sumbangan','Administrator\SumbanganController@download_pdf_sumbangan');

			Route::get('data_kategoriberita','Administrator\KategoriBeritaController@index');
			Route::post('post_kategori_berita','Administrator\KategoriBeritaController@post_kategori_berita');
			Route::get('hapus_kategori_berita','Administrator\KategoriBeritaController@hapus_kategori_berita');
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

	
	Route::post('runlogin','LoginController@runlogin');

	Route::get('logoutadmin','LoginController@logout');

	


Auth::routes();