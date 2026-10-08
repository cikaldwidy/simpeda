<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'jenis_kelamin',
        'nik',
        'no_hp',
        'tempat_lahir',
        'tanggal_lahir',
        'bulan_lahir',
        'tahun_lahir',

        // wilayah
        'provinsi_id',
        'kabupaten_id',
        'kecamatan_id',
        'desa_id',
        'rt/rw',
        'dusun',
        'kode_pos',

        // detail alamat
        'alamat_detail',

        // approval
        'approval_status',
        'approved_at',
        'approved_by',
        'rejection_reason',

        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'approved_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function suratPengajuans(): HasMany
    {
        return $this->hasMany(SuratPengajuan::class);
    }

    public function whatsappNumber(): ?string
    {
        $phone = preg_replace('/\D+/', '', (string) $this->no_hp);

        if ($phone === '') {
            return null;
        }

        if (str_starts_with($phone, '0')) {
            return '62' . substr($phone, 1);
        }

        if (str_starts_with($phone, '62')) {
            return $phone;
        }

        return null;
    }

    public function fullAddress(): string
    {
        $parts = array_filter([
            $this->alamat_detail,
            $this->dusun ? 'Dusun ' . $this->dusun : null,
            $this->{'rt/rw'} ? 'RT/RW ' . $this->{'rt/rw'} : null,
            $this->desa_id,
            $this->kecamatan_id,
            $this->kabupaten_id,
            $this->provinsi_id,
            $this->kode_pos,
        ], fn ($value) => filled($value));

        return $parts !== [] ? implode(', ', $parts) : '-';
    }
}
