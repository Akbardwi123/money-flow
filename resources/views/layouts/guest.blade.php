<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'MoneyFlow') }} — Masuk & Otentikasi</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-full bg-[#070a13] text-slate-100 antialiased selection:bg-emerald-500 selection:text-black relative flex items-center justify-center p-4">
        <!-- Ambient Background Lights -->
        <div class="fixed inset-0 -z-10 pointer-events-none overflow-hidden">
            <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-gradient-to-tr from-emerald-500/15 via-teal-500/15 to-indigo-500/15 rounded-full blur-[140px]"></div>
            <div class="absolute -bottom-20 -right-20 w-[450px] h-[450px] bg-cyan-500/10 rounded-full blur-[130px]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(#ffffff08_1px,transparent_1px)] [background-size:24px_24px] opacity-40"></div>
        </div>

        <div class="w-full max-w-md my-8">
            <!-- Brand Logo Header -->
            <div class="flex flex-col items-center justify-center mb-8">
                <a href="/" class="group flex items-center gap-3 transition-transform hover:scale-105 duration-200">
                    <div class="h-12 w-12 rounded-2xl bg-gradient-to-tr from-emerald-500 via-teal-500 to-indigo-600 flex items-center justify-center shadow-[0_0_25px_rgba(16,185,129,0.4)] text-white">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                    <div class="flex flex-col text-left">
                        <span class="text-2xl font-black text-white tracking-tight">Money<span class="text-emerald-400">Flow</span></span>
                        <span class="text-[10px] uppercase font-bold tracking-widest text-emerald-400/90 font-mono-num">Fintech Surplus Engine</span>
                    </div>
                </a>
            </div>

            <!-- Glass Card Container -->
            <div class="fintech-card rounded-3xl p-7 sm:p-9 border border-white/10 shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-[2px] bg-gradient-to-r from-transparent via-emerald-500 to-transparent"></div>
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
