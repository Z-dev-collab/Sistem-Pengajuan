<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $service->form_title ?: $service->name }}</title>
    <style>
        @page { margin: 38px 44px 46px; }
        * { box-sizing: border-box; }
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 10.5px;
            color: #17251f;
            line-height: 1.45;
        }
        .kop {
            text-align: center;
            border-bottom: 3px double #174d3a;
            padding-bottom: 10px;
            margin-bottom: 18px;
        }
        .kop-provinsi { font-size: 11px; letter-spacing: .8px; text-transform: uppercase; }
        .kop-kota { font-size: 15px; font-weight: bold; letter-spacing: .5px; margin-top: 2px; text-transform: uppercase; }
        .kop-kecamatan { font-size: 11px; margin-top: 1px; text-transform: uppercase; }
        .kop-kelurahan { font-size: 17px; font-weight: bold; margin-top: 3px; text-transform: uppercase; letter-spacing: .6px; }
        .kop-alamat { font-size: 8.5px; color: #4d5f56; margin-top: 3px; }
        .title-wrap { text-align: center; margin: 17px 0 5px; }
        .title {
            font-size: 14px;
            font-weight: bold;
            letter-spacing: .8px;
            text-transform: uppercase;
            text-decoration: underline;
            display: inline-block;
            padding-bottom: 3px;
        }
        .subtitle { font-size: 9.5px; color: #57645e; margin-top: 3px; }
        .form-number {
            width: 100%;
            margin: 15px 0 18px;
            font-size: 9.5px;
        }
        .form-number td { padding: 0; }
        .form-number td:last-child { text-align: right; }
        .line { display: inline-block; border-bottom: 1px solid #536159; min-width: 125px; }
        .intro { margin-bottom: 13px; font-size: 9.8px; }
        .field-table { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
        .field-table td { vertical-align: top; padding: 5px 0; }
        .field-label { width: 30%; font-size: 9.6px; color: #34453c; }
        .field-colon { width: 3%; }
        .field-line {
            border-bottom: 1px dotted #6e7a73;
            height: 20px;
            padding: 0 4px;
            font-size: 9px;
            color: #8a948e;
        }
        .field-line.tall { height: 42px; }
        .section-title {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #174d3a;
            border-bottom: 1px solid #cdd9d2;
            padding-bottom: 4px;
            margin: 18px 0 8px;
        }
        .requirements { margin: 5px 0 0; padding-left: 17px; font-size: 9.3px; }
        .requirements li { margin-bottom: 3.5px; }
        .statement {
            margin-top: 16px;
            border: 1px solid #d6dfda;
            background: #f6f8f6;
            padding: 10px 12px;
            font-size: 9.2px;
            line-height: 1.55;
        }
        .signature-table { width: 100%; margin-top: 22px; border-collapse: collapse; }
        .signature-table td { width: 50%; vertical-align: top; text-align: center; font-size: 9.5px; }
        .signature-space { height: 58px; }
        .signature-name { display: inline-block; border-top: 1px solid #7c847f; padding: 3px 20px 0; font-weight: bold; }
        .signature-note { color: #7a857f; font-size: 8.5px; }
        .footer {
            position: fixed;
            bottom: -26px;
            left: 0;
            right: 0;
            border-top: 1px solid #dce5e0;
            padding-top: 6px;
            font-size: 7.8px;
            color: #7d8882;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="kop">
        <div class="kop-provinsi">Pemerintah {{ $site['provinsi'] }}</div>
        <div class="kop-kota">Pemerintah {{ $site['kota'] }}</div>
        <div class="kop-kecamatan">{{ $site['kecamatan'] }}</div>
        <div class="kop-kelurahan">{{ $site['name'] }}</div>
        <div class="kop-alamat">{{ $site['address'] }} · Telp. {{ $site['phone'] }}</div>
    </div>

    <div class="title-wrap">
        <span class="title">{{ $service->form_title ?: 'Formulir '.$service->name }}</span>
        <div class="subtitle">Nomor formulir: ......................... / ......................... / {{ now()->format('Y') }}</div>
    </div>

    <table class="form-number">
        <tr>
            <td>Kepada Yth. Lurah {{ $site['name'] }}</td>
            <td>Tanggal: .......... / .......... / ..........</td>
        </tr>
    </table>

    <p class="intro">Dengan hormat, saya yang bertanda tangan di bawah ini mengajukan permohonan <strong>{{ $service->name }}</strong>. Data yang saya isi adalah benar dan dapat dipertanggungjawabkan.</p>

    <div class="section-title">Data Pemohon</div>
    <table class="field-table">
        @php $fields = $service->form_fields ?: []; @endphp
        @foreach(array_chunk($fields, 2) as $row)
            @if(count($row) === 1 || (($row[0]['width'] ?? 'half') === 'full'))
                @php $field = $row[0]; @endphp
                <tr>
                    <td class="field-label">{{ $field['label'] }}</td>
                    <td class="field-colon">:</td>
                    <td><div class="field-line {{ ($field['type'] ?? 'text') === 'textarea' ? 'tall' : '' }}"></div></td>
                </tr>
                @if(($row[0]['width'] ?? 'half') === 'full' && count($row) === 2)
                    @php $second = $row[1]; @endphp
                    <tr>
                        <td class="field-label">{{ $second['label'] }}</td>
                        <td class="field-colon">:</td>
                        <td><div class="field-line {{ ($second['type'] ?? 'text') === 'textarea' ? 'tall' : '' }}"></div></td>
                    </tr>
                @endif
            @else
                @foreach($row as $field)
                    @if(($field['width'] ?? 'half') === 'full')
                        <tr>
                            <td colspan="3" class="field-label" style="padding-top:8px;">{{ $field['label'] }}</td>
                        </tr>
                        <tr>
                            <td colspan="3"><div class="field-line tall"></div></td>
                        </tr>
                    @else
                        <tr>
                            <td class="field-label">{{ $field['label'] }}</td>
                            <td class="field-colon">:</td>
                            <td><div class="field-line"></div></td>
                        </tr>
                    @endif
                @endforeach
            @endif
        @endforeach
    </table>

    <div class="section-title">Berkas yang Dilampirkan</div>
    <ol class="requirements">
        @foreach($service->requirements as $requirement)
            <li>{{ data_get($requirement, 'name') }}@if(data_get($requirement, 'note')) — {{ data_get($requirement, 'note') }}@endif @unless(data_get($requirement, 'is_mandatory'))<em>(jika diperlukan)</em>@endunless</li>
        @endforeach
    </ol>

    <div class="statement">
        Saya menyatakan bahwa data dan dokumen yang saya lampirkan adalah benar, lengkap, dan sah. Apabila di kemudian hari terdapat kekeliruan, saya bersedia bertanggung jawab sesuai ketentuan yang berlaku.
    </div>

    <table class="signature-table">
        <tr>
            <td>
                <div class="signature-note">Mengetahui,</div>
                <div class="signature-note">Ketua RT / RW</div>
                <div class="signature-space"></div>
                <div class="signature-name">(...........................................)</div>
            </td>
            <td>
                <div class="signature-note">{{ $site['name'] }}, ................................... {{ now()->format('Y') }}</div>
                <div class="signature-note">Pemohon</div>
                <div class="signature-space"></div>
                <div class="signature-name">(...........................................)</div>
            </td>
        </tr>
    </table>

    <div class="footer">
        Formulir ini dapat diunduh melalui situs resmi {{ $site['name'] }}. Serahkan formulir yang telah diisi beserta berkas persyaratan kepada petugas kelurahan.
    </div>
</body>
</html>
