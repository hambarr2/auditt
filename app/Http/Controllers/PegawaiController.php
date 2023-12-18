<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\Bidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PegawaiController extends Controller
{
    public function index()
    {
        $bidang = Bidang::all();
        $jabatan = Jabatan::all();
        $pegawai = Pegawai::all();
        return view('data_master.pegawai.index', compact('pegawai', 'bidang', 'jabatan'));
    }

    public function masuk(Request $request) {
        $data = [
            "nip" => $request->masuk_nip,
            "password" => $request->masuk_kata_sandi,
        ];
        // dd($data);
        if(Auth::attempt($data)) {
            Session::flash('alert', [
                // tipe dalam sweetalert2: success, error, warning, info, question
                'type' => 'success',
                'title' => 'Login Berhasil',
                'message' => "Selamat Datang ".Auth::user()->nama_pegawai,
            ]);
            if (Auth::user()->password_reset) {
                return redirect()->route('ubah_kata_sandi');
            }
            return redirect()->route("spt_pka.spt");
        }
        Session::flash('alert', [
            'type' => 'error',
            'title' => 'Login Gagal',
            'message' => "Username atau Password salah!",
        ]);
        return back();
    }

    public function keluar() {
        Auth::logout();
        Session::flash('alert', [
            'type' => 'success',
            'title' => 'Logout Berhasil',
            'message' => "",
        ]);
        return redirect()->route("masuk.akun");
    }

    public function ubah_kata_sandi(Request $request, $nip) {
        $this->validate($request, [
            'password_old' => 'required',
            'password_new' => 'required',
        ]);

        $pegawai = Pegawai::where('nip', $nip)->first();

        if($pegawai && password_verify($request->password_old, $pegawai->password)) {
            if ($request->password_old === $request->password_new || $request->password_new == "12345678") {
                Session::flash('alert', [
                    'type' => 'error',
                    'title' => 'Ubah Password Gagal',
                    'message' => "Password baru tidak boleh sama dengan yang lama",
                ]);
            } else {
                $pegawai->update([
                    'password' => bcrypt($request->password_new),
                    'password_reset' => 0,
                ]);
                Session::flash('alert', [
                    'type' => 'success',
                    'title' => 'Ubah Password Berhasil',
                    'message' => '',
                ]);
                return redirect()->route('spt_pka.spt');
            }
        } else {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Ubah Password Gagal',
                'message' => "Mohon dicek kembali inputannya!",
            ]);
        }

        return back();
    }

    public function tambah_pegawai_baru(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nip' => 'required',
            'nama_pegawai' => 'required',
            'nama_bidang' => 'required',
            'nama_jabatan' => 'required',
        ]);
        // dd($validator);
        if ($validator->fails()) {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Input Data Gagal',
                'message' => 'Ada inputan yang salah!',
            ]);
        } else {
            Pegawai::create([
                'nip' => $request->nip,
                'nama_pegawai' => $request->nama_pegawai,
                'nama_bidang' => $request->nama_bidang,
                'nama_jabatan' => $request->nama_jabatan,
                'password' => bcrypt('12345678'),
            ]);
    
            Session::flash('alert', [
                // tipe dalam sweetalert2: success, error, warning, info, question
                'type' => 'success',
                'title' => 'Input Data Berhasil',
                'message' => "",
            ]);
        }
        return back();
    }

    public function get_data_pegawai(Request $request)
    {
        $pegawai = Pegawai::where('nip', $request->id)->first();
        if($pegawai) {
            $bidang = Bidang::where('nama_bidang', $pegawai->nama_bidang)->first();
            $jabatan = Jabatan::where('nama_jabatan', $pegawai->nama_jabatan)->first();
            return response()->json([
                'status' => 'success',
                'nip' => $pegawai->nip,
                'nama' => $pegawai->nama_pegawai,
                'jabatan' => $jabatan,
                'bidang' => $bidang,
            ]);
        } else {
            return response()->json([
                'status' => 'error',
            ]);
        }
    }

    public function ubah_data_pegawai(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ubah_nip'=>'required',
            'ubah_nama_pegawai' => 'required',
            'nama_bidang' => 'required',
            'nama_jabatan' => 'required',
        ]);
        if ($validator->fails()) {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Input Data Gagal',
                'message' => 'Ada inputan yang salah!',
            ]);
        } else {
            $pegawai = Pegawai::where('nip', $request->nip)->first();

            if($pegawai){
                $pegawai->update([
                    'nip' => $request->ubah_nip,
                    'nama_pegawai' => $request->ubah_nama_pegawai,
                    'nama_jabatan' => $request->nama_jabatan,
                    'nama_bidang' => $request->nama_bidang,
                ]);
                Session::flash('alert', [
                    'type' => 'success',
                    'title' => 'Edit Data Berhasil',
                    'message' => "",
                ]);
            } else {
                Session::flash('alert', [
                    'type' => 'error',
                    'title' => 'Input Data Gagal',
                    'message' => 'Ada inputan yang salah!',
                ]); 
            }
        }
        return back();
    }

    public function destroy($nip) {
        $pegawai = Pegawai::findOrFail($nip);
        if($pegawai) {
            Session::flash('alert', [
                'type' => 'success',
                'title' => 'Hapus Data '.$pegawai->nama_pegawai.' Berhasil',
                'message' => "",
            ]); 
            $pegawai->delete();
        } else {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Hapus Data Gagal',
                'message' => 'NIP Tidak Valid!',
            ]); 
        }
        return back();
    }
}