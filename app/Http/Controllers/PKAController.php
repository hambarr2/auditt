<?php

namespace App\Http\Controllers;

use App\Models\PKA;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class PKAController extends Controller
{
    public function index($id_spt)
    {
        $pka = PKA::where('id_spt', $id_spt)->get();
        $idSPT = $id_spt;
        return view('spt_pka.pka', compact('pka', 'idSPT'));
    }

    public function detail_pka_irban($id_spt)
    {
        $pka = PKA::where('id_spt', $id_spt)->get();
        $idSpt = $id_spt;
        return view('spt_pka.detail_pka', compact('pka', 'idSpt'));
    }

    public function buat_pka_ketua(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_spt' => 'required',
            'tujuan' => 'required',
            'langkah' => 'required',
            'dilaksanakan' => 'required',
            'num-teams' => 'required',
            'nomor' => 'required',
            'catatan' => 'required',
        ]);
        
        if ($validator->fails()) {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Input Data Gagal',
                'message' => 'Ada inputan yang salah!',
            ]);
        } else {
            $inputLangkah = $request->langkah;
            $inputLangkah = preg_replace_callback('/<li>(.*?)<\/li>/', function ($match) {
                return "<li style=\"padding-left: 5px;\">{$match[1]}</p></li>";
            }, $inputLangkah);
            $inputLangkah = preg_replace('/<ol>/', '<ol style="margin: 0 0 0 -12px;">', $inputLangkah);
            $inputLangkah = preg_replace('/<ul>/', '<ul style="margin: 0 0 0 -12px;">', $inputLangkah);
            PKA::create([
                'id_spt' => $request->id_spt,
                'tujuan' => $request->tujuan,
                'langkah_kerja' => $inputLangkah,
                'pelaksana' => $request->dilaksanakan,
                'waktu' => $request['num-teams'],
                'no_kka' => $request->nomor,
                'catatan' => $request->catatan,
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

    public function get_data_pka(Request $request)
    {
        $pka = PKA::where('id_pka', $request->id)->first();
        if($pka)
        {
            return response()->json([
                'status'=>'success',
                'pka'=> $pka,
            ]);
        }
        else
        {
            return response()->json([
                'status'=>'error',
            ]);
        }

    }

    public function ubah_data_pka(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'id_pka'=>'required',
            'ubah_tujuan' => 'required',
            'ubah_langkah' => 'required',
            'ubah_pelaksana' => 'required',
            'ubah_waktu' => 'required',
            'ubah_nomor' => 'required',
            'ubah_catatan' => 'required',
        ]);
        if ($validator->fails()) {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Input Data Gagal',
                'message' => 'Ada inputan yang salah!',
            ]);
        } else {
            $pka = PKA::where('id_pka', $request->id_pka)->first();

            if($pka){
                $pka->update([
                    'tujuan' => $request->ubah_tujuan,
                    'langkah_kerja' => $request->ubah_langkah,
                    'pelaksana' => $request->ubah_pelaksana,
                    'waktu' => $request->ubah_waktu,
                    'no_kka' => $request->ubah_nomor,
                    'catatan' => $request->ubah_catatan,
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

    public function ubah_isian_pka($id) 
    {
        $pka = PKA::find($id);
        return response()->json(['pka'=>$pka]);
    }

    public function destroy($id_pka) {
        $pka = PKA::findOrFail($id_pka);
        if($pka) {
            Session::flash('alert', [
                'type' => 'success',
                'title' => 'Hapus Data Berhasil',
                'message' => "",
            ]); 
            $pka->delete();
        } else {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Hapus Data Gagal',
                'message' => "",
            ]); 
        }
        return back();
    }

    public function cetak2($id_spt)
    {
        $pka = PKA::with('relasi_id_spt')->where('id_spt', $id_spt)->get();

        $template2 = '
            <!DOCTYPE html>
            <html>
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                <style>
                    @page { size: A4 landscape; )
                    html { margin: 1cm 1.5cm; }
                    body { max-width: 29.7cm; font-family: Arial, Helvetica, sans-serif; }
                    }
                    
                    body {
                        width: 29.7cm; /* A4 */
                        height: 21cm;
                        // margin: 0;
                        // padding: 2.5cm 1cm 1.5cm 2.5cm;
                        font-family: Arial, Helvetica, sans-serif;
                    }
            
                    #kerangka {
                        width: 40cm;
                    }
            
                    .p10 {
                        margin: 0;
                        font-size: 10pt;
                    }
            
                    .p11 {
                        margin: 0;
                        font-size: 10pt;
                        padding: 10px;
                        margin-bottom: 10px;
                    }

                    .p12 {
                        text-align: left !important; 
                    }
                    
                    #isi th, #isi td {
                        text-align: left;
                        vertical-align: top;
                        margin-left: 0;
                        padding-left : 15px;
                    }
            
                    #isian {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    #isian th, #isian td {
                        border: 1px solid black;
                        padding: 4px;
                        margin-left : 15px;
                    }
            
                    #isian th {
                        text-align: center;
                        vertical-align: middle;
                    }
            
                    #isian tbody td {
                        text-align: center;
                        vertical-align: middle;
                    }

                    .obyek {
                        padding-left: 0px !important;
                    }
                </style>
            </head>
            <body>
                <div id="kerangka">
                    <div style="width: 100%; margin-bottom: 10px;">
                        <p style="margin-bottom: 2; text-decoration: underline; text-align: left; font-weight: bold; font-size: 12pt; padding-left: 20px;">INSPEKTORAT KABUPATEN MAGETAN&nbsp;</p>
                    </div>
                    <table id="isi" style="width: 30%;">
                        <tr>
                            <th style="font-size: 12pt; text-align: center;">DOKUMEN</th>
                            <td style="padding-left: 20px; font-size: 12pt;">: Program Kerja Audit</td>
                            <td style="padding-left: 20px;" id="content">
                            </td>
                        </tr>
                        <tr>
                            <th style="font-size: 12pt; text-align: center;">OBYEK AUDIT</th>
                            <td style="padding-left: 20px; font-size: 12pt;">: '.$pka[0]->relasi_id_spt->obyek_audit.'</td>
                        </tr>
                    </table><br>
                    <table id="isian" style="width:50%; margin-left: 20px">
                        <thead>
                            <tr>
                                <th class="p11" style="width: 0.5cm;">No.</th>
                                <th class="p11" style="width: 5cm;">Tujuan/Sasaran</th>
                                <th class="p11" style="width: 6cm;">Langkah-langkah kerja</th>
                                <th class="p11" style="width: 2cm;">Dilaksanakan oleh</th>
                                <th class="p11" style="width: 2cm;">Waktu yang diperlukan</th>
                                <th class="p11" style="width: 2cm;">Nomor KKA (Kertas Kerja Audit)</th>
                                <th class="p11" style="width: 6cm;">Catatan</th>
                            </tr>
                        </thead>
                        <tbody>';
                        $no = 1;
                        foreach ($pka as $data) {
                            $template2 .= '
                            <tr>
                                <td class="p11">'.$no++.'</td>
                                <td class="p12">'.$data->tujuan.'</td>      
                                <td class="p12">';
                                $template2 .= preg_replace_callback('/<(p|ul|ol)(.*?)>/', function ($matches) {
                                    $tag = $matches[1];
                                    $attributes = $matches[2];
                                    if ($tag === 'p') {
                                        return "<$tag$attributes style=\"margin: 0; font-size: inherit;\" class=\"p11\">";
                                    } elseif ($tag === 'ul' || $tag === 'ol') {
                                        return "<$tag$attributes style=\"margin: 0; padding: 0 0 0 20px; font-size: inherit;\">";
                                    } else {
                                        return "<$tag$attributes>";
                                    }
                                }, $data->langkah_kerja);
                                $template2 .= '</td>
                                <td style="font-size: inherit;">'.$data->pelaksana.'</td>
                                <td style="font-size: inherit;">'.$data->waktu.'</td>       
                                <td style="font-size: inherit;">'.$data->no_kka.'</td> 
                                <td class="p12" style="font-size: inherit;">'.$data->catatan.'</td>                     
                            </tr> ';
                        }                        
                        $template2 .= '
                        </tbody>
                    </table>
                </td>
            </tr>
        </div>
    </body>
</html>';
        $pdf = PDF::loadHTML($template2)->setPaper('A4', 'landscape');
        $pdf->output();
        return $pdf->stream('Program Kerja Audit '.date("Y-m-d H-i-s").'.pdf', array('Attachment' => false));
    }

}
