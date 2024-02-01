<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Bidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class JabatanController extends Controller
{
    public function index()
    {
        $bidang = Bidang::all();
        
        $jabatan = Jabatan::all();
        return view('data_master.jabatan.index', compact('jabatan', 'bidang'));
    }

    public function tambah_jabatan_baru(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_jabatan' => 'required',
            'nama_bidang' => 'required',
        ]);
        if ($validator->fails()) {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Input Data Gagal',
                'message' => 'Ada inputan yang salah!',
            ]);
        } else {
            Jabatan::create([
                'nama_jabatan' => $request->nama_jabatan,
                'nama_bidang' => $request->nama_bidang,
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

    public function get_data_jabatan(Request $request)
    {
        $jabatan = Jabatan::where('id_jabatan', $request->id)->first();
        if($jabatan) {
            $bidang = Bidang::where('nama_bidang', $jabatan->nama_bidang)->first();
            return response()->json([
                'status' => 'success',
                'jabatan' => $jabatan,
                'bidang' => $bidang,
            ]);
        } else {
            return response()->json([
                'status' => 'error',
            ]);
        }
    }

    public function ubah_data_jabatan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_jabatan'=>'required',
            'ubah_nama_jabatan' => 'required',
            'nama_bidang' => 'required',
        ]);
        if ($validator->fails()) {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Input Data Gagal',
                'message' => 'Ada inputan yang salah!',
            ]);
        } else {
            $jabatan = Jabatan::where('id_jabatan', $request->id_jabatan)->first();

            if($jabatan){
                $jabatan->update([
                    'nama_jabatan' => $request->ubah_nama_jabatan,
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

    public function destroy($id_jabatan) {
        $jabatan = Jabatan::findOrFail($id_jabatan);
        if($jabatan) {
            Session::flash('alert', [
                'type' => 'success',
                'title' => 'Hapus Data '.$jabatan->nama_jabatan.' Berhasil',
                'message' => "",
            ]); 
            $jabatan->delete();
        } else {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Hapus Data Gagal',
                'message' => 'ID Jabatan Tidak Valid!',
            ]); 
        }
        return back();
    }
}
