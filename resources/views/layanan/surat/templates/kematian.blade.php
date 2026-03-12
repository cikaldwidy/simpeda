@php
$logoSrc = !empty($isPdf) && empty($isPreview)
    ? public_path('img/logo_TA.png')
    : asset('img/logo_TA.png');
$regDesNomor = '472.12/' . ($surat->nomor_urut ?? '-') . '/' . ($user->desa_id ?? '-') . '/' . ($surat->tahun ?? optional($surat->tanggal_surat)->format('Y'));
$data = json_decode((string) $surat->catatan, true) ?: [];
$alamatLine1 = trim(implode(' ', array_filter([
    !empty($data['dusun_meninggal']) ? 'Dusun ' . $data['dusun_meninggal'] : null,
    !empty($data['rt_rw_meninggal']) ? 'RT.' . str_replace('/', ' RW.', $data['rt_rw_meninggal']) : null,
    !empty($data['desa_meninggal']) ? 'Desa ' . $data['desa_meninggal'] : null,
])));
$alamatLine2 = trim(implode(' ', array_filter([
    !empty($data['kecamatan_meninggal']) ? 'Kecamatan ' . $data['kecamatan_meninggal'] : null,
    !empty($data['kabupaten_meninggal']) ? 'Kabupaten ' . $data['kabupaten_meninggal'] : null,
])));
@endphp

<style>
.doc-wrap { width: 100%; font-family: Arial, sans-serif; color: #111827; font-size: 12px; line-height: 1.55; }
.doc-head { width: 100%; border-bottom: 3px double #1f2937; padding-bottom: 8px; margin: 0 auto; position: relative; min-height: 84px; }
.logo-cell { position: absolute; left: 0; top: 0; width: 110px; }
.logo-cell img { width: 110px; height: 80px; object-fit: contain; margin-top: 10px; }
.head-text { text-align: center; }
.title-1,.title-2,.title-3 { margin: 0; text-align: center; font-size: 18px; font-weight: 700; letter-spacing: .5px; }
.title-4 { margin: 0; text-align: center; font-size: 12px; }
.doc-center { text-align: center; margin-top: 16px; }
.doc-name { margin: 0; font-size: 16px; font-weight: 700; text-transform: uppercase; text-decoration: underline; }
.doc-no { margin: 4px 0 0; font-size: 12px; font-weight: 700; }
.mt-20 { margin-top: 20px; }
.mt-16 { margin-top: 16px; }
.tbl { width: 100%; border-collapse: collapse; }
.tbl td { padding: 2px 0; vertical-align: top; }
.label { width: 170px; }
.colon { width: 12px; text-align: center; }
.alamat-line { display: block; }
.justify { text-align: justify; }
.sign-wrap { width: 260px; margin-left: auto; margin-top: 38px; text-align: center; }
.sign-gap { height: 70px; }
.sign-name { font-weight: 700; text-decoration: underline; }
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
        <p class="doc-name">Surat Kematian</p>
        <p class="doc-no">NO : {{ $regDesNomor }}</p>
    </div>

    <div class="mt-20">
        <p>Yang bertanda tangan dibawah ini menerangkan bahwa :</p>
    </div>

    <div class="mt-16">
        <table class="tbl">
            <tr>
                <td class="label">Nama</td>
                <td class="colon">:</td>
                <td>{{ strtoupper($data['nama_meninggal'] ?? '-') }}</td>
            </tr>
            <tr>
                <td class="label">Jenis Kelamin</td>
                <td class="colon">:</td>
                <td>{{ ucfirst((string) ($data['jenis_kelamin_meninggal'] ?? '-')) }}</td>
            </tr>
            <tr>
                <td class="label">Alamat</td>
                <td class="colon">:</td>
                <td>
                    @if($alamatLine1 !== '')
                    <span class="alamat-line">{{ $alamatLine1 }}</span>
                    @endif
                    @if($alamatLine2 !== '')
                    <span class="alamat-line">{{ $alamatLine2 }}</span>
                    @endif
                    @if(!empty($data['provinsi_meninggal']))
                    <span class="alamat-line">Provinsi {{ $data['provinsi_meninggal'] }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">Usia</td>
                <td class="colon">:</td>
                <td>{{ $data['usia_meninggal'] ?? '-' }} Tahun</td>
            </tr>
            <tr>
                <td class="label">Tanggal Meninggal</td>
                <td class="colon">:</td>
                <td>{{ !empty($data['tanggal_meninggal']) ? \Illuminate\Support\Carbon::parse($data['tanggal_meninggal'])->format('d - m - Y') : '-' }}</td>
            </tr>
            <tr>
                <td class="label">Di</td>
                <td class="colon">:</td>
                <td>{{ $data['lokasi_meninggal'] ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Disebabkan Karena</td>
                <td class="colon">:</td>
                <td>{{ $data['sebab_meninggal'] ?? '-' }}</td>
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
