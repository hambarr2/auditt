<?php

namespace App\Http\Controllers;

use App\Models\AnggotaSPT;
use Carbon\Carbon;
use App\Models\SPT;
use App\Models\Pegawai;
use App\Models\PKA;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class SPTController extends Controller
{
    public function index()
    {
        $pegawais = Pegawai::all();
        $spt = SPT::all();
        $statusNames = [
            1 => 'Draf',
            2 => 'Terkirim',
            3 => 'Menunggu Verifikasi Irban',
            4 => 'Menunggu Verifikasi Sekre',
            5 => 'Mengatur Jadwal',
            6 => 'Selesai',
            7 => 'Ditolak Irban',
            8 => 'Ditolak Sekre',
        ];
    
        // Mengganti nilai status dalam koleksi $spt
        foreach ($spt as $item) {
            $item->status_spt = $statusNames[$item->status_spt] ?? '';
            $item->anggota_spt = $item->anggotaSPT;

            $pengawasIrban = $item->anggotaSpt->where('keterangan', 'Pengawas')->first();
            if ($pengawasIrban) {
                $namaPengawas = $pegawais->where('nip', $pengawasIrban->nip)->first();
                $item->nama_pengawas = $namaPengawas ? $namaPengawas->nama_pegawai : '';
            } else {
                $item->nama_pengawas = '';
            }

            $ketuaTim = $item->anggotaSpt->where('keterangan', 'Ketua Tim')->first();
            if ($ketuaTim) {
                $namaKetuaTim = $pegawais->where('nip', $ketuaTim->nip)->first();
                $item->nama_ketua_tim = $namaKetuaTim ? $namaKetuaTim->nama_pegawai : '';
            } else {
                $item->nama_ketua_tim = '';
            }

            $anggotaAnggota = $item->anggotaSpt->where('keterangan', 'Anggota');
            $namaAnggota = $anggotaAnggota->map(function ($anggota) use ($pegawais) {
                $namaPegawai = $pegawais->where('nip', $anggota->nip)->first();
                return $namaPegawai ? $namaPegawai->nama_pegawai : '';
            })->implode(', ');

            $item->nama_anggota = $namaAnggota;
        }

        return view('spt_pka.spt', compact('pegawais', 'spt'));
    }

    public function buat_spt_irban(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jenis_spt' => 'required',
            'dasar_spt' => 'required',
            'nama' => 'required',
            'untuk_spt' => 'required',
        ]);
        if ($validator->fails()) {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Input Data Gagal',
                'message' => 'Ada inputan yang salah!',
            ]);
        } else {
            // Keperluan dari ckeditor
            $inputDasarSpt = $request->dasar_spt;
            $inputDasarSpt = preg_replace('/<p>/', '<p class="p11" style="margin: 0;">', $inputDasarSpt);
            $inputDasarSpt = preg_replace_callback('/<li>(.*?)<\/li>/', function ($match) {
                return "<li style=\"padding-left: 5px;\"><p class=\"p11\" style=\"margin-bottom: 5px;\">{$match[1]}</p></li>";
            }, $inputDasarSpt);
            $inputDasarSpt = preg_replace('/<ol>/', '<ol style="margin: 0 0 0 -12px;">', $inputDasarSpt);
            $inputDasarSpt = preg_replace('/<ul>/', '<ul style="margin: 0 0 0 -12px;">', $inputDasarSpt);
            $buatSPT = SPT::create([
                'jenis_spt' => $request->jenis_spt,
                'dasar_spt' => $inputDasarSpt,
                'untuk_spt' => $request->untuk_spt,
            ]);
            $sptId = $buatSPT->getKey();
            AnggotaSPT::create([
                'id_spt' => $sptId,
                'nip' => Pegawai::where('nama_jabatan', 'Inspektur')->first()->nip,
                'keterangan' => 'Penanggungjawab',
            ]);
            AnggotaSPT::create([
                'id_spt' => $sptId,
                'nip' => Auth::user()->nip,
                'keterangan' => 'Pengawas',
            ]);
            AnggotaSPT::create([
                'id_spt' => $sptId,
                'nip' => Pegawai::where('nip', $request->nama)->first()->nip,
                'keterangan' => 'Ketua Tim',
            ]);
            Session::flash('alert', [
                'type' => 'success',
                'title' => 'Input Data Berhasil',
                'message' => "",
            ]);
        }
        return back();
    }

    public function get_data_spt_irban(Request $request)
    {
        $spt = SPT::where('id_spt', $request->id)->first();
        if($spt) {
            $anggotaSPT = AnggotaSPT::where('id_spt', $request->id)->get();
            return response()->json([
                'status' => 'success',
                'spt' => $spt,
                'anggotaSPT' => $anggotaSPT->map(function($anggota) {
                    $pegawai = Pegawai::where('nip', $anggota->nip)->first();
                    return [
                        'id_anggota' => $anggota->id_anggota,
                        'nip' => $anggota->nip,
                        'keterangan' => $anggota->keterangan,
                        'nama_pegawai' => $pegawai->nama_pegawai,
                    ];
                }),
            ]);
        } else {
            return response()->json([
                'status' => 'error',
            ]);
        }
    }

    public function ubah_spt_irban(Request $request)
    {
        if($request->ubah_anggota == null && $request->kurun_waktu_awal == null && $request->kurun_waktu_akhir == null) { // buat irban
            $validator = Validator::make($request->all(), [
                'id_spt'=>'required',
                'ubah_jenis_spt' => 'required',
                'ubah_dasar_spt' => 'required',
                'ubah_untuk_spt' => 'required',
                'ubah_ketua' => 'required',
                'ketua_sebelumnya' => 'required',
                
            ]);
            if ($validator->fails()) {
                Session::flash('alert', [
                    'type' => 'error',
                    'title' => 'Input Data Gagal',
                    'message' => 'Ada inputan yang salah!',
                ]);
            } else {
                $spt = SPT::where('id_spt', $request->id_spt)->first();
    
                if($spt){
                    $spt->update([
                        'jenis_spt' => $request->ubah_jenis_spt,
                        'dasar_spt' => $request->ubah_dasar_spt,
                        'untuk_spt' => $request->ubah_untuk_spt,
                        'obyek_audit' => $request->ubah_obyek,
                    ]);
                    $anggotaSPT = AnggotaSPT::where('id_spt', $spt->id_spt)->where('nip', $request->ketua_sebelumnya)->first();
                    $anggotaSPT->update([
                        'nip' => $request->ubah_ketua,
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
        } else { // buat ketua
            $validator = Validator::make($request->all(), [
                'id_spt'=>'required',
                'ubah_jenis_spt' => 'required',
                'ubah_dasar_spt' => 'required',
                'ubah_untuk_spt' => 'required',
                'ubah_obyek' => 'required',
                'ubah_anggota' => 'required',
                'kurun_waktu_awal' => 'required',
                'kurun_waktu_akhir' => 'required',
            ]);
            if ($validator->fails()) {
                Session::flash('alert', [
                    'type' => 'error',
                    'title' => 'Input Data Gagal',
                    'message' => 'Ada inputan yang salah!',
                ]);
            } else {
                $spt = SPT::where('id_spt', $request->id_spt)->first();
    
                if($spt){
                    if (strtotime($request->kurun_waktu_awal) == true && strtotime($request->kurun_waktu_akhir) == true) {
                        $spt->update([
                            'jenis_spt' => $request->ubah_jenis_spt,
                            'dasar_spt' => $request->ubah_dasar_spt,
                            'untuk_spt' => $request->ubah_untuk_spt,
                            'obyek_audit' => $request->ubah_obyek,
                            'kurun_waktu_awal' => date('Y-m-d', strtotime($request->kurun_waktu_awal)),
                            'kurun_waktu_akhir' => date('Y-m-d', strtotime($request->kurun_waktu_akhir)),
                        ]);
                    }                    
                    AnggotaSPT::where('id_spt', $spt->id_spt)->where('keterangan', 'Anggota')->delete();
                    foreach ($request->ubah_anggota as $nip) {
                        AnggotaSPT::create([
                            'id_spt' => $spt->id_spt,
                            'nip' => $nip,
                            'keterangan' => 'Anggota',
                        ]);
                    }
    
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
        }
        return back();
    }

    public function kirim_spt_irban(Request $request, $id_spt) {
        $spt = SPT::findOrFail($id_spt);
        if($spt) {
            switch($spt->status_spt) {
                case 1:
                    $spt->update([
                        'status_spt' => 2,
                    ]);
                    Session::flash('alert', [
                        'type' => 'success',
                        'title' => 'Kirim Data Berhasil',
                        'message' => "",
                    ]);
                    break;
                case 3:
                    switch($request->aksi) {
                        case 'setuju':
                            $spt->update([
                                'status_spt' => 4,
                            ]);
                            Session::flash('alert', [
                                'type' => 'success',
                                'title' => 'SPT Berhasil Disetujui',
                                'message' => "",
                            ]);
                            break;
                        case 'tolak':
                            $spt->update([
                                'status_spt' => 7,
                            ]);
                            Session::flash('alert', [
                                'type' => 'success',
                                'title' => 'SPT Berhasil Ditolak',
                                'message' => "",
                            ]);
                            break;
                    }
                    break;
            }
        } else {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Kirim Data Gagal',
                'message' => 'ID SPT tidak valid!',
            ]); 
        }
        return back();
    }

    public function get_data_spt_ketua(Request $request)
    {
        $spt = SPT::where('id_spt', $request->id)->first();
        if($spt) {
            return response()->json([
                'status' => 'success',
                'spt' => $spt,
            ]);
        } else {
            return response()->json([
                'status' => 'error',
            ]);
        }
    }

    public function lengkapi_spt_ketua(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_spt'=>'required',
            'ubah_jenis_spt' => 'required',
            'kurun_waktu_awal' => 'required|date',
            'kurun_waktu_akhir' => 'required|date',
            'ubah_dasar_spt' => 'required',
            'ubah_untuk_spt' => 'required',
            'ubah_obyek' => 'required',
        ]);
        if ($validator->fails()) {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Input Data Gagal',
                'message' => 'Ada inputan yang salah!',
            ]);
        } else {
            $spt = SPT::where('id_spt', $request->id_spt)->first();

            if($spt){
                $spt->update([
                    'jenis_spt' => $request->ubah_jenis_spt,
                    'dasar_spt' => $request->ubah_dasar_spt,
                    'untuk_spt' => $request->ubah_untuk_spt,
                    'obyek_audit' => $request->ubah_obyek,
                    'kurun_waktu_awal' => Carbon::createFromFormat('j F Y', $request->kurun_waktu_awal),
                    'kurun_waktu_akhir' => Carbon::createFromFormat('j F Y', $request->kurun_waktu_akhir),
                    
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

    public function kirim_spt_ketua($id_spt) {
        $spt = SPT::findOrFail($id_spt);
        if($spt) {
            $pka = PKA::where('id_spt', $id_spt)->get()->count();
            if($pka != null) {
                $spt->update([
                    'status_spt' => 3,
                ]);
                Session::flash('alert', [
                    'type' => 'success',
                    'title' => 'Kirim Data Berhasil',
                    'message' => "",
                ]);
            } else {
                Session::flash('alert', [
                    'type' => 'error',
                    'title' => 'Kirim Data Gagal',
                    'message' => 'PKA Belum Diisi!',
                ]); 
            }
        } else {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Kirim Data Gagal',
                'message' => 'ID SPT tidak valid!',
            ]); 
        }
        return back();
    }

    public function detail_spt($id_spt)
    {
        $spt = SPT::findOrFail($id_spt);
        $anggota_spt = AnggotaSPT::where('id_spt', $id_spt)->get();
        return view('spt_pka.detail_spt', compact('spt', 'anggota_spt'));
    }

    public function riwayatSPT(Request $request)
    {
        $spt = SPT::where('status_spt', 6)->get();
        $pegawais = Pegawai::all();
        foreach ($spt as $item) {
            $item->status_spt = $statusNames[$item->status_spt] ?? '';
            $item->anggota_spt = $item->anggotaSPT;

            $pengawasIrban = $item->anggotaSpt->where('keterangan', 'Pengawas')->first();
            if ($pengawasIrban) {
                $namaPengawas = $pegawais->where('nip', $pengawasIrban->nip)->first();
                $item->nama_pengawas = $namaPengawas ? $namaPengawas->nama_pegawai : '';
            } else {
                $item->nama_pengawas = '';
            }

            $ketuaTim = $item->anggotaSpt->where('keterangan', 'Ketua Tim')->first();
            if ($ketuaTim) {
                $namaKetuaTim = $pegawais->where('nip', $ketuaTim->nip)->first();
                $item->nama_ketua_tim = $namaKetuaTim ? $namaKetuaTim->nama_pegawai : '';
            } else {
                $item->nama_ketua_tim = '';
            }

            $anggotaAnggota = $item->anggotaSpt->where('keterangan', 'Anggota');
            $namaAnggota = $anggotaAnggota->map(function ($anggota) use ($pegawais) {
                $namaPegawai = $pegawais->where('nip', $anggota->nip)->first();
                return $namaPegawai ? $namaPegawai->nama_pegawai : '';
            })->implode(', ');

            $item->nama_anggota = $namaAnggota;
        }
        return view('spt_pka.riwayat_spt', compact('pegawais', 'spt'));
    }

    public function verifikasi_spt_sekre(Request $request, $id_spt) {
        $spt = SPT::findOrFail($id_spt);
        if($spt) {
            switch($spt->status_spt) {
                case 4:
                    switch($request->aksi) {
                        case 'setuju':
                            $spt->update([
                                'status_spt' => 5,
                            ]);
                            Session::flash('alert', [
                                'type' => 'success',
                                'title' => 'SPT Berhasil Disetujui',
                                'message' => "",
                            ]);
                            break;
                        case 'tolak':
                            $spt->update([
                                'status_spt' => 8,
                            ]);
                            Session::flash('alert', [
                                'type' => 'success',
                                'title' => 'SPT Berhasil Ditolak',
                                'message' => "",
                            ]);
                            break;
                    }
                    break;
            }
        } else {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Kirim Data Gagal',
                'message' => 'Terjadi Error!'
            ]);
        }
        return back();
    }

    public function verifikasi_spt_selesai($id_spt) {
        $spt = SPT::findOrFail($id_spt);
        if($spt) {
            $spt->update([
                'status_spt' => 6,
            ]);

            Session::flash('alert', [
                'type' => 'success',
                'title' => 'Kirim Data Berhasil',
                'message' => ''
            ]);
        } else {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Kirim Data Gagal',
                'message' => 'Terjadi Error!'
            ]);
        }
        return back();
    }

    public function simpan_tanggal(Request $request)
    {
        $anggota = AnggotaSPT::get();

        foreach($anggota  as  $ang){
            if(!empty($request['tanggal_awal'.$ang->id_anggota])){
                $awal = Carbon::createFromFormat('j F Y', $request['tanggal_awal'.$ang->id_anggota]);
                $akhir = Carbon::createFromFormat('j F Y', $request['tanggal_akhir'.$ang->id_anggota]);
                $data = AnggotaSPT::where('id_anggota', $request['id_anggota'.$ang->id_anggota])->first();
                $data->tanggal_awal = $awal;
                $data->tanggal_akhir = $akhir;
                $data->update();
            }
        }
        return back();
    }

    public function cetak($spt_id)
    {
        $spt = SPT::with('anggotaSPT')->where('id_spt', $spt_id)->first();
        $jangkaWaktu = Carbon::parse($spt->kurun_waktu_akhir)->diffInDays(Carbon::parse($spt->kurun_waktu_awal));
        $ketJangkaWaktu = $this->numberToWords($jangkaWaktu);

        $kurun_awal = Carbon::parse($spt->kurun_waktu_awal);
        $kurun_akhir = Carbon::parse($spt->kurun_waktu_akhir);


        $sameYear = $kurun_awal->isSameYear($kurun_akhir);

        // Format date range
        if ($sameYear) {
            $awal = $kurun_awal->format('j');
            $akhir = $kurun_akhir->format('j');
            
            // If the day is less than 10, remove leading zero
            if ($awal < 10) {
                $awal = $kurun_awal->format('j');
            }
            
            if ($akhir < 10) {
                $akhir = $kurun_akhir->format('j');
            }

            $kurun_waktu = $awal . ' ' . $this->angkaBulanKeNama($kurun_awal->month) . ' s.d ' . $akhir . ' ' . $this->angkaBulanKeNama($kurun_akhir->month) . ' ' . $kurun_awal->format('Y');
        } else {
            $kurun_waktu = $kurun_awal->format('j F Y') . ' s.d ' . $kurun_akhir->format('j F Y');
        }

        // return view('template_spt', compact('spt', 'jangkaWaktu', 'ketJangkaWaktu', 'kurun_awal', 'kurun_akhir', 'kurun_waktu'));

        //download pdf
        $template = '
            <!DOCTYPE html>
            <html>
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                <style>
                    @page { size: A4 portrait; }
                    html { margin: 1cm 1.5cm; }
                    body { max-width: 21cm; font-family: Arial, Helvetica, sans-serif; }
                    .page-break {
                        page-break-after: always;
                    }
                    .p10 {
                        margin: 0;
                        font-size: 10pt;
                    }
                    .p11 {
                        margin: 0;
                        font-size: 11pt;
                    }
                    #kop {
                        width: 100%;
                        border-bottom: 3pt double black;
                    }
                    #kop td {
                        text-align: center;
                        vertical-align: middle;
                    }
                    #isi th, #isi td {
                        text-align: left;
                        vertical-align: top;
                    }
                    #kepada {
                        width: 14.5cm;
                        border: 2px solid black;
                        border-collapse: collapse;
                    }
                    #kepada th, #kepada td {
                        border: 1px solid black;
                    }
                    #kepada th, #kepada td {
                        text-align: center;
                        vertical-align: middle;
                    }
                </style>
            </head>
            <body>
                <table id="kop">
                    <tr>
                        <td style="width: 13.5%;">
                            <img src="'.public_path("logo.png").'" style="width: 100%;">
                            <p style="margin: 0; font-size: 3pt;">&nbsp;</p>
                        </td>
                        <td style="width: 86.5%;">
                            <p style="margin: 0; font-size: 12.7pt; font-weight: bold;">PEMERINTAH KABUPATEN MAGETAN</p>
                            <p style="margin: 0; font-size: 16pt; font-weight: bold;">I N S P E K T O R A T</p>
                            <p class="p10">Jl. Tripandita No. 17 Magetan Kode Pos 63319</p>
                            <p class="p10">Telp. (0351) 897113 Fax. (0351) 897161</p>
                            <p class="p10">E-mail : inspektorat@magetan.go.id Website : http://inspektorat.magetan.go.id</p>
                        </td>
                    </tr>
                </table>
                <div style="width: 100%;">
                    <p style="margin-bottom: 0; text-decoration: underline; text-align: center; font-weight: bold; font-size: 16pt;">SURAT PERINTAH TUGAS&nbsp;</p>
                    <p style="margin-top: 0; text-align: center; font-weight: bold; font-size: 11pt;">Nomor : 094/&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; /403.060/2023</p>
                </div>
                <table id="isi" style="width: 100%;">
                    <tr>
                        <th><p class="p11">DASAR</p></th>
                        <td style="padding-left: 35px;"><p class="p11">:</p></td>
                        <td style="padding-left: 20px;" id="content">';
        $template .= preg_replace_callback('/<(p|ul|ol)(.*?)>/', function ($matches) {
            $tag = $matches[1];
            $attributes = $matches[2];
            if ($tag === 'p') {
                return "<$tag$attributes style=\"margin: 0;\" class=\"p11\">";
            } elseif ($tag === 'ul' || $tag === 'ol') {
                return "<$tag$attributes style=\"margin: 0; padding: 0 0 0 20px;\">";
            } else {
                return "<$tag$attributes>";
            }
        }, $spt->dasar_spt);
        $template .= '
                        </td>
                    </tr>
                    <tr>
                        <th colspan="3"><p class="p11" style="text-align: center; margin: 10pt 0;">M E M E R I N T A H K A N</p></th>
                    </tr>
                    <tr>
                        <th><p class="p11">KEPADA</p></th>
                        <td style="padding-left: 35px;"><p class="p11">:</p></td>
                        <td style="padding-left: 20px; padding-bottom: 15px;">
                            <table id="kepada">
                                <thead>
                                    <tr>
                                        <th class="p11" style="width: 0.5cm;">No.</th>
                                        <th class="p11" style="width: 8cm;">NAMA</th>
                                        <th class="p11" style="width: 4cm;">KETERANGAN</th>
                                        <th class="p11" style="width: 2cm;">JANGKA WAKTU</th>
                                    </tr>
                                </thead>
                                <tbody>';
        $no = 1;
        foreach ($spt->anggotaSPT as $anggota) {
            $template .= '
                                    <tr>
                                        <td class="p11">'.$no++.'</td>
                                        <td class="p11" style="text-align: left;">Sdr. &nbsp; &nbsp; '.strtoupper($anggota->relasi_pegawai->nama_pegawai).'</td>
                                        <td class="p11">'.$anggota->keterangan.'</td>
                                        <td class="p11">'.$jangkaWaktu.' &nbsp; &nbsp; hari</td>
                                    </tr>';
        }
        $template .= '
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <th><p class="p11">UNTUK</p></th>
                        <td style="padding-left: 35px;"><p class="p11">:</p></td>
                        <td style="padding-left: 20px; padding-bottom: 10px; text-align: justify;"><p class="p11">'.html_entity_decode($spt->untuk_spt).'</p></td>
                    </tr>
                    <tr>
                        <td><p class="p11"></p></td>
                        <td colspan="2" style="text-align: justify; padding-left: 3px;">
                            <p class="p11" style="text-indent: 3.7em; margin-bottom: 5pt;">Kegiatan tersebut dilaksanakan selama '.$jangkaWaktu.' ('.$ketJangkaWaktu.') hari kerja dalam kurun waktu '.$kurun_waktu.' dan biaya yang berkaitan dengan penugasan menjadi beban Anggaran Inspektorat Kabupaten Magetan.</p>
                            <p class="p11" style="text-indent: 3.7em;">Kepada pihak-pihak yang bersangkutan diminta kesediannya untuk memberikan keterangan yang diperlukan guna kelancaran dan penyelesaian tugas dimaksud.</p>
                            <p class="p11" style="text-indent: 3.7em;">Sebagai informasi, disampaikan bahwa Inspektorat Kabupaten Magetan tidak memungut biaya apapun atas pelayanan yang diberikan, dan untuk menjaga integritas dimohon untuk tidak menyampaikan pemberian dalam bentuk apapun kepada Pejabat/Pegawai Inspektorat Kabupaten Magetan.</p>
                        </td>
                    </tr>
                </table>
                <p></p>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td rowspan="6" style="width: 50%;"></td>
                        <td class="p11">Dikeluarkan di</td>
                        <td class="p11" style="text-align: center;">:</td>
                        <td class="p11" style="text-align: right;">M A G E T A N</td>
                    </tr>
                    <tr>
                        <td class="p11" style="border-bottom: 2px solid black;">Pada Tanggal</td>
                        <td class="p11" style="text-align: center; border-bottom: 2px solid black;">:</td>
                        <td class="p11" style="text-align: right; border-bottom: 2px solid black;"> '.date("F Y").' </td>
                    </tr>
                    <tr>
                        <td class="p11" style="text-align: center; font-weight: bold;" colspan="3">INSPEKTUR KABUPATEN MAGETAN</td>
                    </tr>
                    <tr>
                        <td class="p11" style="text-align: center; font-weight: bold; text-decoration: underline; padding-top: 100px;" colspan="3">Nama Inspektur</td>
                    </tr>
                    <tr>
                        <td class="p11" style="text-align: center" colspan="3">Nama Pangkat</td>    
                    </tr>
                    <tr>
                        <td class="p11" style="text-align: center" colspan="3">NIP. 00000000 000000 0 000</td>    
                    </tr>
                </table>
            </body>
            </html>';
        $pdf = PDF::loadHTML($template)->setPaper('A4', 'portrait');
        $pdf->output();
        return $pdf->stream('Surat Perintah Tugas '.date("Y-m-d H-i-s").'.pdf', array('Attachment' => false));
    }

    function numberToWords($number)
    {
        $words = [
            0 => 'Nol',
            1 => 'Satu',
            2 => 'Dua',
            3 => 'Tiga',
            4 => 'Empat',
            5 => 'Lima',
            6 => 'Enam',
            7 => 'Tujuh',
            8 => 'Delapan',
            9 => 'Sembilan',
            10 => 'Sepuluh',
            11 => 'Sebelas'
        ];

        if ($number < 10) {
            return $words[$number];
        } elseif ($number < 20) {
            return $words[$number - 10] . ' belas' ;
        } elseif ($number < 100) {
            return $words[($number - $number % 10) / 10] . ' puluh ' . ($number % 10 !== 0 ? $words[$number % 10] : '');
        } elseif ($number < 1000) {
            return $words[($number - $number % 100) / 100] . ' ratus ' . ($number % 100 !== 0 ? $this->numberToWords($number % 100) : '');
        }

        return 'undefined';
    }


    function angkaBulanKeNama($bulan)
    {
        $namaBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return $namaBulan[$bulan] ?? 'Bulan tidak valid';
    }
}
