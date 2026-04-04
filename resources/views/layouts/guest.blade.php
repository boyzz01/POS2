<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Masuk') — Supplier MBG</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
</head>
<body class="bg-gradient-to-br from-sky-50 via-white to-sky-100 min-h-dvh flex flex-col items-center justify-center p-4 antialiased">

    <div class="w-full max-w-md">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center justify-center gap-2.5 mb-8">
            <div class="w-10 h-10 bg-sky-600 rounded-xl flex items-center justify-center shadow-md">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div>
                <p class="font-bold text-sky-700 text-xl leading-tight">Supplier MBG</p>
                <p class="text-xs text-gray-500">Makan Bergizi Gratis</p>
            </div>
        </a>

        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
            @yield('content')
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">
            © {{ date('Y') }} Supplier MBG — Platform pengadaan kebutuhan MBG
        </p>
    </div>

</body>
</html>
