<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MoneyFlow — Pengelolaan Keuangan Pribadi & Surplus Investment Hub</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
    </style>
</head>
<body class="min-h-full bg-slate-950 text-slate-100 antialiased selection:bg-emerald-500 selection:text-white relative overflow-x-hidden">
    <!-- Ambient glow backdrops -->
    <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[700px] h-[700px] bg-gradient-to-tr from-emerald-600/30 via-teal-500/20 to-indigo-600/30 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-[600px] -left-40 w-[500px] h-[500px] bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-[900px] -right-40 w-[500px] h-[500px] bg-emerald-600/20 rounded-full blur-3xl pointer-events-none"></div>

    <!-- Navigation Header -->
    <header class="border-b border-slate-800/80 bg-slate-950/60 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-emerald-500 via-teal-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-emerald-500/25 text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
                <div>
                    <span class="text-xl font-extrabold text-white tracking-tight">Money<span class="text-emerald-400">Flow</span></span>
                    <span class="block text-[10px] uppercase font-semibold tracking-wider text-slate-400">Surplus & Skill Hub</span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-semibold text-sm transition shadow-lg shadow-emerald-500/20">
                            Buka Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl text-slate-300 hover:text-white text-sm font-medium transition">
                            Masuk
                        </a>
                        <form method="POST" action="{{ route('demo.login') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-slate-950 font-bold text-sm transition shadow-lg shadow-emerald-500/20 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Coba Demo 1-Klik</span>
                            </button>
                        </form>
                    @endauth
                @endif
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-16 text-center relative">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-emerald-500/30 bg-emerald-500/10 text-emerald-400 text-xs font-semibold mb-8">
            <span class="flex h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
            Framework Finansial Modern Berbasis Surplus
        </div>

        <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight text-white max-w-4xl mx-auto leading-tight sm:leading-none">
            Kelola Pengeluaran, Temukan Surplus, Investasikan ke <span class="bg-gradient-to-r from-emerald-400 via-teal-300 to-indigo-400 bg-clip-text text-transparent">Finansial & Skill</span>
        </h1>

        <p class="mt-6 text-lg sm:text-xl text-slate-400 max-w-2xl mx-auto font-normal leading-relaxed">
            Aplikasi pengelolaan keuangan terarah yang memisahkan pengeluaran wajib & harian, mengkalkulasi sisa saldo otomatis, serta mendistribusikan surplus ke aset finansial maupun pengembangan diri (*leher ke atas*).
        </p>

        <!-- CTA Buttons -->
        <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
            <form method="POST" action="{{ route('demo.login') }}">
                @csrf
                <button type="submit" class="px-8 py-4 rounded-xl bg-gradient-to-r from-emerald-500 via-teal-400 to-emerald-500 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-bold text-base transition shadow-xl shadow-emerald-500/30 hover:scale-105 transform flex items-center gap-3">
                    <span>Masuk ke Dashboard Demo</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </button>
            </form>
            <a href="{{ route('register') }}" class="px-7 py-4 rounded-xl border border-slate-700 hover:border-slate-500 bg-slate-900/60 hover:bg-slate-800 text-white font-medium text-base transition flex items-center gap-2">
                <span>Daftar Akun Baru</span>
            </a>
        </div>

        <!-- 5-Step System Flow Showcase -->
        <div class="mt-20 pt-10 border-t border-slate-800/80">
            <h2 class="text-xs uppercase tracking-widest font-bold text-slate-400 mb-8">Arsitektur Alur Sistem (Flow)</h2>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 text-left">
                <!-- Step 1 -->
                <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800 hover:border-emerald-500/40 transition">
                    <div class="h-10 w-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 font-bold flex items-center justify-center mb-4 text-sm">
                        01
                    </div>
                    <h3 class="font-bold text-white text-base mb-1">Input Pemasukan</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Catat arus masuk gaji pokok, freelance, dividen pasif, hingga side hustle secara terstruktur.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800 hover:border-rose-500/40 transition">
                    <div class="h-10 w-10 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 font-bold flex items-center justify-center mb-4 text-sm">
                        02
                    </div>
                    <h3 class="font-bold text-white text-base mb-1">Pencatatan Pengeluaran</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Pemisahan jelas antara kelompok <strong>Prioritas (Wajib)</strong> vs <strong>Fleksibel (Harian)</strong> dengan catatan detail.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800 hover:border-teal-500/40 transition">
                    <div class="h-10 w-10 rounded-xl bg-teal-500/10 border border-teal-500/20 text-teal-400 font-bold flex items-center justify-center mb-4 text-sm">
                        03
                    </div>
                    <h3 class="font-bold text-white text-base mb-1">Kalkulasi Surplus</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Sistem menghitung real-time <code>Surplus = Pemasukan - Pengeluaran</code> beserta rasio kesehatan dana.
                    </p>
                </div>

                <!-- Step 4 -->
                <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800 hover:border-indigo-500/40 transition">
                    <div class="h-10 w-10 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 font-bold flex items-center justify-center mb-4 text-sm">
                        04
                    </div>
                    <h3 class="font-bold text-white text-base mb-1">Hub Alokasi Investasi</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Distribusikan sisa dana ke <strong>Investasi Finansial</strong> (Saham/Crypto) & <strong>Leher ke Atas</strong> (Kelas/Sertifikasi).
                    </p>
                </div>

                <!-- Step 5 -->
                <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800 hover:border-purple-500/40 transition">
                    <div class="h-10 w-10 rounded-xl bg-purple-500/10 border border-purple-500/20 text-purple-400 font-bold flex items-center justify-center mb-4 text-sm">
                        05
                    </div>
                    <h3 class="font-bold text-white text-base mb-1">Dashboard & Laporan</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Pantau grafik portofolio aset, tracking target skill, dan riwayat mutasi dana ledger secara menyeluruh.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- The 2 Pillars of Investment Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="p-8 sm:p-12 rounded-3xl bg-gradient-to-b from-slate-900 to-slate-950 border border-slate-800">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-400">Hub Alokasi Surplus</span>
                <h2 class="text-2xl sm:text-4xl font-extrabold text-white mt-2">Dua Jalur Distribusi Surplus Anda</h2>
                <p class="text-sm text-slate-400 mt-3">Tiap rupiah surplus dialokasikan dengan intensi nyata untuk memperbesar kapasitas dan aset di masa depan.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Financial Pillar -->
                <div class="p-8 rounded-2xl bg-slate-950/80 border border-slate-800/80 relative overflow-hidden group hover:border-cyan-500/50 transition">
                    <div class="absolute -right-8 -top-8 w-36 h-36 bg-cyan-500/10 rounded-full blur-2xl group-hover:bg-cyan-500/20 transition"></div>
                    <div class="h-12 w-12 rounded-xl bg-cyan-500/10 text-cyan-400 flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">1. Investasi Finansial</h3>
                    <p class="text-sm text-slate-400 mb-6 leading-relaxed">
                        Akumulasi aset produktif untuk menciptakan pasif income dan perlindungan nilai kekayaan dari inflasi.
                    </p>
                    <ul class="space-y-3 text-xs text-slate-300">
                        <li class="flex items-center gap-2">
                            <span class="h-1.5 w-1.5 rounded-full bg-cyan-400"></span>
                            <span><strong>Saham Blue Chip & Dividen</strong> (Stockbit, Mirae, dll.)</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="h-1.5 w-1.5 rounded-full bg-cyan-400"></span>
                            <span><strong>Crypto & DCA Bitcoin</strong> (Tokocrypto, Binance, dll.)</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="h-1.5 w-1.5 rounded-full bg-cyan-400"></span>
                            <span><strong>Reksa Dana & SBN / Obligasi Negara</strong> (Bibit, Bareksa)</span>
                        </li>
                    </ul>
                </div>

                <!-- Skill / Leher ke Atas Pillar -->
                <div class="p-8 rounded-2xl bg-slate-950/80 border border-slate-800/80 relative overflow-hidden group hover:border-violet-500/50 transition">
                    <div class="absolute -right-8 -top-8 w-36 h-36 bg-violet-500/10 rounded-full blur-2xl group-hover:bg-violet-500/20 transition"></div>
                    <div class="h-12 w-12 rounded-xl bg-violet-500/10 text-violet-400 flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">2. Investasi Leher ke Atas (Skill)</h3>
                    <p class="text-sm text-slate-400 mb-6 leading-relaxed">
                        Peningkatan kapasitas dan nilai jual diri untuk melipatgandakan potensi penghasilan utama (earning power).
                    </p>
                    <ul class="space-y-3 text-xs text-slate-300">
                        <li class="flex items-center gap-2">
                            <span class="h-1.5 w-1.5 rounded-full bg-violet-400"></span>
                            <span><strong>Kursus & Lab Cybersecurity</strong> (HackTheBox, OffSec, Coursera)</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="h-1.5 w-1.5 rounded-full bg-violet-400"></span>
                            <span><strong>Sertifikasi Internasional</strong> (CompTIA, AWS, CEH, Cisco)</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="h-1.5 w-1.5 rounded-full bg-violet-400"></span>
                            <span><strong>Buku & Literatur Arsitektur / Finansial</strong> lengkap target capaian</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-slate-900 py-10 text-center text-xs text-slate-500">
        <p>© 2026 MoneyFlow. Sistem Pengelolaan Keuangan Pribadi & Surplus Allocation Hub.</p>
    </footer>
</body>
</html>
