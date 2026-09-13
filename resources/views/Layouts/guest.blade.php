<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Cafe POS') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-[#f4eee5] font-['DM_Sans'] text-[#2d211b] antialiased">
        <style>
            .auth-shell { background: radial-gradient(circle at 8% 12%, rgba(211, 151, 86, .28), transparent 28%), radial-gradient(circle at 90% 88%, rgba(173, 103, 54, .14), transparent 30%), #f4eee5; }
            .auth-card { background: #ffffff; box-shadow: 0 12px 24px rgba(74, 47, 28, .08), 0 28px 70px rgba(74, 47, 28, .12); }
            .coffee-mark { background: linear-gradient(145deg, #b86d3d, #713a22); box-shadow: 0 10px 20px rgba(141, 75, 37, .24); }
            .auth-input { border-color: #ded8d0; background: #ffffff; color: #2d211b; transition: border-color 160ms ease, box-shadow 160ms ease; }
            .auth-input:focus { border-color: #b86d3d; box-shadow: 0 0 0 3px rgba(184, 109, 61, .14); outline: none; }
            .auth-submit { background: #a85d32; transition: transform 160ms ease, box-shadow 160ms ease, background-color 160ms ease; }
            .auth-submit:hover { background: #864522; box-shadow: 0 10px 20px rgba(141, 75, 37, .2); transform: translateY(-1px); }
        </style>
        </style>
        <div class="auth-shell flex min-h-screen flex-col items-center justify-center px-4 py-10 sm:px-6">
            <div class="mb-6 text-center">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3 rounded-full border border-[#dfcdbb] bg-[#fffaf4]/75 px-4 py-2.5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <span class="coffee-mark flex h-10 w-10 items-center justify-center rounded-2xl text-xl text-white">☕</span>
                    <span class="text-left"><span class="block font-['Playfair_Display'] text-xl font-bold leading-none text-[#3a271d]">Cafe POS</span><span class="mt-1 block text-[10px] font-bold uppercase tracking-[.2em] text-[#a56842]">Daily service</span></span>
                </a>
            </div>
            <main class="auth-card w-full max-w-md rounded-2xl border border-[#e7e1da] p-6 sm:p-10">
                {{ $slot }}
            </main>
            <p class="mt-6 text-center text-xs font-medium tracking-wide text-[#927d6b]">Fresh starts here · Open today</p>
        </div>
    </body>
</html>
