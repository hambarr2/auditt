<?php

use App\Http\Controllers\BidangController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PKAController;
use App\Http\Controllers\SPTController;
use App\Models\Bidang;
use App\Models\Jabatan;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// })->name('welcome');

Route::middleware('guest')->group(function() {
    Route::get('masuk', function () {
        return view('masuk');
    })->name('masuk'); // GET Akses Laman Login
    Route::post('masuk', [PegawaiController::class, 'masuk'])->name('masuk.akun'); // POST Aksi Proses Login
});
Route::middleware('auth')->group(function() {
    Route::get('keluar', [PegawaiController::class, 'keluar'])->name('keluar.akun'); // GET Aksi Proses Logout

    Route::get('ubah_kata_sandi', function () {
        return view('ubah_kata_sandi');
    })->name('ubah_kata_sandi'); // GET Akses Laman Ubah Password
    Route::put('ubah_kata_sandi/{nip}', [PegawaiController::class, 'ubah_kata_sandi'])->name('submit_kata_sandi'); // PUT Aksi Proses Ubah Password


    Route::group(['middleware' => 'ubah.kata.sandi'], function () {
        Route::get('/', function () {
            return view('dasbor');
        })->name('dasbor'); // GET Akses Laman Dasbor
        Route::prefix('data-master')->group(function(){
            Route::get('pegawai', [PegawaiController::class, 'index'])->name('data_master.pegawai'); // GET Akses Laman Pegawai
            Route::post('tambah_pegawai', [PegawaiController::class, 'tambah_pegawai_baru'])->name('tambah_pegawai'); // POST Aksi Proses Tambah pegawai
            Route::post('get_data_pegawai', [PegawaiController::class, 'get_data_pegawai'])->name('get_data_pegawai'); // POST Akses Data Pegawai
            Route::put('ubah_data_pegawai', [PegawaiController::class, 'ubah_data_pegawai'])->name('ubah_data_pegawai'); // PUT Aksi Proses Ubah Pegawai
            Route::delete('pegawai/{nip}/hapus', [PegawaiController::class, 'destroy'])->name('hapus_pegawai');

            Route::get('bidang', [BidangController::class, 'index'])->name('data_master.bidang'); // GET Akses Laman Bidang
            Route::post('tambah_bidang', [BidangController::class, 'tambah_bidang_baru'])->name('tambah_bidang'); // POST Aksi Proses Tambah Bidang
            Route::post('get_data_bidang', [BidangController::class, 'get_data_bidang'])->name('get_data_bidang'); // POST Akses Data Bidang
            Route::put('ubah_data_bidang', [BidangController::class, 'ubah_data_bidang'])->name('ubah_data_bidang'); // PUT Aksi Proses Ubah Bidang
            Route::delete('bidang/{id_bidang}/hapus', [BidangController::class, 'destroy'])->name('hapus_bidang');

            Route::get('jabatan', [JabatanController::class, 'index'])->name('data_master.jabatan'); // GET Akses Laman Jabatan
            Route::post('tambah_jabatan', [JabatanController::class, 'tambah_jabatan_baru'])->name('tambah_jabatan'); // POST Aksi Proses Tambah Jabatan
            Route::post('get_data_jabatan', [JabatanController::class, 'get_data_jabatan'])->name('get_data_jabatan'); // POST Akses Data Jabatan
            Route::put('ubah_data_jabatan', [JabatanController::class, 'ubah_data_jabatan'])->name('ubah_data_jabatan'); // PUT Aksi Proses Ubah Jabatan
            Route::delete('jabatan/{id_jabatan}/hapus', [JabatanController::class, 'destroy'])->name('hapus_jabatan');
        });


        Route::put('ubah_data_pka', [PKAController::class, 'ubah_data_pka'])->name('ubah_data_pka');
        Route::post('get_data_pka', [PKAController::class, 'get_data_pka'])->name('get_data_pka');
        Route::get('ubah_isi_pka/{id}', [PKAController::class, 'ubah_isian_pka']);
        Route::delete('pka/{id_pka}/hapus', [PKAController::class, 'destroy'])->name('hapus_pka');

        Route::get('spt', [SPTController::class, 'index'])->name('spt_pka.spt'); // GET Akses Laman SPT
        Route::post('buat_spt', [SPTController::class, 'buat_spt_irban'])->name('buat_spt'); // POST Aksi Proses Buat SPT

        Route::get('riwayat_spt', [SPTController::class, 'riwayatSPT'])->name('spt_pka.riwayat_spt'); // GET Akses Laman Riwayat

        Route::put('detail_spt_irban', [SPTController::class, 'detail_spt_irban'])->name('detail_spt_irban'); // POST Akses Data SPT
        Route::post('get_data_spt_irban', [SPTController::class, 'get_data_spt_irban'])->name('get_data_spt_irban'); // POST Akses Data SPT
        Route::put('ubah_spt', [SPTController::class, 'ubah_spt_irban'])->name('ubah_spt_irban'); // PUT Aksi Proses Ubah SPT
        Route::put('kirim_spt/{id_spt}/irban', [SPTController::class, 'kirim_spt_irban'])->name('kirim_spt_irban'); // PUT Aksi Kirim SPT Irban 1 ke 2


        Route::post('get_data_spt_ketua', [SPTController::class, 'get_data_spt_ketua'])->name('get_data_spt_ketua'); // POST Akses Data SPT
        Route::put('lengkapi_spt', [SPTController::class, 'lengkapi_spt_ketua'])->name('lengkapi_spt_ketua'); // PUT Aksi Proses Ubah SPT
        Route::put('kirim_spt/{id_spt}/ketua', [SPTController::class, 'kirim_spt_ketua'])->name('kirim_spt_ketua'); // PUT Aksi Kirim SPT Irban 2 ke 3

        Route::put('verifikasi_spt/{id_spt}/sekre', [SPTController::class, 'verifikasi_spt_sekre'])->name('verifikasi_spt_sekre'); // PUT Aksi Kirim SPT Sekre 4 ke 5
        Route::put('verifikasi_spt/{id_spt}/selesai', [SPTController::class, 'verifikasi_spt_selesai'])->name('verifikasi_spt_selesai'); // PUT Aksi Kirim SPT Sekre 5 ke 6
        Route::get('detail_spt/{id_spt}', [SPTController::class, 'detail_spt'])->name('detail_spt');

        Route::post('simpan_tanggal', [SPTController::class, 'simpan_tanggal'])->name('simpan_tanggal');


        Route::post('buat_pka', [PKAController::class, 'buat_pka_ketua'])->name('buat_pka');
        Route::get('pka/{id_spt}', [PKAController::class, 'index'])->name('spt_pka.pka');
        Route::get('detail_pka/{id_spt}', [PKAController::class, 'detail_pka_irban'])->name('spt_pka.detail_pka');

        
        Route::get('spt_cetak/{id_spt}', [SPTController::class, 'cetak'])->name('cetak');
        Route::get('pka_cetak/{id_spt}', [PKAController::class, 'cetak2'])->name('cetak2');

    });
});
