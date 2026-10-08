<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'MoneyFlow') }} — Manajemen Keuangan & Surplus</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

        <!-- Alpine x-cloak prevent Flash of Unstyled Content -->
        <style>
            [x-cloak] { display: none !important; }
        </style>

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-full bg-slate-50 dark:bg-[#0b0f17] text-slate-900 dark:text-slate-100 antialiased selection:bg-slate-900 selection:text-white dark:selection:bg-slate-100 dark:selection:text-slate-900">
        <div class="min-h-screen flex flex-col">
            @include('layouts.navigation')

            <!-- Flash Notifications -->
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition.duration.200ms
                     class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-5 w-full">
                    <div class="p-3.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 text-emerald-900 dark:text-emerald-200 flex items-center justify-between text-xs font-medium shadow-sm">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" class="text-emerald-700/60 hover:text-emerald-900 dark:hover:text-emerald-100">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" x-transition.duration.200ms
                     class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-5 w-full">
                    <div class="p-3.5 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60 text-rose-900 dark:text-rose-200 flex items-center justify-between text-xs font-medium shadow-sm">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-rose-600 dark:text-rose-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button @click="show = false" class="text-rose-700/60 hover:text-rose-900 dark:hover:text-rose-100">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div x-data="{ show: true }" x-show="show" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-5 w-full">
                    <div class="p-3.5 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60 text-rose-900 dark:text-rose-200 text-xs">
                        <p class="font-bold mb-1">Periksa kembali data yang dimasukkan:</p>
                        <ul class="list-disc list-inside space-y-0.5 text-rose-800 dark:text-rose-300">
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

            <!-- Clean Grounded Footer -->
            <footer class="mt-auto border-t border-slate-200 dark:border-slate-800/80 bg-white dark:bg-[#0e1422] py-5 text-xs text-slate-500">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <div class="flex items-center gap-2 font-medium">
                        <span class="text-slate-800 dark:text-slate-200 font-bold">MoneyFlow</span>
                        <span class="text-slate-300 dark:text-slate-700">&bull;</span>
                        <span>Sistem Manajemen Keuangan & Alokasi Surplus</span>
                    </div>
                    <div class="text-slate-400">
                        <span>Aman &bull; Mandiri &bull; Terukur</span>
                    </div>
                </div>
            </footer>
        </div>

        <script>
            // Helper function to format IDR currency dynamically
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
