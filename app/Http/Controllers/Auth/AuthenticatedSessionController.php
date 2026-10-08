<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Display the admin login view.
     */
    public function createAdmin(): View
    {
        return view('auth.admin-login');
    }

    /**
     * Display the petugas login view.
     */
    public function createPetugas(): View
    {
        return view('auth.petugas-login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
        $request->session()->forget('url.intended');

        if (($request->user()?->role ?? null) === 'petugas') {
            return redirect()->route('petugas.dashboard');
        }

        if (($request->user()?->role ?? null) === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($request->user()?->approval_status !== 'approved') {
            return redirect()->route('account.pending');
        }

        return redirect()->route('dashboard');
    }

    /**
     * Handle an incoming admin authentication request.
     */
    public function storeAdmin(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Email/password salah.'])
                ->onlyInput('email');
        }

        if (($request->user()?->role ?? null) !== 'admin') {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors(['email' => 'Akun ini bukan admin. Silakan gunakan login pengguna/petugas.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();
        $request->session()->forget('url.intended');

        return redirect()->route('admin.dashboard');
    }

    /**
     * Handle an incoming petugas authentication request.
     */
    public function storePetugas(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Email/password salah.'])
                ->onlyInput('email');
        }

        if (($request->user()?->role ?? null) !== 'petugas') {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors(['email' => 'Akun ini bukan petugas. Silakan gunakan login pengguna/admin.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();
        $request->session()->forget('url.intended');

        return redirect()->route('petugas.dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $role = $request->user()?->role;

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return match ($role) {
            'admin' => redirect()->route('admin.login'),
            'petugas' => redirect()->route('petugas.login'),
            default => redirect('/'),
        };
    }
}
