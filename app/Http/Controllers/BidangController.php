<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class BidangController extends Controller
{
    public function index()
    {
        $bidang = Bidang::all();
        return view('data_master.bidang.index', compact('bidang'));
    }

    public function tambah_bidang_baru(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_bidang' => 'required',
        ]);
        if ($validator->fails()) {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Input Data Gagal',
                'message' => 'Ada inputan yang salah!',
            ]);
        } else {
            Bidang::create([
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

    public function get_data_bidang(Request $request)
    {
        $bidang = Bidang::where('id_bidang', $request->id)->first();
        if($bidang) {
            return response()->json([
                'status' => 'success',
                'bidang' => $bidang,
            ]);
        } else {
            return response()->json([
                'status' => 'error',
            ]);
        }
    }

    public function ubah_data_bidang(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_bidang'=>'required',
            'ubah_nama_bidang' => 'required',
        ]);
        if ($validator->fails()) {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Input Data Gagal',
                'message' => 'Ada inputan yang salah!',
            ]);
        } else {
            $bidang = Bidang::where('id_bidang', $request->id_bidang)->first();

            if($bidang){
                $bidang->update([
                    'nama_bidang' => $request->ubah_nama_bidang,
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

    public function destroy($id_bidang) {
        $bidang = Bidang::findOrFail($id_bidang);
        if($bidang) {
            Session::flash('alert', [
                'type' => 'success',
                'title' => 'Hapus Data '.$bidang->nama_bidang.' Berhasil',
                'message' => "",
            ]); 
            $bidang->delete();
        } else {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Hapus Data Gagal',
                'message' => 'ID Bidang Tidak Valid!',
            ]); 
        }
        return back();
    }
}
