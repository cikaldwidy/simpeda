<?php

namespace App\Http\Controllers;

use App\Models\LaporanAnggaran;
use Illuminate\View\View;

class LaporanAnggaranPublicController extends Controller
{
    public function show(LaporanAnggaran $anggaran): View
    {
        abort_unless($anggaran->is_published, 404);
        $anggaran->load('items');

        return view('landing.anggaran.show', compact('anggaran'));
    }
}
