<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(Request $request): View
    {
        $status = (string) $request->query('status', 'all');
        $search = trim((string) $request->query('q', ''));

        $query = User::query()
            ->withCount('suratPengajuans')
            ->orderByDesc('created_at');

        if ($status === 'approved' || $status === 'pending') {
            $query->where('approval_status', $status);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('nik', 'like', '%' . $search . '%')
                    ->orWhere('no_hp', 'like', '%' . $search . '%');
            });
        }

        $users = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => User::count(),
            'warga' => User::where('role', 'warga')->count(),
            'approved' => User::where('role', 'warga')->where('approval_status', 'approved')->count(),
            'pending' => User::where('role', 'warga')->where('approval_status', 'pending')->count(),
            'adminPetugas' => User::whereIn('role', ['admin', 'petugas'])->count(),
        ];

        $regionMaps = $this->buildRegionMaps($users->getCollection());
        $users->setCollection(
            $users->getCollection()->map(function (User $user) use ($regionMaps) {
                $user->provinsi_display = $this->regionDisplay($user->provinsi_id, $regionMaps['provinsi']);
                $user->kabupaten_display = $this->regionDisplay($user->kabupaten_id, $regionMaps['kabupaten']);
                $user->kecamatan_display = $this->regionDisplay($user->kecamatan_id, $regionMaps['kecamatan']);
                $user->desa_display = $this->regionDisplay($user->desa_id, $regionMaps['desa']);
                return $user;
            })
        );

        return view('admin.users.index', [
            'users' => $users,
            'stats' => $stats,
            'status' => $status,
            'search' => $search,
        ]);
    }

    public function updateApproval(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'approval_status' => ['required', 'in:pending,approved'],
        ]);

        if ($user->role !== 'warga') {
            return back()->with('status_error', 'Status approval hanya berlaku untuk akun warga.');
        }

        $status = $validated['approval_status'];

        $user->approval_status = $status;
        if ($status === 'approved') {
            $user->approved_at = Carbon::now();
            $user->approved_by = $request->user()?->id;
        } else {
            $user->approved_at = null;
            $user->approved_by = null;
        }
        $user->save();

        return back()->with('status', 'Status akun pengguna berhasil diperbarui.');
    }

    private function buildRegionMaps($users): array
    {
        $provinceMap = [];
        $regencyMap = [];
        $districtMap = [];
        $villageMap = [];

        $provinceData = $this->fetchWilayah('https://wilayah.id/api/provinces.json');
        foreach ($provinceData['data'] ?? [] as $item) {
            if (!empty($item['code']) && !empty($item['name'])) {
                $provinceMap[(string) $item['code']] = (string) $item['name'];
            }
        }

        $provinceCodes = $users->pluck('provinsi_id')->filter()->unique()->values();
        foreach ($provinceCodes as $provinceCode) {
            $regencies = $this->fetchWilayah("https://wilayah.id/api/regencies/{$provinceCode}.json");
            foreach ($regencies['data'] ?? [] as $item) {
                if (!empty($item['code']) && !empty($item['name'])) {
                    $regencyMap[(string) $item['code']] = (string) $item['name'];
                }
            }
        }

        $regencyCodes = $users->pluck('kabupaten_id')->filter()->unique()->values();
        foreach ($regencyCodes as $regencyCode) {
            $districts = $this->fetchWilayah("https://wilayah.id/api/districts/{$regencyCode}.json");
            foreach ($districts['data'] ?? [] as $item) {
                if (!empty($item['code']) && !empty($item['name'])) {
                    $districtMap[(string) $item['code']] = (string) $item['name'];
                }
            }
        }

        $districtCodes = $users->pluck('kecamatan_id')->filter()->unique()->values();
        foreach ($districtCodes as $districtCode) {
            $villages = $this->fetchWilayah("https://wilayah.id/api/villages/{$districtCode}.json");
            foreach ($villages['data'] ?? [] as $item) {
                if (!empty($item['code']) && !empty($item['name'])) {
                    $villageMap[(string) $item['code']] = (string) $item['name'];
                }
            }
        }

        return [
            'provinsi' => $provinceMap,
            'kabupaten' => $regencyMap,
            'kecamatan' => $districtMap,
            'desa' => $villageMap,
        ];
    }

    private function regionDisplay(?string $value, array $map): string
    {
        if (! $value) {
            return '-';
        }

        $raw = trim($value);
        $name = $map[$raw] ?? null;
        if ($name) {
            return $this->cleanRegionName($name);
        }

        if (preg_match('/[A-Za-z]/', $raw)) {
            return $this->cleanRegionName($raw);
        }

        return $raw;
    }

    private function cleanRegionName(string $value): string
    {
        return trim((string) preg_replace('/^(Kabupaten|Kota|Provinsi|Kecamatan|Desa)\s+/i', '', $value));
    }

    private function fetchWilayah(string $url): array
    {
        $context = stream_context_create([
            'http' => [
                'timeout' => 8,
            ],
        ]);

        $body = @file_get_contents($url, false, $context);
        if ($body === false) {
            return ['data' => []];
        }

        return json_decode($body, true) ?? ['data' => []];
    }
}
