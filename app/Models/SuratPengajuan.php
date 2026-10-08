<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratPengajuan extends Model
{
    use HasFactory;

    public const JENIS_DOMISILI = 'domisili';
    public const JENIS_TIDAK_MAMPU = 'tidak_mampu';
    public const JENIS_KEMATIAN = 'kematian';
    public const JENIS_KELAHIRAN = 'kelahiran';
    public const JENIS_USAHA = 'usaha';
    public const JENIS_BELUM_MENIKAH = 'belum_menikah';
    public const JENIS_KEHILANGAN = 'kehilangan';
    public const JENIS_PENGHASILAN_ORTU = 'penghasilan_ortu';
    public const KODE_DESA = 'DSW';

    protected $fillable = [
        'user_id',
        'jenis_surat',
        'nomor_surat',
        'nomor_urut',
        'tahun',
        'perihal',
        'keperluan',
        'catatan',
        'status_perkawinan',
        'pekerjaan',
        'tanggal_surat',
        'status',
        'admin_note',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_surat' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function jenisOptions(): array
    {
        return [
            self::JENIS_DOMISILI => 'Surat Keterangan Domisili',
            self::JENIS_TIDAK_MAMPU => 'Surat Keterangan Tidak Mampu',
            self::JENIS_KEMATIAN => 'Surat Keterangan Kematian',
            self::JENIS_KELAHIRAN => 'Surat Keterangan Kelahiran',
            self::JENIS_USAHA => 'Surat Keterangan Usaha',
            self::JENIS_BELUM_MENIKAH => 'Surat Keterangan Belum Menikah',
            self::JENIS_KEHILANGAN => 'Surat Keterangan Kehilangan',
            self::JENIS_PENGHASILAN_ORTU => 'Surat Keterangan Penghasilan Orang Tua',
        ];
    }

    public static function kodeJenis(string $jenis): string
    {
        return match ($jenis) {
            self::JENIS_DOMISILI => 'SKD',
            self::JENIS_TIDAK_MAMPU => 'SKTM',
            self::JENIS_KEMATIAN => 'SKKM',
            self::JENIS_KELAHIRAN => 'SKK',
            self::JENIS_USAHA => 'SKU',
            self::JENIS_BELUM_MENIKAH => 'SKBM',
            self::JENIS_KEHILANGAN => 'SKH',
            self::JENIS_PENGHASILAN_ORTU => 'SKPOT',
            default => 'SK',
        };
    }

    public static function formatNomorUrut(int|string|null $nomorUrut): string
    {
        $nomorUrut = preg_replace('/\D+/', '', (string) $nomorUrut);

        if ($nomorUrut === '') {
            return '';
        }

        return str_pad($nomorUrut, 3, '0', STR_PAD_LEFT);
    }

    public static function nomorSuratSuffix(string $jenis, ?DateTimeInterface $tanggalSurat = null): string
    {
        $setting = self::nomorSetting($jenis);
        $tanggalSurat ??= now();

        return implode('/', [
            $setting['kode_wilayah'],
            $tanggalSurat->format('Y'),
        ]);
    }

    public static function nomorSuratPrefix(string $jenis): string
    {
        return self::nomorSetting($jenis)['kode_klasifikasi'];
    }

    public static function buatNomorSurat(int|string $nomorUrut, string $jenis, ?DateTimeInterface $tanggalSurat = null): string
    {
        return implode('/', [
            self::nomorSuratPrefix($jenis),
            self::formatNomorUrut($nomorUrut),
            self::nomorSuratSuffix($jenis, $tanggalSurat),
        ]);
    }

    public static function nomorSetting(string $jenis): array
    {
        $setting = SuratNomorSetting::query()
            ->where('jenis_surat', $jenis)
            ->first();

        if ($setting) {
            return [
                'jenis_surat' => $jenis,
                'kode_klasifikasi' => $setting->kode_klasifikasi,
                'kode_wilayah' => $setting->kode_wilayah,
            ];
        }

        return SuratNomorSetting::defaultsFor($jenis);
    }

    public function nomorUrutDisplay(): string
    {
        if ($this->nomor_urut !== null) {
            return self::formatNomorUrut($this->nomor_urut);
        }

        $segments = str($this->nomor_surat)->explode('/');
        $firstSegment = (string) $segments->get(0, '');

        return self::formatNomorUrut(ctype_digit($firstSegment) ? $firstSegment : (string) $segments->get(1, ''));
    }

    public static function statusPerkawinanLabel(?string $value): string
    {
        return match ($value) {
            'belum_kawin' => 'Belum Kawin',
            'kawin' => 'Kawin',
            'cerai_hidup' => 'Cerai Hidup',
            'cerai_mati' => 'Cerai Mati',
            default => '-',
        };
    }

    public function jenisLabel(): string
    {
        return self::jenisOptions()[$this->jenis_surat] ?? 'Surat Keterangan';
    }

    public function whatsappMessage(): string
    {
        $namaWarga = $this->user?->name ?? 'Warga';
        $nikWarga = $this->user?->nik ?? '-';
        $jenisSurat = $this->jenisLabel();
        $alamatLengkap = $this->user?->fullAddress() ?? '-';

        return trim(implode("\n", [
            "Yth. sdr {$namaWarga},",
            '',
            'Pengajuan surat Anda sudah disetujui pihak desa.',
            "NIK Anda: {$nikWarga}",
            "Jenis surat: {$jenisSurat}",
            "Nomor surat: " . ($this->nomor_surat ?: '-'),
            "Alamat lengkap: {$alamatLengkap}",
            '',
            'Langkah selanjutnya:',
            '1. Silakan login ke website sistem pelayanan desa wonorejo untuk melihat atau mengunduh surat.',
            '2. Setelah itu, silahkan ke kantor desa untuk tanda tangan dan pengambilan surat.',
            '',
            'Terima kasih.',
        ]));
    }

    public function whatsappUrl(): ?string
    {
        $whatsappNumber = $this->user?->whatsappNumber();

        if ($whatsappNumber === null) {
            return null;
        }

        return 'https://wa.me/' . $whatsappNumber . '?text=' . urlencode($this->whatsappMessage());
    }
}
