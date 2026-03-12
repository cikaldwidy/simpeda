@php
$logoSrc = !empty($isPdf) && empty($isPreview)
? public_path('img/logo_TA.png')
: asset('img/logo_TA.png');
$regDesNomor = '474.1/' . ($surat->nomor_urut ?? '-') . '/' . ($user->desa_id ?? '-') . '/' . ($surat->tahun ??
optional($surat->tanggal_surat)->format('Y'));
$statusPerkawinan = match ($surat->status_perkawinan) {
'belum_kawin' => 'Belum Kawin',
'kawin' => 'Kawin',
'cerai_hidup' => 'Cerai Hidup',
'cerai_mati' => 'Cerai Mati',
default => '-',
};
@endphp

<style>
.doc-wrap {
    width: 100%;
    font-family: Arial, sans-serif;
    color: #111827;
    font-size: 12px;
    line-height: 1.55;
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
    font-size: 12px;
}

.doc-center {
    text-align: center;
    margin-top: 16px;
}

.doc-name {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
    text-transform: uppercase;
    text-decoration: underline;
}

.doc-no {
    margin: 4px 0 0;
    font-size: 12px;
    font-weight: 700;
}

.mt-20 {
    margin-top: 20px;
}

.mt-16 {
    margin-top: 16px;
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
    width: 12px;
    text-align: center;
}

.alamat-line {
    display: block;
}

.justify {
    text-align: justify;
}

.sign-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 12px;
}

.sign-table td {
    width: 50%;
    text-align: center;
    vertical-align: top;
    padding: 0;
}

.sign-gap {
    height: 70px;
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
        <p class="doc-name">Surat Keterangan Tidak Mampu</p>
        <p class="doc-no">Reg.des.Nomor : {{ $regDesNomor }}</p>
    </div>

    <div class="mt-20">
        <p class="justify">
            Yang bertanda tangan di bawah ini kami Kepala Desa Wonorejo Kecamatan Sumbergempol Kabupaten Tulungagung,
            dengan ini menerangkan bahwa :
        </p>
    </div>

    <div class="mt-16">
        <p>Menerangkan dengan sesungguhnya bahwa orang yang tercantum di bawah ini :</p>
        <table class="tbl">
            <tr>
                <td class="label">Nama</td>
                <td class="colon">:</td>
                <td>{{ strtoupper($user->name) }}</td>
            </tr>
            <tr>
                <td class="label">Tempat Tanggal Lahir</td>
                <td class="colon">:</td>
                <td>{{ $ttlFormatted }}</td>
            </tr>
            <tr>
                <td class="label">NIK</td>
                <td class="colon">:</td>
                <td>{{ $user->nik }}</td>
            </tr>
            <tr>
                <td class="label">Alamat</td>
                <td class="colon">:</td>
                <td>
                    @foreach($alamatDomisiliLines as $line)
                    <span class="alamat-line">{{ $line }}</span>
                    @endforeach
                </td>
            </tr>
            <tr>
                <td class="label">Jenis Kelamin</td>
                <td class="colon">:</td>
                <td>{{ ucfirst((string) $user->jenis_kelamin) }}</td>
            </tr>
            <tr>
                <td class="label">Status Perkawinan</td>
                <td class="colon">:</td>
                <td>{{ $statusPerkawinan }}</td>
            </tr>
            <tr>
                <td class="label">Pekerjaan</td>
                <td class="colon">:</td>
                <td>{{ $surat->pekerjaan ?: '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="mt-16 justify">
        <p>
            Menerangkan yang tersebut di atas benar-benar penduduk Desa Wonorejo Kecamatan Sumbergempol Kabupaten
            Tulungagung dan kondisi ekonominya tergolong tidak mampu.
            Surat keterangan ini dipergunakan untuk keperluan <strong>{{ $surat->keperluan ?: '-' }}</strong>
            @if(!empty($surat->catatan))
            di <strong>{{ $surat->catatan }}</strong>.
            @else
            .
            @endif
        </p>
        <p>Demikian Surat Keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
    </div>

    <table class="sign-table mt-20">
        <tr>
            <td>
                <p>Pemegang Surat</p>
            </td>
            <td>
                <p>Wonorejo, {{ $surat->tanggal_surat?->translatedFormat('d - m - Y') }}</p>
                <p>Kepala Desa Wonorejo</p>
            </td>
        </tr>
        <tr>
            <td><div class="sign-gap"></div></td>
            <td><div class="sign-gap"></div></td>
        </tr>
        <tr>
            <td>
                <p class="sign-name">{{ strtoupper($user->name) }}</p>
            </td>
            <td>
                <p class="sign-name">ANIS WIJAYANTI</p>
            </td>
        </tr>
    </table>
</div>
