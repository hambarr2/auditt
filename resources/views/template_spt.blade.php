<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('logo.png') }}" type="image/x-icon">
    <title>Dokumen SPT</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        body {
            max-width: 21cm; /* A4 */
            margin: 0;
            padding: 1cm 1cm 1.5cm 1.5cm;
            font-family: Arial, Helvetica, sans-serif;
        }

        #kerangka {
            width: 18.5cm;
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
    <div id="kerangka">
        <table id="kop">
            <tr>
                <td style="width: 2.5cm;">
                    <img src="{{ asset('logo.png') }}" style="width: 100%;">
                    <p style="margin: 0; font-size: 3pt;">&nbsp;</p>
                </td>
                <td style="width: 16cm;">
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
            <p style="margin-top: 0; text-align: center; font-weight: bold; font-size: 11pt;">Nomor : 094/&nbsp; &nbsp; &nbsp; &nbsp; /403.060/2023</p>
        </div>
        <table id="isi" style="width: 100%;">
            <tr>
                <th><p class="p11">DASAR</p></th>
                <td style="padding-left: 35px;"><p class="p11">:</p></td>
                <td style="padding-left: 20px;" id="content">
                    {!! preg_replace_callback('/<(p|ul|ol)(.*?)>/', function($matches) {
                        $tag = $matches[1];
                        $attributes = $matches[2];
                        if ($tag === 'p') {
                            return "<$tag$attributes style=\"margin: 0;\" class=\"p11\">";
                        } elseif ($tag === 'ul' || $tag === 'ol') {
                            return "<$tag$attributes style=\"margin: 0; padding: 0 0 0 20px;\">";
                        } else {
                            return "<$tag$attributes>";
                        }
                    }, $spt->dasar_spt) !!}
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
                        <tbody>
                            @foreach ($spt->anggotaSPT as $anggota)
                                <tr>
                                    <td class="p11">{{ $loop->iteration }}</td>
                                    <td class="p11" style="text-align: left;">Sdr. &nbsp; &nbsp; {{ strtoupper($anggota->relasi_pegawai->nama_pegawai)}}</td>
                                    <td class="p11">{{ $anggota->keterangan }}</td>
                                    <td class="p11">{{ $jangkaWaktu }} &nbsp; &nbsp; hari</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <th><p class="p11">UNTUK</p></th>
                <td style="padding-left: 35px;"><p class="p11">:</p></td>
                <td style="padding-left: 20px; padding-bottom: 10px; text-align: justify;"><p class="p11">{!! $spt->untuk_spt !!}</p></td>
            </tr>
            <tr>
                <td><p class="p11"></p></td>
                <td colspan="2" style="text-align: justify; padding-left: 3px;">
                    <p class="p11" style="text-indent: 3.7em; margin-bottom: 5pt;">Kegiatan tersebut dilaksanakan selama {{ $jangkaWaktu }} ({{ $ketJangkaWaktu }}) hari kerja dalam kurun waktu {{ $kurun_waktu }} dan biaya yang berkaitan dengan penugasan menjadi beban Anggaran Inspektorat Kabupaten Magetan.</p>
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
                <td class="p11" style="text-align: right; border-bottom: 2px solid black;">Januari 2023</td>
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
    </div>
</body>
</html>