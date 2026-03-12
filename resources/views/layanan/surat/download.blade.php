@php
    $templateMap = [
        \App\Models\SuratPengajuan::JENIS_DOMISILI => 'layanan.surat.templates.domisili',
        \App\Models\SuratPengajuan::JENIS_TIDAK_MAMPU => 'layanan.surat.templates.tidak_mampu',
        \App\Models\SuratPengajuan::JENIS_KEMATIAN => 'layanan.surat.templates.kematian',
        \App\Models\SuratPengajuan::JENIS_KELAHIRAN => 'layanan.surat.templates.kelahiran',
    ];
    $templateView = $templateMap[$surat->jenis_surat] ?? 'layanan.surat.templates.domisili';
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $jenisLabel }} - {{ $surat->nomor_surat }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 16px; color: #111827; }
    </style>
</head>
<body>
    @include($templateView, [
        'surat' => $surat,
        'user' => $user,
        'jenisLabel' => $jenisLabel,
        'ttlFormatted' => $ttlFormatted,
        'domisiliAlamat' => $domisiliAlamat,
        'alamatDomisiliLines' => $alamatDomisiliLines,
        'alamatRingkas' => $alamatRingkas,
        'isPdf' => true,
    ])
</body>
</html>
