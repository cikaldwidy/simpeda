<?php

namespace App\Http\Controllers;

use App\Models\SuratPengajuan;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        $suratPengajuan = SuratPengajuan::query()
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('profile.edit', [
            'user' => $user,
            'suratPengajuan' => $suratPengajuan,
            'alamatDomisiliLines' => $this->alamatDomisiliLines($user),
            'ttlFormatted' => $this->ttlFormatted($user),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    private function ttlFormatted($user): string
    {
        if (! $user->tanggal_lahir || ! $user->bulan_lahir || ! $user->tahun_lahir) {
            return '-';
        }

        $tanggal = str_pad((string) $user->tanggal_lahir, 2, '0', STR_PAD_LEFT);
        $bulan = str_pad((string) $user->bulan_lahir, 2, '0', STR_PAD_LEFT);
        $tahun = (string) $user->tahun_lahir;

        if (! $user->tempat_lahir) {
            return "{$tanggal}-{$bulan}-{$tahun}";
        }

        return $user->tempat_lahir . ', ' . "{$tanggal}-{$bulan}-{$tahun}";
    }

    private function alamatDomisiliLines($user): array
    {
        $desaNama = $this->cleanRegionName($user->desa_id);
        $kecamatanNama = $this->cleanRegionName($user->kecamatan_id);
        $kabupatenNama = $this->cleanRegionName($user->kabupaten_id);
        $provinsiNama = $this->cleanRegionName($user->provinsi_id);

        $dusunRt = trim(implode(' ', array_filter([
            $user->dusun ? 'Dusun ' . $user->dusun : null,
            $this->formattedRtRw($user->{'rt/rw'} ?? null),
        ])));

        $desaKec = trim(implode(' ', array_filter([
            $desaNama ? 'Desa ' . $desaNama : null,
            $kecamatanNama ? 'Kecamatan ' . $kecamatanNama : null,
        ])));

        $kabupaten = $kabupatenNama ? 'Kabupaten ' . $kabupatenNama : null;
        $provinsi = $provinsiNama ? 'Provinsi ' . $provinsiNama : null;

        return array_values(array_filter([
            $dusunRt ?: null,
            $desaKec ?: null,
            $kabupaten,
            $provinsi,
        ]));
    }

    private function formattedRtRw(?string $rtRw): ?string
    {
        if (! $rtRw) {
            return null;
        }

        $parts = explode('/', $rtRw);
        $rt = trim($parts[0] ?? '');
        $rw = trim($parts[1] ?? '');

        if ($rt !== '' && $rw !== '') {
            return 'RT.' . $rt . ' RW.' . $rw;
        }

        return 'RT/RW ' . $rtRw;
    }

    private function cleanRegionName(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        return trim((string) preg_replace('/^(Kabupaten|Kota|Provinsi|Kecamatan|Desa)\s+/i', '', $value));
    }
}
