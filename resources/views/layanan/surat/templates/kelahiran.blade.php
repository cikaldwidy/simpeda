@php
$logoSrc = !empty($isPdf) && empty($isPreview)
? public_path('img/logo_TA.png')
: asset('img/logo_TA.png');
$regDesNomor = $surat->nomor_surat ?: 'Belum diisi';
$data = json_decode((string) $surat->catatan, true) ?: [];
$alamatLines = $alamatDomisiliLines ?? [];
if (empty($alamatLines) && !empty($alamatRingkas)) {
$alamatLines = [$alamatRingkas];
}
$alamatLine1 = '';
$alamatLine2 = '';
if (!empty($alamatLines)) {
$alamatLine1 = trim(implode(' ', array_filter([
$alamatLines[0] ?? null,
$alamatLines[1] ?? null,
])));
$alamatLine2 = trim(implode(' ', array_filter([
$alamatLines[2] ?? null,
])));
}
$abbrMap = [
'Dusun ' => 'Dsn. ',
'Desa ' => 'Ds. ',
'Kecamatan ' => 'Kec. ',
'Kabupaten ' => 'Kab. ',
];
$alamatLine1 = str_replace(array_keys($abbrMap), array_values($abbrMap), $alamatLine1);
$alamatLine2 = str_replace(array_keys($abbrMap), array_values($abbrMap), $alamatLine2);
$tanggalBayi = !empty($data['tanggal_lahir_bayi'])
? \Illuminate\Support\Carbon::parse($data['tanggal_lahir_bayi'])->format('d - m - Y')
: null;
$tanggalAyah = !empty($data['tanggal_lahir_ayah'])
? \Illuminate\Support\Carbon::parse($data['tanggal_lahir_ayah'])->format('d - m - Y')
: null;
$tanggalIbu = !empty($data['tanggal_lahir_ibu'])
? \Illuminate\Support\Carbon::parse($data['tanggal_lahir_ibu'])->format('d - m - Y')
: null;
@endphp

<style>
.doc-wrap {
    width: 100%;
    font-family: "Times New Roman", Times, serif;
    color: #111827;
    font-size: 14px;
    line-height: 1.5;
}

.doc-head {
    width: 100%;
    border-bottom: 3px double #1f2937;
    padding-bottom: 8px;
    margin: 0 auto;
    position: relative;
    min-height: 84px;
}

.logo-cell {
    position: absolute;
    left: 0;
    top: 0;
    width: 110px;
}

.logo-cell img {
    width: 110px;
    height: 80px;
    object-fit: contain;
    margin-top: 10px;
}

.head-text {
    text-align: center;
}

.title-1,
.title-2,
.title-3 {
    margin: 0;
    text-align: center;
    font-size: 18px;
    font-weight: 700;
    letter-spacing: .5px;
}

.title-4 {
    margin: 0;
    text-align: center;
    font-size: 14px;

}

.doc-center {
    text-align: center;
    margin-top: 14px;
}

.doc-title-wrap {
    display: inline-block;
    text-align: center;
}

.doc-name {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
    text-transform: uppercase;

}

.doc-underline {
    display: inline-block;
    border-bottom: 1px solid #111827;
    line-height: 0;
    margin: 0;
}

.doc-underline .doc-no-text {
    visibility: hidden;
}

.doc-no {
    margin: -20px 0 0;
    font-size: 14px;
    font-weight: 700;
}

.mt-20 {
    margin-top: 14px;
}

.mt-16 {
    margin-top: 10px;
}

.mt-10 {
    margin-top: 6px;
}

.tbl {
    width: 100%;
    border-collapse: collapse;
}

.tbl td {
    padding: 2px 0;
    vertical-align: top;
}

.label {
    width: 170px;
}

.colon {
    width: 14px;
    text-align: center;
}

.value-indent {
    padding-left: 14px;
}

.tbl-indent {
    margin-left: 24px;
    width: calc(100% - 24px);
}

.alamat-line {
    display: block;
}

.justify {
    text-align: justify;
}

.para-indent {
    text-indent: 24px;
}

.sign-wrap {
    width: 260px;
    margin-left: auto;
    margin-top: 16px;
    text-align: center;
}

.sign-gap {
    height: 48px;
}

.sign-name {
    font-weight: 700;
    text-decoration: underline;
}
</style>

