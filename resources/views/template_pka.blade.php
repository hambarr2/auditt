<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('logo.png') }}" type="image/x-icon">
    <title>Dokumen PKA</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }
        body {
            width: 29.7cm; /* A4 */
            height: 21cm;
            margin: 0;
            padding: 2.5cm 1cm 1.5cm 2.5cm;
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
            font-size: 12pt;
            padding: 10px;
            margin-bottom: 10px;
        }
        
        #isi th, #isi td {
            text-align: left;
            vertical-align: top;
            padding-left: 20px;
        }

        #isian {
            width: 32cm;
            border: 2px solid black;
            border-collapse: collapse;
        }
        #isian th, #isian td {
            border: 1px solid black;
        }

        #isian th {
            text-align: center;
            vertical-align: middle;
        }

        #isian tbody td {
            text-align: center;
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div id="kerangka">
        <div style="width: 100%;">
            <p style="margin-bottom: 2; text-decoration: underline; text-align: left; font-weight: bold; font-size: 12pt; padding-left: 20px;">INSPEKTORAT KABUPATEN MAGETAN&nbsp;</p>
        </div>
        <table id="isi" style="width: 100%;">
            <tr>
                <th style="font-size: 12pt;">DOKUMEN</th>
                <td style="padding-left: 20px; font-size: 12pt;">: PROGRAM KERJA AUDIT</td>
                <td style="padding-left: 20px;" id="content">
                </td>
            </tr>
            <tr>
                <th style="font-size: 12pt;">OBYEK AUDIT</th>
                <td style="padding-left: 20px; font-size: 12pt;">:</td>
                <td style="padding-left: 20px;" id="content">
                </td>
            </tr>
            <tr>
                <td style="padding-left: 35px;"></td>
                <td style="padding-left: 20px; padding-top: 15px;">
                    <table id="isian">
                        <thead>
                            <tr>
                                <th class="p11" style="width: 5cm;">Tujuan/Sasaran</th>
                                <th class="p11" style="width: 8cm;">Langkah-langkah kerja</th>
                                <th class="p11" style="width: 3cm;">Dilaksanakan oleh</th>
                                <th class="p11" style="width: 3cm;">Waktu yang diperlukan</th>
                                <th class="p11" style="width: 3cm;">Nomor KKA (Kertas Kerja Audit)</th>
                                <th class="p11" style="width: 8cm;">Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pka as $pk )
                                <tr>
                                    <td class="p11" style="text-align: left">{{ $pk->tujuan }}</td>
                                    <td class="p11" style="text-align: left;">
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
                                        }, $pk->langkah_kerja) !!}
                                    </td>
                                    <td class="p11">{{ $pk->pelaksana }}</td>
                                    <td class="p11">{{ $pk->waktu }}</td>
                                    <td class="p11">{{ $pk->no_kka }}</td>
                                    <td class="p11" style="text-align: left">{{ $pk->catatan }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>