<div class="mx-auto max-w-3xl text-[15px] leading-relaxed text-gray-900">
    <div class="border-b-4 border-double border-gray-800 pb-3 text-center">
        <p class="text-[30px] font-bold tracking-wide leading-tight">PEMERINTAH KABUPATEN TULUNGAGUNG</p>
        <p class="text-[26px] font-bold tracking-wide leading-tight">KECAMATAN SUMBERGEMPOL</p>
        <p class="text-[42px] font-bold tracking-wide leading-tight">DESA WONOREJO</p>
        <p class="text-[20px] leading-tight">Jln. Raya Desa Wonorejo Kecamatan Sumbergempol Kode Pos 66291</p>
    </div>

    <div class="mt-6 text-center">
        <p class="text-[30px] font-bold uppercase underline decoration-1 underline-offset-4">Surat Keterangan Kelahiran</p>
        <p class="mt-1 text-[18px]">Reg.des.Nomor : {{ $surat->nomor_surat }}</p>
    </div>

    <div class="mt-8">
        <p>Yang bertanda tangan di bawah ini menerangkan bahwa:</p>
        <table class="mt-3 w-full">
            <tbody>
                <tr>
                    <td class="w-44 py-1">Nama</td>
                    <td class="w-6 py-1">:</td>
                    <td class="py-1">{{ strtoupper($user->name) }}</td>
                </tr>
                <tr>
                    <td class="py-1">NIK</td>
                    <td class="py-1">:</td>
                    <td class="py-1">{{ $user->nik }}</td>
                </tr>
                <tr>
                    <td class="py-1">Tempat Tanggal Lahir</td>
                    <td class="py-1">:</td>
                    <td class="py-1">{{ $ttlFormatted }}</td>
                </tr>
                <tr>
                    <td class="py-1 align-top">Alamat</td>
                    <td class="py-1 align-top">:</td>
                    <td class="py-1">
                        @foreach($alamatDomisiliLines as $line)
                            <div>{{ $line }}</div>
                        @endforeach
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <p class="mt-8 text-justify">
        Surat keterangan ini diterbitkan untuk keperluan <strong>{{ $surat->keperluan }}</strong> dan dipergunakan
        sebagaimana mestinya sesuai peraturan administrasi yang berlaku.
    </p>

    <div class="mt-14 flex justify-end">
        <div class="w-72 text-center">
            <p>Wonorejo, {{ $surat->tanggal_surat?->translatedFormat('d - m - Y') }}</p>
            <p class="mt-1">Kepala Desa Wonorejo</p>
            <div class="h-20"></div>
            <p class="font-semibold underline underline-offset-2">ANIS WIJAYANTI</p>
        </div>
    </div>
</div>