<div class="doc-wrap">
    <div class="doc-head">
        <div class="logo-cell">
            <img src="{{ $logoSrc }}" alt="Logo Tulungagung">
        </div>
        <div class="head-text">
            <p class="title-1">PEMERINTAH KABUPATEN TULUNGAGUNG</p>
            <p class="title-2">KECAMATAN SUMBERGEMPOL</p>
            <p class="title-3">DESA WONOREJO</p>
            <p class="title-4">Jln. Raya Desa Wonorejo Kecamatan Sumbergempol Kode Pos 66291</p>
        </div>
    </div>

    <div class="doc-center">
        <div class="doc-title-wrap">
            <p class="doc-name">Surat Kelahiran</p>
            <div class="doc-underline"><span class="doc-no-text">Reg.des.Nomor : {{ $regDesNomor }}</span></div>
            <p class="doc-no">Reg.des.Nomor : {{ $regDesNomor }}</p>
        </div>
    </div>

    <div class="mt-20">
        <p class="para-indent">Yang bertanda tangan di bawah ini kami Kepala Desa Wonorejo Kecamatan Sumbergempol
            Kabupaten Tulungagung,
            menerangkan bahwa :</p>
    </div>

    <div class="mt-15">
        <table class="tbl tbl-indent">
            <tr>
                <td class="label">Nama Lengkap</td>
                <td class="colon">:</td>
                <td class="value-indent">{{ strtoupper($data['nama_bayi'] ?? '-') }}</td>
            </tr>
            <tr>
                <td class="label">Jenis Kelamin</td>
                <td class="colon">:</td>
                <td class="value-indent">{{ ucfirst((string) ($data['jenis_kelamin_bayi'] ?? '-')) }}</td>
            </tr>
            <tr>
                <td class="label">Tempat, Tanggal Lahir</td>
                <td class="colon">:</td>
                <td class="value-indent">
                    @if(!empty($data['tempat_lahir_bayi']) && $tanggalBayi)
                    {{ ucfirst(strtolower($data['tempat_lahir_bayi'])) }}, {{ $tanggalBayi }}
                    @else
                    {{ $tanggalBayi ?? '-' }}
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">Alamat</td>
                <td class="colon">:</td>
                <td class="value-indent">
                    @if($alamatLine1 || $alamatLine2)
                    @if($alamatLine1)
                    <span class="alamat-line">{{ $alamatLine1 }}</span>
                    @endif
                    @if($alamatLine2)
                    <span class="alamat-line">{{ $alamatLine2 }}</span>
                    @endif
                    @else
                    <span class="alamat-line">-</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">Anak yang ke</td>
                <td class="colon">:</td>
                <td class="value-indent">{{ $data['anak_ke'] ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="mt-15">
        <p>Adalah anak kandung dari seorang AYAH :</p>
        <table class="tbl tbl-indent mt-10">
            <tr>
                <td class="label">Nama</td>
                <td class="colon">:</td>
                <td class="value-indent">{{ strtoupper($data['nama_ayah'] ?? '-') }}</td>
            </tr>
            <tr>
                <td class="label">Tempat Lahir</td>
                <td class="colon">:</td>
                <td class="value-indent">
                    @if(!empty($data['tempat_lahir_ayah']) && $tanggalAyah)
                    {{ ucfirst(strtolower($data['tempat_lahir_ayah'])) }}, {{ $tanggalAyah }}
                    @else
                    {{ $tanggalAyah ?? '-' }}
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">Agama</td>
                <td class="colon">:</td>
                <td class="value-indent">{{ ucfirst(strtolower($data['agama_ayah'] ?? '-')) }}</td>
            </tr>
            <tr>
                <td class="label">Alamat</td>
                <td class="colon">:</td>
                <td class="value-indent">
                    @if($alamatLine1 || $alamatLine2)
                    @if($alamatLine1)
                    <span class="alamat-line">{{ $alamatLine1 }}</span>
                    @endif
                    @if($alamatLine2)
                    <span class="alamat-line">{{ $alamatLine2 }}</span>
                    @endif
                    @else
                    <span class="alamat-line">-</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="mt-15">
        <p>Adalah anak kandung dari seorang IBU :</p>
        <table class="tbl tbl-indent mt-10">
            <tr>
                <td class="label">Nama</td>
                <td class="colon">:</td>
                <td class="value-indent">{{ strtoupper($data['nama_ibu'] ?? '-') }}</td>
            </tr>
            <tr>
                <td class="label">Tempat Lahir</td>
                <td class="colon">:</td>
                <td class="value-indent">
                    @if(!empty($data['tempat_lahir_ibu']) && $tanggalIbu)
                    {{ ucfirst(strtolower($data['tempat_lahir_ibu'])) }}, {{ $tanggalIbu }}
                    @else
                    {{ $tanggalIbu ?? '-' }}
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">Agama</td>
                <td class="colon">:</td>
                <td class="value-indent">{{ ucfirst(strtolower($data['agama_ibu'] ?? '-')) }}</td>
            </tr>
            <tr>
                <td class="label">Alamat</td>
                <td class="colon">:</td>
                <td class="value-indent">
                    @if($alamatLine1 || $alamatLine2)
                    @if($alamatLine1)
                    <span class="alamat-line">{{ $alamatLine1 }}</span>
                    @endif
                    @if($alamatLine2)
                    <span class="alamat-line">{{ $alamatLine2 }}</span>
                    @endif
                    @else
                    <span class="alamat-line">-</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="mt-15 justify">
        <p>{{ $surat->keperluan }}</p>
    </div>

    <div class="sign-wrap">
        <p>Wonorejo, {{ $surat->tanggal_surat?->translatedFormat('d - m - Y') }}</p>
        <p>Kepala Desa Wonorejo</p>
        <div class="sign-gap"></div>
        <p class="sign-name">ANIS WIJAYANTI</p>
    </div>
</div>
