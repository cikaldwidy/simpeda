<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'jenis_kelamin' => ['required', 'in:laki-laki,perempuan'],
            'nik' => ['required', 'regex:/^[0-9]+$/', 'digits_between:1,16', 'unique:' . User::class],
            'no_hp' => ['required', 'string', 'max:20'],
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'tanggal_lahir' => ['required', 'date', 'before_or_equal:today'],

            // wilayah (dropdown)
            'provinsi_id'  => ['required', 'string', 'max:10'],
            'kabupaten_id' => ['required', 'string', 'max:10'],
            'kecamatan_id' => ['required', 'string', 'max:15'],
            'desa_id'      => ['required', 'string', 'max:20'],
            'rt/rw' => ['nullable', 'string', 'max:7'],
            'dusun' => ['nullable', 'string', 'max:100'],
            'kode_pos' => ['nullable', 'string', 'max:10'],

            // detail alamat
            'alamat_detail' => ['nullable', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $tanggalLahir = Carbon::parse($request->tanggal_lahir);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'jenis_kelamin' => $request->jenis_kelamin,
            'nik' => $request->nik,
            'no_hp' => $request->no_hp,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => (int) $tanggalLahir->format('d'),
            'bulan_lahir' => (int) $tanggalLahir->format('m'),
            'tahun_lahir' => (int) $tanggalLahir->format('Y'),

            // wilayah
            'provinsi_id' => $request->provinsi_id,
            'kabupaten_id' => $request->kabupaten_id,
            'kecamatan_id' => $request->kecamatan_id,
            'desa_id' => $request->desa_id,
            'rt/rw' => $request->input('rt/rw'),
            'dusun' => $request->dusun,
            'kode_pos' => $request->kode_pos,
            // detail alamat
            'alamat_detail' => $request->alamat_detail,

            'approval_status' => 'pending',
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
