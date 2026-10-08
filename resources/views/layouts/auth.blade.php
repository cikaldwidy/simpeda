<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name'))</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo_TAhead.png') }}"
        class="h-10 w-auto rounded-full object-cover">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Google Font tambahan -->
    <link href="https://fonts.googleapis.com/css2?family=Hind:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Allura&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Google reCAPTCHA v2 -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="font-sans antialiased min-h-screen bg-gradient-to-b from-gray-900 via-gray-800 to-gray-900">
    <!-- Page Loader -->
    <div id="page-loader" class="fixed inset-0 bg-white z-50 flex items-center justify-center">
        <div class="animate-spin rounded-full h-32 w-32 border-b-2 border-orange-500"></div>
    </div>

    @yield('content')

    <script>
    document.addEventListener('click', function(event) {
        const button = event.target.closest('.password-toggle');
        if (!button) return;

        const inputId = button.getAttribute('data-target');
        const input = document.getElementById(inputId);
        if (!input) return;

        const isPassword = input.getAttribute('type') === 'password';
        input.setAttribute('type', isPassword ? 'text' : 'password');

        const icon = button.querySelector('i');
        if (icon) {
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        }
    });
    </script>

    @stack('scripts')
</body>

</html>