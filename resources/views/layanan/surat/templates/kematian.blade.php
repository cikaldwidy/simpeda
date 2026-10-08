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
$tanggalLahirRaw = $data['tanggal_lahir_meninggal'] ?? null;
$tanggalLahirFormatted = $tanggalLahirRaw
? \Illuminate\Support\Carbon::parse($tanggalLahirRaw)->format('d - m - Y')
: null;
$sentenceCase = static function (?string $value): string {
$value = trim((string) $value);

if ($value === '') {
return '-';
}

$lower = mb_strtolower($value);

return mb_strtoupper(mb_substr($lower, 0, 1)) . mb_substr($lower, 1);
};
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

.mt-8 {
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

.value-indent-ortu {
    padding-left: 14px;
    text-transform: uppercase;
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
            <p class="doc-name">Surat Kematian</p>
            <div class="doc-underline"><span class="doc-no-text">Reg.des.Nomor : {{ $regDesNomor }}</span></div>
            <p class="doc-no">Reg.des.Nomor : {{ $regDesNomor }}</p>
        </div>
    </div>

    <div class="mt-20">
        <p>Yang bertanda tangan dibawah ini menerangkan bahwa :</p>
    </div>

    <div class="mt-16">
        <table class="tbl tbl-indent">
            <tr>
                <td class="label">Nama</td>
                <td class="colon">:</td>
                <td class="value-indent">{{ strtoupper($data['nama_meninggal'] ?? '-') }}</td>
            </tr>
            <tr>
                <td class="label">Jenis Kelamin</td>
                <td class="colon">:</td>
                <td class="value-indent">{{ ucfirst((string) ($data['jenis_kelamin_meninggal'] ?? '-')) }}</td>
            </tr>
            <tr>
                <td class="label">Alamat</td>
                <td class="colon">:</td>
                <td class="value-indent">
                    @forelse($alamatLines as $line)
                    <span class="alamat-line">{{ $line }}</span>
                    @empty
                    <span class="alamat-line">-</span>
                    @endforelse
                </td>
            </tr>
            <tr>
                <td class="label">Tanggal Lahir</td>
                <td class="colon">:</td>
                <td class="value-indent">
                    @if(!empty($data['tempat_lahir_meninggal']) && $tanggalLahirFormatted)
                    {{ $sentenceCase($data['tempat_lahir_meninggal']) }}, {{ $tanggalLahirFormatted }}
                    @else
                    {{ $tanggalLahirFormatted ?? '-' }}
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">Tanggal Meninggal</td>
                <td class="colon">:</td>
                <td class="value-indent">
                    {{ !empty($data['tanggal_meninggal']) ? \Illuminate\Support\Carbon::parse($data['tanggal_meninggal'])->format('d - m - Y') : '-' }}
                </td>
            </tr>
            <tr>
                <td class="label">Usia Meninggal</td>
                <td class="colon">:</td>
                <td class="value-indent">{{ $data['usia_meninggal'] ?? '-' }} Tahun</td>
            </tr>
            <tr>
                <td class="label">Nama Ortu (Bapak/Ibu)</td>
                <td class="colon">:</td>
                <td class="value-indent-ortu">{{ $data['nama_ortu_meninggal'] ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Di</td>
                <td class="colon">:</td>
                <td class="value-indent">{{ $sentenceCase($data['lokasi_meninggal'] ?? '-') }}
            </tr>
            <tr>
                <td class="label">Disebabkan Karena</td>
                <td class="colon">:</td>
                <td class="value-indent">{{ $sentenceCase($data['sebab_meninggal'] ?? '-') }}</td>
            </tr>
        </table>
    </div>

    <div class="mt-16 justify">
        <p>{{ $surat->keperluan }}</p>
    </div>

    <div class="sign-wrap">
        <p>Wonorejo, {{ $surat->tanggal_surat?->translatedFormat('d - m - Y') }}</p>
        <p>Kepala Desa Wonorejo</p>
        <div class="sign-gap"></div>
        <p class="sign-name">ANIS WIJAYANTI</p>
    </div>
</div>
