<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Menggunakan CDN Bootstrap agar praktis tanpa error Vite -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <link rel="stylesheet" href="{{ asset('bootstrap-5.3.8-dist/css/bootstrap.min.css') }}">
</head>

<body class="bg-light d-flex flex-column min-vh-100">
    <header>
        @include('partials.header')
    </header>

    <!-- TAMBAHKAN d-flex flex-column flex-grow-1 DI SINI -->
    <main class="d-flex flex-column flex-grow-1 py-4">
        @yield('content')
    </main>

    @include('partials.footer')
    
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> --}}
    <script src="{{ asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>