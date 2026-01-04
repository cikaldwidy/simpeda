<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ config('app.name') }}</title>

    {{-- Kalau kamu punya CSS sendiri taruh di public/css/auth.css --}}
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>

<div class="login-container">
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <h2>Login</h2>

        {{-- Error umum --}}
        @if ($errors->any())
            <div class="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

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
                autofocus
            >
        </div>

        {{-- Password --}}
        <div class="input-group">
            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Masukkan password"
                required
            >
        </div>

        {{-- Remember me (opsional) --}}
        <div class="input-group" style="display:flex; gap:8px; align-items:center;">
            <input type="checkbox" id="remember" name="remember">
            <label for="remember" style="margin:0;">Ingat saya</label>
        </div>

        <button type="submit">Login</button>

        <p style="margin-top:12px;">
            Belum punya akun? <a href="{{ route('register') }}">Daftar</a>
        </p>
    </form>
</div>

</body>
</html>
