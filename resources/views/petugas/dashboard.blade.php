<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMPEDA</title>

    {{-- Kalau kamu punya CSS sendiri taruh di public/css/auth.css --}}
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>

<body>

    <div class="container">
        <h1>Dashboard Petugas</h1>
        <p>Halo, {{ auth()->user()->name }} ({{ auth()->user()->role }})</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>


</body>

</html>
