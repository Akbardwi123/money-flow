<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'MoneyFlow') }} — Surplus & Investment Hub</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-full bg-[#070a13] text-slate-100 antialiased selection:bg-emerald-500 selection:text-black">
        <!-- Ambient Background Glow Mesh -->
        <div class="fixed inset-0 -z-10 pointer-events-none overflow-hidden">
            <div class="absolute -top-40 right-[-10%] w-[600px] h-[600px] bg-emerald-500/10 rounded-full blur-[140px]"></div>
            <div class="absolute top-[30%] -left-40 w-[600px] h-[600px] bg-cyan-500/10 rounded-full blur-[140px]"></div>
            <div class="absolute -bottom-40 right-[20%] w-[700px] h-[700px] bg-indigo-500/10 rounded-full blur-[160px]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(#ffffff08_1px,transparent_1px)] [background-size:24px_24px] opacity-40"></div>
        </div>

        <div class="min-h-screen flex flex-col">
            @include('layouts.navigation')

            <!-- Flash Notifications -->
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition.duration.300ms
                     class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-5 w-full">
                    <div class="p-4 rounded-2xl bg-emerald-950/60 border border-emerald-500/30 text-emerald-200 flex items-center justify-between shadow-glow-emerald backdrop-blur-xl">
                        <div class="flex items-center gap-3">
                            <div class="h-9 w-9 rounded-xl bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center shrink-0 text-emerald-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <p class="text-sm font-semibold tracking-wide">{{ session('success') }}</p>
                        </div>
                        <button @click="show = false" class="text-emerald-400/70 hover:text-emerald-300 p-1 rounded-lg hover:bg-emerald-500/10 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" x-transition.duration.300ms
                     class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-5 w-full">
                    <div class="p-4 rounded-2xl bg-rose-950/60 border border-rose-500/30 text-rose-200 flex items-center justify-between shadow-lg shadow-rose-950/50 backdrop-blur-xl">
                        <div class="flex items-center gap-3">
                            <div class="h-9 w-9 rounded-xl bg-rose-500/20 border border-rose-500/30 flex items-center justify-center shrink-0 text-rose-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <p class="text-sm font-semibold tracking-wide">{{ session('error') }}</p>
                        </div>
                        <button @click="show = false" class="text-rose-400/70 hover:text-rose-300 p-1 rounded-lg hover:bg-rose-500/10 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div x-data="{ show: true }" x-show="show" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-5 w-full">
                    <div class="p-4 rounded-2xl bg-rose-950/50 border border-rose-500/25 text-rose-200 backdrop-blur-xl">
                        <p class="text-xs font-bold uppercase tracking-wider mb-2 flex items-center gap-1.5 text-rose-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Terdapat kesalahan input:</span>
                        </p>
                        <ul class="list-disc list-inside text-xs space-y-1 text-rose-300/90 pl-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Page Heading -->
            @isset($header)
                <header class="py-6">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-1 pb-20">
                {{ $slot }}
            </main>

            <!-- Modern Sleek Footer -->
            <footer class="mt-auto border-t border-slate-800/80 bg-[#070a13]/80 backdrop-blur-xl py-6 text-xs text-slate-500">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="h-6 w-6 rounded-lg bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </div>
                        <span class="font-bold text-slate-300">MoneyFlow</span>
                        <span class="text-slate-700">|</span>
                        <span class="text-slate-400">Smart Surplus & Skill Investment Engine</span>
                    </div>
                    <div class="flex items-center gap-2 text-slate-400">
                        <span class="inline-block h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>Engine Active & Optimized</span>
                    </div>
                </div>
            </footer>
        </div>

        <script>
            // Helper function to format IDR currency dynamically in Alpine components
            window.formatIDR = function(value) {
                if (value === null || value === undefined || isNaN(value)) return 'Rp 0';
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    maximumFractionDigits: 0
                }).format(value);
            };
        </script>
    </body>
</html>
