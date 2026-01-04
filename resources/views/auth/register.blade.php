<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - {{ config('app.name') }}</title>

    {{-- CSS kamu (buat di public/css/auth.css) --}}
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>

<div class="login-container">
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <h2>Daftar Akun</h2>

        {{-- Error validasi --}}
        @if ($errors->any())
            <div class="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Nama --}}
        <div class="input-group">
            <label for="name">Nama</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                placeholder="Masukkan nama"
                required
                autofocus
            >
        </div>

        {{-- Email --}}
        <div class="input-group">
            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="Masukkan email"
                required
            >
        </div>

        {{-- Password --}}
        <div class="input-group">
            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Buat password"
                required
            >
        </div>

        {{-- Konfirmasi Password --}}
        <div class="input-group">
            <label for="password_confirmation">Konfirmasi Password</label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                placeholder="Ulangi password"
                required
            >
        </div>

        <button type="submit">Daftar</button>

        <p style="margin-top:12px;">
            Sudah punya akun? <a href="{{ route('login') }}">Login</a>
        </p>
    </form>
</div>

</body>
</html>
