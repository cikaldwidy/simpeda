<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

        $users = $query->paginate(10)->withQueryString();

        $stats = [
            'total' => User::count(),
            'warga' => User::where('role', 'warga')->count(),
            'approved' => User::where('role', 'warga')->where('approval_status', 'approved')->count(),
            'pending' => User::where('role', 'warga')->where('approval_status', 'pending')->count(),
            'adminPetugas' => User::whereIn('role', ['admin', 'petugas'])->count(),
        ];

        $users->setCollection(
            $users->getCollection()->map(function (User $user) {
                $user->provinsi_display = $this->cleanRegionName((string) ($user->provinsi_id ?? '-'));
                $user->kabupaten_display = $this->cleanRegionName((string) ($user->kabupaten_id ?? '-'));
                $user->kecamatan_display = $this->cleanRegionName((string) ($user->kecamatan_id ?? '-'));
                $user->desa_display = $this->cleanRegionName((string) ($user->desa_id ?? '-'));
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

    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        $currentUser = $request->user();

        if (($currentUser?->role ?? null) !== 'admin' && $user->role !== 'warga') {
            return back()->with('status_error', 'Petugas hanya dapat mereset password akun warga.');
        }

        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->forceFill([
            'password' => Hash::make($validated['password']),
        ])->save();

        return back()->with('status', 'Password pengguna berhasil direset.');
    }

    private function cleanRegionName(string $value): string
    {
        return trim((string) preg_replace('/^(Kabupaten|Kota|Provinsi|Kecamatan|Desa)\s+/i', '', $value));
    }
}
