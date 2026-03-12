<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LayananController extends Controller
{
    public function index(): View
    {
        return view('landing.layanan');
    }

    public function mulaiPengajuan(Request $request): RedirectResponse
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        if ($request->user()->approval_status !== 'approved') {
            return redirect()->route('account.pending');
        }

        return redirect()->route('dashboard');
    }
}
