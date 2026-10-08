<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8" x-data="dashboardData()">
        <!-- Period Filter Bar -->
        <div class="fintech-card rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="h-11 w-11 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0 shadow-[0_0_15px_rgba(16,185,129,0.15)]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h1 class="text-base font-bold text-white tracking-tight">Dashboard Portofolio & Arus Kas</h1>
                    <p class="text-xs text-slate-400">Periode Aktif: <span class="font-bold text-emerald-400">{{ $metrics['period']['label'] }}</span></p>
                </div>
            </div>

            <!-- Filter Controls -->
            <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                <select name="month" class="px-3.5 py-2 text-xs font-semibold rounded-xl fintech-input focus:outline-none">
                    <option value="">Semua Bulan</option>
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(2026, $m, 1)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>

                <select name="year" class="px-3.5 py-2 text-xs font-semibold rounded-xl fintech-input focus:outline-none">
                    @for ($y = 2024; $y <= 2027; $y++)
                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>

                <button type="submit" class="px-4 py-2 text-xs font-bold rounded-xl bg-white/10 hover:bg-white/15 border border-white/10 text-white transition flex items-center gap-1.5 shadow-sm">
                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>Terapkan</span>
                </button>

                @if($selectedMonth || $selectedYear != now()->year)
                    <a href="{{ route('dashboard') }}" class="px-3 py-2 text-xs font-medium rounded-xl text-slate-400 hover:text-white hover:bg-white/5 transition">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- HERO SURPLUS HUB CARD -->
        <div class="relative overflow-hidden rounded-3xl fintech-card-glow p-6 sm:p-9 text-white">
            <div class="absolute -right-20 -top-20 w-96 h-96 bg-emerald-500/15 rounded-full blur-[120px] pointer-events-none"></div>
            <div class="absolute -left-20 -bottom-20 w-96 h-96 bg-cyan-500/15 rounded-full blur-[120px] pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 pb-6 border-b border-white/10">
                <div>
                    <div class="flex items-center gap-2.5 mb-2.5">
                        <span class="text-[11px] font-extrabold uppercase tracking-widest text-emerald-400 font-mono-num">Real-Time Surplus Engine</span>
                        @if($metrics['status'] === 'healthy')
                            <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-[11px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 shadow-[0_0_12px_rgba(16,185,129,0.25)]">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Surplus Positif
                            </span>
                        @elseif($metrics['status'] === 'deficit')
                            <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-[11px] font-bold bg-rose-500/20 text-rose-300 border border-rose-500/40 shadow-[0_0_12px_rgba(244,63,94,0.25)]">
                                <span class="h-1.5 w-1.5 rounded-full bg-rose-400 animate-pulse"></span> Defisit Dana
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-[11px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/40 shadow-[0_0_12px_rgba(245,158,11,0.25)]">
                                <span class="h-1.5 w-1.5 rounded-full bg-amber-400 animate-pulse"></span> Saldo Seimbang
                            </span>
                        @endif
                    </div>
                    <div class="flex items-baseline gap-3">
                        <h2 class="text-3xl sm:text-5xl font-black tracking-tight font-mono-num text-white drop-shadow-[0_2px_10px_rgba(0,0,0,0.5)]">
                            Rp {{ number_format($metrics['gross_surplus'], 0, ',', '.') }}
                        </h2>
                    </div>
                    <p class="text-xs text-slate-300 mt-2 font-medium">
                        Sisa Dana Bersih = Total Pemasukan (<span class="text-emerald-400 font-semibold">Rp {{ number_format($metrics['total_income'], 0, ',', '.') }}</span>) − Total Pengeluaran (<span class="text-rose-400 font-semibold">Rp {{ number_format($metrics['total_expenses'], 0, ',', '.') }}</span>)
                    </p>
                </div>

                <!-- Surplus Allocation Status Box -->
                <div class="bg-[#070b16]/90 border border-white/10 rounded-2xl p-5 min-w-[290px] w-full lg:w-auto shadow-2xl backdrop-blur-xl">
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">Status Sisa Surplus Siap Alokasi</p>
                    <div class="flex items-baseline justify-between gap-4">
                        <span class="text-2xl font-black font-mono-num {{ $metrics['remaining_surplus'] >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                            Rp {{ number_format($metrics['remaining_surplus'], 0, ',', '.') }}
                        </span>
                        <a href="{{ route('allocations.index') }}" class="px-3 py-1.5 rounded-xl bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-white text-xs font-bold transition shadow-md shadow-cyan-500/20 flex items-center gap-1.5">
                            <span>Alokasikan</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-2">
                        Telah teralokasi: <span class="text-white font-bold">Rp {{ number_format($metrics['total_allocated'], 0, ',', '.') }}</span>
                        ({{ $metrics['financial_allocation_percent'] + $metrics['skill_allocation_percent'] }}% dari surplus)
                    </p>
                </div>
            </div>

            <!-- Surplus Distribution Progress Bar -->
            <div class="relative z-10 mt-6 pt-2">
                <div class="flex justify-between text-xs font-semibold text-slate-300 mb-2.5">
                    <span class="text-white">Komposisi Distribusi Surplus</span>
                    <span class="text-emerald-400 font-mono-num">{{ $metrics['surplus_rate'] }}% Rasio Surplus dari Pemasukan</span>
                </div>
                <div class="h-3.5 w-full rounded-full bg-[#070b16] p-0.5 border border-white/10 overflow-hidden flex shadow-inner">
                    <!-- Financial Investment Bar -->
                    <div class="bg-gradient-to-r from-cyan-500 to-cyan-400 h-full rounded-full transition-all duration-700 shadow-[0_0_10px_rgba(6,182,212,0.5)]" :style="{ width: '{{ min(100, $metrics['financial_allocation_percent']) }}%' }" title="Investasi Finansial: {{ $metrics['financial_allocation_percent'] }}%"></div>
                    <!-- Skill / Leher ke Atas Bar -->
                    <div class="bg-gradient-to-r from-violet-500 to-indigo-400 h-full rounded-full transition-all duration-700 shadow-[0_0_10px_rgba(139,92,246,0.5)]" :style="{ width: '{{ min(100 - $metrics['financial_allocation_percent'], $metrics['skill_allocation_percent']) }}%' }" title="Investasi Leher ke Atas: {{ $metrics['skill_allocation_percent'] }}%"></div>
                    <!-- Unallocated Bar -->
                    <div class="bg-emerald-500/40 h-full rounded-full transition-all duration-700" :style="{ width: '{{ max(0, 100 - $metrics['financial_allocation_percent'] - $metrics['skill_allocation_percent']) }}%' }" title="Belum Dialokasikan"></div>
                </div>

                <div class="mt-4 flex flex-wrap items-center gap-4 sm:gap-6 text-xs">
                    <div class="flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-cyan-400 shadow-[0_0_6px_rgba(34,211,238,0.8)]"></span>
                        <span class="text-slate-300">Investasi Finansial:</span>
                        <span class="font-bold text-white font-mono-num">Rp {{ number_format($metrics['financial_investment'], 0, ',', '.') }} ({{ $metrics['financial_allocation_percent'] }}%)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-violet-400 shadow-[0_0_6px_rgba(167,139,250,0.8)]"></span>
                        <span class="text-slate-300">Investasi Leher ke Atas (Skill):</span>
                        <span class="font-bold text-white font-mono-num">Rp {{ number_format($metrics['skill_investment'], 0, ',', '.') }} ({{ $metrics['skill_allocation_percent'] }}%)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-400 shadow-[0_0_6px_rgba(52,211,153,0.8)]"></span>
                        <span class="text-slate-300">Cadangan Tersisa:</span>
                        <span class="font-bold text-white font-mono-num">Rp {{ number_format(max(0, $metrics['remaining_surplus']), 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4 KPI SUMMARY CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1: Total Pemasukan -->
            <div class="fintech-card rounded-2xl p-5 relative group hover:border-emerald-500/40 hover:-translate-y-1 transition duration-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Pemasukan</span>
                    <div class="h-9 w-9 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-white font-mono-num">
                    Rp {{ number_format($metrics['total_income'], 0, ',', '.') }}
                </div>
                <div class="mt-2.5 text-xs text-slate-400 flex items-center justify-between">
                    <span>Arus masuk dana</span>
                    <a href="{{ route('incomes.index') }}" class="text-emerald-400 hover:text-emerald-300 font-bold transition flex items-center gap-1">Kelola &rarr;</a>
                </div>
            </div>

            <!-- Card 2: Pengeluaran Prioritas (Wajib) -->
            <div class="fintech-card rounded-2xl p-5 relative group hover:border-rose-500/40 hover:-translate-y-1 transition duration-200">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-1.5">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Pengeluaran Prioritas</span>
                        <span class="px-1.5 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider bg-rose-500/20 text-rose-300 border border-rose-500/30">Wajib</span>
                    </div>
                    <div class="h-9 w-9 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-rose-400 font-mono-num">
                    Rp {{ number_format($metrics['priority_expenses'], 0, ',', '.') }}
                </div>
                <div class="mt-2.5 text-xs text-slate-400 flex items-center justify-between">
                    <span>Rasio: <strong class="text-slate-200 font-mono-num">{{ $metrics['priority_ratio'] }}%</strong></span>
                    <a href="{{ route('expenses.index', ['type' => 'priority']) }}" class="text-rose-400 hover:text-rose-300 font-bold transition flex items-center gap-1">Detail &rarr;</a>
                </div>
            </div>

            <!-- Card 3: Pengeluaran Fleksibel (Harian) -->
            <div class="fintech-card rounded-2xl p-5 relative group hover:border-amber-500/40 hover:-translate-y-1 transition duration-200">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-1.5">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Pengeluaran Fleksibel</span>
                        <span class="px-1.5 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider bg-amber-500/20 text-amber-300 border border-amber-500/30">Harian</span>
                    </div>
                    <div class="h-9 w-9 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-amber-400 font-mono-num">
                    Rp {{ number_format($metrics['flexible_expenses'], 0, ',', '.') }}
                </div>
                <div class="mt-2.5 text-xs text-slate-400 flex items-center justify-between">
                    <span>Rasio: <strong class="text-slate-200 font-mono-num">{{ $metrics['flexible_ratio'] }}%</strong></span>
                    <a href="{{ route('expenses.index', ['type' => 'flexible']) }}" class="text-amber-400 hover:text-amber-300 font-bold transition flex items-center gap-1">Detail &rarr;</a>
                </div>
            </div>

            <!-- Card 4: Total Alokasi Investasi & Skill -->
            <div class="fintech-card rounded-2xl p-5 relative group hover:border-cyan-500/40 hover:-translate-y-1 transition duration-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Investasi Dialokasikan</span>
                    <div class="h-9 w-9 rounded-xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-cyan-400 font-mono-num">
                    Rp {{ number_format($metrics['total_allocated'], 0, ',', '.') }}
                </div>
                <div class="mt-2.5 text-xs text-slate-400 flex items-center justify-between">
                    <span>Finansial & Skill</span>
                    <a href="{{ route('allocations.index') }}" class="text-cyan-400 hover:text-cyan-300 font-bold transition flex items-center gap-1">Hub Alokasi &rarr;</a>
                </div>
            </div>
        </div>

        <!-- CHARTS SECTION -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Chart 1: Donut Alokasi Surplus -->
            <div class="fintech-card rounded-3xl p-6 sm:p-7">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-sm font-bold text-white tracking-tight">Alokasi Surplus Dana</h2>
                        <p class="text-xs text-slate-400">Distribusi surplus ke instrumen masa depan</p>
                    </div>
                    <span class="h-2 w-2 rounded-full bg-cyan-400 shadow-[0_0_8px_rgba(6,182,212,0.8)]"></span>
                </div>
                <div class="h-56 relative flex items-center justify-center">
                    <canvas id="surplusAllocationChart"></canvas>
                </div>
                <div class="mt-4 pt-4 border-t border-white/10 space-y-2.5 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-cyan-400"></span> Finansial (Saham, SBN)</span>
                        <span class="font-bold font-mono-num text-white">Rp {{ number_format($metrics['financial_investment'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-violet-400"></span> Skill (Buku, Kursus, Lab)</span>
                        <span class="font-bold font-mono-num text-white">Rp {{ number_format($metrics['skill_investment'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-emerald-400"></span> Sisa Cadangan</span>
                        <span class="font-bold font-mono-num text-emerald-400">Rp {{ number_format(max(0, $metrics['remaining_surplus']), 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Chart 2: Donut Komposisi Pengeluaran (Prioritas vs Fleksibel) -->
            <div class="fintech-card rounded-3xl p-6 sm:p-7">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-sm font-bold text-white tracking-tight">Komposisi Pengeluaran</h2>
                        <p class="text-xs text-slate-400">Perbandingan Wajib vs Fleksibel</p>
                    </div>
                    <span class="h-2 w-2 rounded-full bg-rose-400 shadow-[0_0_8px_rgba(244,63,94,0.8)]"></span>
                </div>
                <div class="h-56 relative flex items-center justify-center">
                    <canvas id="expenseCompositionChart"></canvas>
                </div>
                <div class="mt-4 pt-4 border-t border-white/10 space-y-2.5 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-rose-400"></span> Prioritas (Sewa, Tagihan)</span>
                        <span class="font-bold font-mono-num text-white">Rp {{ number_format($metrics['priority_expenses'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-amber-400"></span> Fleksibel (Nongkrong, Hobi)</span>
                        <span class="font-bold font-mono-num text-white">Rp {{ number_format($metrics['flexible_expenses'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between pt-1 border-t border-dashed border-white/10">
                        <span class="font-semibold text-slate-400">Total Pengeluaran</span>
                        <span class="font-black font-mono-num text-rose-400">Rp {{ number_format($metrics['total_expenses'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Chart 3: Tren Arus Kas Bulanan -->
            <div class="fintech-card rounded-3xl p-6 sm:p-7 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="text-sm font-bold text-white tracking-tight">Tren Arus Kas ({{ $selectedYear ?? now()->year }})</h2>
                            <p class="text-xs text-slate-400">Pergerakan Masuk vs Keluar per Bulan</p>
                        </div>
                        <span class="h-2 w-2 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(16,185,129,0.8)]"></span>
                    </div>
                    <div class="h-56 relative">
                        <canvas id="cashflowTrendChart"></canvas>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-white/10 text-xs text-slate-400 flex items-center justify-between">
                    <span>Grafik tahunan lengkap</span>
                    <a href="{{ route('reports.index') }}" class="text-cyan-400 hover:text-cyan-300 font-bold transition flex items-center gap-1">Lihat Laporan &rarr;</a>
                </div>
            </div>
        </div>

        <!-- QUICK ACTION BAR -->
        <div class="fintech-card rounded-2xl p-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-sm font-bold text-white">Aksi Cepat Transaksi</h2>
                <p class="text-xs text-slate-400 mt-0.5">Catat pemasukan, kelompokkan pengeluaran, atau alokasikan sisa dana surplus.</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('incomes.index') }}" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-slate-950 font-bold text-xs uppercase tracking-wider transition shadow-[0_0_15px_rgba(16,185,129,0.3)] flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Catat Pemasukan</span>
                </a>
                <a href="{{ route('expenses.index') }}" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-400 hover:to-pink-400 text-white font-bold text-xs uppercase tracking-wider transition shadow-[0_0_15px_rgba(244,63,94,0.3)] flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Catat Pengeluaran</span>
                </a>
                <a href="{{ route('allocations.index') }}" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-white font-bold text-xs uppercase tracking-wider transition shadow-[0_0_15px_rgba(6,182,212,0.3)] flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span>+ Alokasi Surplus</span>
                </a>
            </div>
        </div>

        <!-- RECENT ACTIVITY SPLIT -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Column 1: Mutasi Pengeluaran Terkini -->
            <div class="fintech-card rounded-3xl p-6 sm:p-7">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-sm font-bold text-white tracking-tight">Pengeluaran Terkini</h2>
                        <p class="text-xs text-slate-400">Prioritas (Wajib) & Fleksibel (Harian)</p>
                    </div>
                    <a href="{{ route('expenses.index') }}" class="text-xs font-bold text-emerald-400 hover:text-emerald-300 transition">Lihat Semua &rarr;</a>
                </div>

                <div class="space-y-2.5">
                    @forelse($recentExpenses as $exp)
                        <div class="p-3.5 rounded-2xl bg-[#090e1c]/80 border border-white/5 hover:border-white/10 flex items-center justify-between gap-3 transition">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-xl flex items-center justify-center shrink-0 {{ $exp->isPriority() ? 'bg-rose-500/15 text-rose-400 border border-rose-500/25' : 'bg-amber-500/15 text-amber-400 border border-amber-500/25' }}">
                                    @if($exp->isPriority())
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    @endif
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h4 class="text-xs font-bold text-white">{{ $exp->title }}</h4>
                                        <span class="px-1.5 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider {{ $exp->isPriority() ? 'bg-rose-500/20 text-rose-300 border border-rose-500/30' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30' }}">
                                            {{ $exp->isPriority() ? 'Prioritas' : 'Fleksibel' }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-slate-400 truncate max-w-[180px] sm:max-w-xs">{{ $exp->category }} &bull; {{ $exp->notes ?? 'Tidak ada catatan' }}</p>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-xs font-bold font-mono-num text-rose-400">
                                    -Rp {{ number_format($exp->amount, 0, ',', '.') }}
                                </span>
                                <p class="text-[10px] text-slate-500 font-mono-num">{{ \Carbon\Carbon::parse($exp->date)->translatedFormat('d M') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-xs text-slate-500">
                            Belum ada pengeluaran tercatat pada periode ini.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Column 2: Portofolio Alokasi Terkini -->
            <div class="fintech-card rounded-3xl p-6 sm:p-7">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-sm font-bold text-white tracking-tight">Alokasi Investasi Terkini</h2>
                        <p class="text-xs text-slate-400">Finansial & Investasi Leher ke Atas (Skill)</p>
                    </div>
                    <a href="{{ route('allocations.index') }}" class="text-xs font-bold text-cyan-400 hover:text-cyan-300 transition">Buka Hub Alokasi &rarr;</a>
                </div>

                <div class="space-y-2.5">
                    @forelse($financialAllocations->concat($skillAllocations)->sortByDesc('date')->take(6) as $alloc)
                        <div class="p-3.5 rounded-2xl bg-[#090e1c]/80 border border-white/5 hover:border-white/10 flex items-center justify-between gap-3 transition">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-xl flex items-center justify-center shrink-0 {{ $alloc->isFinancial() ? 'bg-cyan-500/15 text-cyan-400 border border-cyan-500/25' : 'bg-violet-500/15 text-violet-400 border border-violet-500/25' }}">
                                    @if($alloc->isFinancial())
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    @endif
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h4 class="text-xs font-bold text-white">{{ $alloc->title }}</h4>
                                        <span class="px-1.5 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider {{ $alloc->isFinancial() ? 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/30' : 'bg-violet-500/20 text-violet-300 border border-violet-500/30' }}">
                                            {{ $alloc->isFinancial() ? 'Finansial' : 'Leher ke Atas' }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-slate-400 truncate max-w-[180px] sm:max-w-xs">{{ $alloc->category }} @if($alloc->platform) &bull; {{ $alloc->platform }} @endif</p>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-xs font-bold font-mono-num {{ $alloc->isFinancial() ? 'text-cyan-400' : 'text-violet-400' }}">
                                    Rp {{ number_format($alloc->amount, 0, ',', '.') }}
                                </span>
                                <p class="text-[10px] text-slate-500 font-mono-num">{{ \Carbon\Carbon::parse($alloc->date)->translatedFormat('d M') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-xs text-slate-500">
                            Belum ada alokasi surplus tercatat pada periode ini.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js JSON Data Carrier -->
    <script id="dashboard-chart-data" type="application/json">
        {!! json_encode([
            'financial' => (float) $metrics['financial_investment'],
            'skill' => (float) $metrics['skill_investment'],
            'remaining' => (float) max(0, $metrics['remaining_surplus']),
            'priority' => (float) $metrics['priority_expenses'],
            'flexible' => (float) $metrics['flexible_expenses'],
            'months' => array_column($trends, 'month_name'),
            'incomes' => array_map('floatval', array_column($trends, 'income')),
            'expenses' => array_map('floatval', array_column($trends, 'expenses')),
            'surplus' => array_map('floatval', array_column($trends, 'surplus')),
        ]) !!}
    </script>

    <!-- Chart.js Initialization Script -->
    <script>
        function dashboardData() {
            return {
                init() {
                    const dataEl = document.getElementById('dashboard-chart-data');
                    const chartData = dataEl ? JSON.parse(dataEl.textContent) : null;
                    if (!chartData) return;

                    this.initSurplusAllocationChart(chartData);
                    this.initExpenseCompositionChart(chartData);
                    this.initCashflowTrendChart(chartData);
                },
                initSurplusAllocationChart(chartData) {
                    const ctx = document.getElementById('surplusAllocationChart');
                    if (!ctx) return;

                    const financial = chartData.financial;
                    const skill = chartData.skill;
                    const remaining = chartData.remaining;
                    const hasData = (financial + skill + remaining) > 0;

                    new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Investasi Finansial', 'Investasi Skill (Leher ke Atas)', 'Sisa Cadangan'],
                            datasets: [{
                                data: hasData ? [financial, skill, remaining] : [1],
                                backgroundColor: hasData ? ['#06b6d4', '#8b5cf6', '#10b981'] : ['#1e293b'],
                                borderWidth: 0,
                                hoverOffset: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: '#0c1220',
                                    titleColor: '#ffffff',
                                    bodyColor: '#cbd5e1',
                                    borderColor: 'rgba(255,255,255,0.1)',
                                    borderWidth: 1,
                                    padding: 10,
                                    callbacks: {
                                        label: function(context) {
                                            if (!hasData) return 'Belum ada data surplus';
                                            return context.label + ': ' + window.formatIDR(context.parsed);
                                        }
                                    }
                                }
                            },
                            cutout: '74%'
                        }
                    });
                },
                initExpenseCompositionChart(chartData) {
                    const ctx = document.getElementById('expenseCompositionChart');
                    if (!ctx) return;

                    const priority = chartData.priority;
                    const flexible = chartData.flexible;
                    const hasData = (priority + flexible) > 0;

                    new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Prioritas (Wajib)', 'Fleksibel (Harian)'],
                            datasets: [{
                                data: hasData ? [priority, flexible] : [1],
                                backgroundColor: hasData ? ['#f43f5e', '#f59e0b'] : ['#1e293b'],
                                borderWidth: 0,
                                hoverOffset: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: '#0c1220',
                                    titleColor: '#ffffff',
                                    bodyColor: '#cbd5e1',
                                    borderColor: 'rgba(255,255,255,0.1)',
                                    borderWidth: 1,
                                    padding: 10,
                                    callbacks: {
                                        label: function(context) {
                                            if (!hasData) return 'Belum ada data pengeluaran';
                                            return context.label + ': ' + window.formatIDR(context.parsed);
                                        }
                                    }
                                }
                            },
                            cutout: '74%'
                        }
                    });
                },
                initCashflowTrendChart(chartData) {
                    const ctx = document.getElementById('cashflowTrendChart');
                    if (!ctx) return;

                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: chartData.months,
                            datasets: [
                                {
                                    label: 'Pemasukan',
                                    data: chartData.incomes,
                                    backgroundColor: '#10b981',
                                    borderRadius: 6
                                },
                                {
                                    label: 'Pengeluaran',
                                    data: chartData.expenses,
                                    backgroundColor: '#f43f5e',
                                    borderRadius: 6
                                },
                                {
                                    label: 'Surplus',
                                    data: chartData.surplus,
                                    backgroundColor: '#06b6d4',
                                    borderRadius: 6
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                x: {
                                    grid: { display: false },
                                    ticks: { font: { size: 10 }, color: '#64748b' }
                                },
                                y: {
                                    grid: { color: 'rgba(255, 255, 255, 0.05)' },
                                    ticks: {
                                        font: { size: 9 },
                                        color: '#64748b',
                                        callback: function(value) {
                                            return 'Rp ' + (value / 1000000) + 'jt';
                                        }
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: { boxWidth: 10, font: { size: 10 }, color: '#94a3b8' }
                                },
                                tooltip: {
                                    backgroundColor: '#0c1220',
                                    titleColor: '#ffffff',
                                    bodyColor: '#cbd5e1',
                                    borderColor: 'rgba(255,255,255,0.1)',
                                    borderWidth: 1,
                                    padding: 10,
                                    callbacks: {
                                        label: function(context) {
                                            return context.dataset.label + ': ' + window.formatIDR(context.parsed.y);
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            };
        }
    </script>
</x-app-layout>
