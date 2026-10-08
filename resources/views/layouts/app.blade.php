<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'MoneyFlow') }} — Pengelolaan Surplus & Investasi Keahlian</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            }
            .font-mono-num {
                font-family: 'JetBrains Mono', monospace;
                font-variant-numeric: tabular-nums;
            }
        </style>
    </head>
    <body class="min-h-full bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 antialiased selection:bg-emerald-500 selection:text-white">
        <!-- Ambient background gradients -->
        <div class="fixed inset-0 -z-10 pointer-events-none overflow-hidden opacity-40 dark:opacity-20">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-emerald-400 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 -left-40 w-96 h-96 bg-indigo-500 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 right-1/3 w-96 h-96 bg-teal-400 rounded-full blur-3xl"></div>
        </div>

        <div class="min-h-screen flex flex-col">
            @include('layouts.navigation')

            <!-- Flash Notifications -->
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition.duration.300ms
                     class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
                    <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-800 dark:text-emerald-300 flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-lg bg-emerald-500/20 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <p class="text-sm font-medium">{{ session('success') }}</p>
                        </div>
                        <button @click="show = false" class="text-emerald-600/70 hover:text-emerald-700 dark:hover:text-emerald-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" x-transition.duration.300ms
                     class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
                    <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-800 dark:text-rose-300 flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-lg bg-rose-500/20 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <p class="text-sm font-medium">{{ session('error') }}</p>
                        </div>
                        <button @click="show = false" class="text-rose-600/70 hover:text-rose-700 dark:hover:text-rose-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div x-data="{ show: true }" x-show="show" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
                    <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-800 dark:text-rose-300">
                        <p class="text-xs font-bold uppercase tracking-wider mb-1">Terdapat kesalahan pengisian data:</p>
                        <ul class="list-disc list-inside text-xs space-y-0.5">
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
            <main class="flex-1 pb-16">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="mt-auto border-t border-slate-200 dark:border-slate-800/80 bg-white/50 dark:bg-slate-900/40 backdrop-blur py-6 text-center text-xs text-slate-600 dark:text-slate-400">
                <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-slate-700 dark:text-slate-300">MoneyFlow</span>
                        <span class="text-slate-400 dark:text-slate-500">|</span>
                        <span>Alokasi Surplus: Finansial & Investasi Leher ke Atas</span>
                    </div>
                    <div>
                        <span>Built with Laravel 13 & Tailwind CSS</span>
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
