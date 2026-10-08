<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6" x-data="dashboardData()">
        <!-- Period Filter Bar -->
        <div class="card-solid p-4 sm:p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-base font-bold text-slate-900 dark:text-white">Ringkasan Finansial & Surplus</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Periode aktif: <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $metrics['period']['label'] }}</span></p>
            </div>

            <!-- Filter Controls -->
            <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                <select name="month" class="px-3 py-1.5 text-xs font-medium input-solid">
                    <option value="">Semua Bulan</option>
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(2026, $m, 1)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>

                <select name="year" class="px-3 py-1.5 text-xs font-medium input-solid">
                    @for ($y = 2024; $y <= 2027; $y++)
                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>

                <button type="submit" class="px-3.5 py-1.5 text-xs font-semibold rounded-lg bg-slate-900 hover:bg-slate-800 text-white dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100 transition shadow-sm">
                    Terapkan
                </button>

                @if($selectedMonth || $selectedYear != now()->year)
                    <a href="{{ route('dashboard') }}" class="px-3 py-1.5 text-xs font-medium rounded-lg text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 transition">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- MAIN SURPLUS & ALLOCATION BANNER (Wise / Stripe Balance Style) -->
        <div class="card-solid p-6 sm:p-7">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 pb-6 border-b border-slate-100 dark:border-slate-800">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Surplus Arus Kas Bersih</span>
                        @if($metrics['status'] === 'healthy')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-600 dark:bg-emerald-400"></span> Surplus Positif
                            </span>
                        @elseif($metrics['status'] === 'deficit')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800/60">
                                <span class="h-1.5 w-1.5 rounded-full bg-rose-600 dark:bg-rose-400"></span> Defisit
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800/60">
                                <span class="h-1.5 w-1.5 rounded-full bg-amber-600 dark:bg-amber-400"></span> Seimbang
                            </span>
                        @endif
                    </div>
                    <div class="text-3xl sm:text-4xl font-extrabold tracking-tight font-mono-num text-slate-900 dark:text-white">
                        Rp {{ number_format($metrics['gross_surplus'], 0, ',', '.') }}
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                        Dari Total Pemasukan (<strong class="text-slate-800 dark:text-slate-200 font-mono-num">Rp {{ number_format($metrics['total_income'], 0, ',', '.') }}</strong>) dikurangi Total Pengeluaran (<strong class="text-slate-800 dark:text-slate-200 font-mono-num">Rp {{ number_format($metrics['total_expenses'], 0, ',', '.') }}</strong>)
                    </p>
                </div>

                <!-- Surplus Allocation Status Box -->
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/60 min-w-[280px] w-full lg:w-auto">
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Sisa Surplus Siap Dialokasikan</p>
                    <div class="flex items-baseline justify-between gap-4">
                        <span class="text-2xl font-bold font-mono-num {{ $metrics['remaining_surplus'] >= 0 ? 'text-emerald-700 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                            Rp {{ number_format($metrics['remaining_surplus'], 0, ',', '.') }}
                        </span>
                        <a href="{{ route('allocations.index') }}" class="px-3 py-1.5 rounded-lg bg-slate-900 hover:bg-slate-800 text-white dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100 text-xs font-semibold transition">
                            Alokasikan
                        </a>
                    </div>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1.5">
                        Teralokasi: <span class="font-semibold text-slate-800 dark:text-slate-200">Rp {{ number_format($metrics['total_allocated'], 0, ',', '.') }}</span>
                        ({{ $metrics['financial_allocation_percent'] + $metrics['skill_allocation_percent'] }}% dari surplus)
                    </p>
                </div>
            </div>

            <!-- Allocation Progress Bar -->
            <div class="mt-5">
                <div class="flex justify-between text-xs font-medium text-slate-600 dark:text-slate-400 mb-2">
                    <span>Distribusi Penggunaan Surplus</span>
                    <span class="font-mono-num">{{ $metrics['surplus_rate'] }}% Tingkat Surplus</span>
                </div>
                <div class="h-2.5 w-full rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden flex">
                    <!-- Financial Investment -->
                    <div class="bg-blue-600 dark:bg-blue-500 h-full transition-all duration-300" :style="{ width: '{{ min(100, $metrics['financial_allocation_percent']) }}%' }" title="Investasi Finansial"></div>
                    <!-- Skill Investment -->
                    <div class="bg-indigo-600 dark:bg-indigo-400 h-full transition-all duration-300" :style="{ width: '{{ min(100 - $metrics['financial_allocation_percent'], $metrics['skill_allocation_percent']) }}%' }" title="Investasi Skill"></div>
                    <!-- Unallocated -->
                    <div class="bg-slate-200 dark:bg-slate-700 h-full transition-all duration-300" :style="{ width: '{{ max(0, 100 - $metrics['financial_allocation_percent'] - $metrics['skill_allocation_percent']) }}%' }" title="Cadangan Sisa"></div>
                </div>

                <div class="mt-3.5 flex flex-wrap items-center gap-4 sm:gap-6 text-xs text-slate-600 dark:text-slate-400">
                    <div class="flex items-center gap-1.5">
                        <span class="h-2.5 w-2.5 rounded-full bg-blue-600 dark:bg-blue-500"></span>
                        <span>Investasi Finansial:</span>
                        <span class="font-bold text-slate-900 dark:text-white font-mono-num">Rp {{ number_format($metrics['financial_investment'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="h-2.5 w-2.5 rounded-full bg-indigo-600 dark:bg-indigo-400"></span>
                        <span>Investasi Skill:</span>
                        <span class="font-bold text-slate-900 dark:text-white font-mono-num">Rp {{ number_format($metrics['skill_investment'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="h-2.5 w-2.5 rounded-full bg-slate-300 dark:bg-slate-600"></span>
                        <span>Cadangan Tersisa:</span>
                        <span class="font-bold text-slate-900 dark:text-white font-mono-num">Rp {{ number_format(max(0, $metrics['remaining_surplus']), 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4 KPI SUMMARY CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1: Total Pemasukan -->
            <div class="card-solid p-5">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Pemasukan</span>
                    <span class="p-1 rounded bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                    </span>
                </div>
                <div class="text-2xl font-bold text-slate-900 dark:text-white font-mono-num">
                    Rp {{ number_format($metrics['total_income'], 0, ',', '.') }}
                </div>
                <div class="mt-2 text-xs text-slate-500 flex items-center justify-between">
                    <span>Arus masuk</span>
                    <a href="{{ route('incomes.index') }}" class="text-slate-800 dark:text-slate-200 font-semibold hover:underline">Kelola &rarr;</a>
                </div>
            </div>

            <!-- Card 2: Pengeluaran Prioritas -->
            <div class="card-solid p-5">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-1.5">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Biaya Wajib</span>
                        <span class="px-1.5 py-0.2 rounded text-[10px] font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-400 border border-rose-200 dark:border-rose-900">Prioritas</span>
                    </div>
                    <span class="p-1 rounded bg-rose-50 dark:bg-rose-950/50 text-rose-700 dark:text-rose-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
                    </span>
                </div>
                <div class="text-2xl font-bold text-slate-900 dark:text-white font-mono-num">
                    Rp {{ number_format($metrics['priority_expenses'], 0, ',', '.') }}
                </div>
                <div class="mt-2 text-xs text-slate-500 flex items-center justify-between">
                    <span>Rasio: <strong class="text-slate-800 dark:text-slate-200 font-mono-num">{{ $metrics['priority_ratio'] }}%</strong></span>
                    <a href="{{ route('expenses.index', ['type' => 'priority']) }}" class="text-slate-800 dark:text-slate-200 font-semibold hover:underline">Detail &rarr;</a>
                </div>
            </div>

            <!-- Card 3: Pengeluaran Fleksibel -->
            <div class="card-solid p-5">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-1.5">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Biaya Harian</span>
                        <span class="px-1.5 py-0.2 rounded text-[10px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400 border border-amber-200 dark:border-amber-900">Fleksibel</span>
                    </div>
                    <span class="p-1 rounded bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </span>
                </div>
                <div class="text-2xl font-bold text-slate-900 dark:text-white font-mono-num">
                    Rp {{ number_format($metrics['flexible_expenses'], 0, ',', '.') }}
                </div>
                <div class="mt-2 text-xs text-slate-500 flex items-center justify-between">
                    <span>Rasio: <strong class="text-slate-800 dark:text-slate-200 font-mono-num">{{ $metrics['flexible_ratio'] }}%</strong></span>
                    <a href="{{ route('expenses.index', ['type' => 'flexible']) }}" class="text-slate-800 dark:text-slate-200 font-semibold hover:underline">Detail &rarr;</a>
                </div>
            </div>

            <!-- Card 4: Total Alokasi Investasi -->
            <div class="card-solid p-5">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Alokasi</span>
                    <span class="p-1 rounded bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </span>
                </div>
                <div class="text-2xl font-bold text-slate-900 dark:text-white font-mono-num">
                    Rp {{ number_format($metrics['total_allocated'], 0, ',', '.') }}
                </div>
                <div class="mt-2 text-xs text-slate-500 flex items-center justify-between">
                    <span>Finansial & Skill</span>
                    <a href="{{ route('allocations.index') }}" class="text-slate-800 dark:text-slate-200 font-semibold hover:underline">Buka Hub &rarr;</a>
                </div>
            </div>
        </div>

        <!-- CHARTS SECTION -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Chart 1: Donut Alokasi Surplus -->
            <div class="card-solid p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white">Alokasi Surplus</h2>
                        <p class="text-xs text-slate-500">Distribusi surplus ke instrumen masa depan</p>
                    </div>
                </div>
                <div class="h-52 relative flex items-center justify-center">
                    <canvas id="surplusAllocationChart"></canvas>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 space-y-2 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-blue-600"></span> Finansial</span>
                        <span class="font-bold font-mono-num text-slate-800 dark:text-slate-200">Rp {{ number_format($metrics['financial_investment'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-indigo-600"></span> Skill (Leher ke Atas)</span>
                        <span class="font-bold font-mono-num text-slate-800 dark:text-slate-200">Rp {{ number_format($metrics['skill_investment'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-slate-400"></span> Sisa Cadangan</span>
                        <span class="font-bold font-mono-num text-slate-800 dark:text-slate-200">Rp {{ number_format(max(0, $metrics['remaining_surplus']), 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Chart 2: Donut Komposisi Pengeluaran -->
            <div class="card-solid p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white">Komposisi Pengeluaran</h2>
                        <p class="text-xs text-slate-500">Perbandingan Biaya Wajib vs Fleksibel</p>
                    </div>
                </div>
                <div class="h-52 relative flex items-center justify-center">
                    <canvas id="expenseCompositionChart"></canvas>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 space-y-2 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-rose-600"></span> Wajib / Prioritas</span>
                        <span class="font-bold font-mono-num text-slate-800 dark:text-slate-200">Rp {{ number_format($metrics['priority_expenses'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-amber-500"></span> Fleksibel / Harian</span>
                        <span class="font-bold font-mono-num text-slate-800 dark:text-slate-200">Rp {{ number_format($metrics['flexible_expenses'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between pt-1 border-t border-slate-100 dark:border-slate-800">
                        <span class="font-semibold text-slate-500">Total Biaya</span>
                        <span class="font-bold font-mono-num text-slate-900 dark:text-white">Rp {{ number_format($metrics['total_expenses'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Chart 3: Tren Arus Kas Bulanan -->
            <div class="card-solid p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="text-sm font-bold text-slate-900 dark:text-white">Tren Arus Kas ({{ $selectedYear ?? now()->year }})</h2>
                            <p class="text-xs text-slate-500">Pemasukan, Pengeluaran & Surplus per Bulan</p>
                        </div>
                    </div>
                    <div class="h-52 relative">
                        <canvas id="cashflowTrendChart"></canvas>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 text-xs text-slate-500 flex items-center justify-between">
                    <span>Laporan menyeluruh</span>
                    <a href="{{ route('reports.index') }}" class="text-slate-800 dark:text-slate-200 hover:underline font-semibold">Buka Laporan &rarr;</a>
                </div>
            </div>
        </div>

        <!-- QUICK ACTION BAR -->
        <div class="card-solid p-5 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-sm font-bold text-slate-900 dark:text-white">Catat Cepat Transaksi</h2>
                <p class="text-xs text-slate-500 mt-0.5">Catat pemasukan, pengeluaran atau alokasikan surplus periode ini.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('incomes.index') }}" class="px-3.5 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition shadow-sm flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Catat Pemasukan</span>
                </a>
                <a href="{{ route('expenses.index') }}" class="px-3.5 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs transition shadow-sm flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Catat Pengeluaran</span>
                </a>
                <a href="{{ route('allocations.index') }}" class="px-3.5 py-2 rounded-lg bg-slate-900 hover:bg-slate-800 text-white dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100 font-semibold text-xs transition shadow-sm flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span>+ Alokasi Surplus</span>
                </a>
            </div>
        </div>

        <!-- RECENT ACTIVITY SPLIT -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Column 1: Mutasi Pengeluaran Terkini -->
            <div class="card-solid p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white">Pengeluaran Terkini</h2>
                        <p class="text-xs text-slate-500">Prioritas & Fleksibel</p>
                    </div>
                    <a href="{{ route('expenses.index') }}" class="text-xs font-semibold text-slate-600 dark:text-slate-400 hover:underline">Lihat Semua &rarr;</a>
                </div>

                <div class="space-y-2">
                    @forelse($recentExpenses as $exp)
                        <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-lg flex items-center justify-center shrink-0 {{ $exp->isPriority() ? 'bg-rose-50 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400' : 'bg-amber-50 text-amber-600 dark:bg-amber-950/60 dark:text-amber-400' }}">
                                    @if($exp->isPriority())
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    @endif
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h4 class="text-xs font-semibold text-slate-900 dark:text-white">{{ $exp->title }}</h4>
                                        <span class="px-1.5 py-0.2 rounded text-[10px] font-bold {{ $exp->isPriority() ? 'bg-rose-50 text-rose-700 dark:bg-rose-950/80 dark:text-rose-300' : 'bg-amber-50 text-amber-700 dark:bg-amber-950/80 dark:text-amber-300' }}">
                                            {{ $exp->isPriority() ? 'Prioritas' : 'Fleksibel' }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 truncate max-w-[180px] sm:max-w-xs">{{ $exp->category }} &bull; {{ $exp->notes ?? 'Tidak ada catatan' }}</p>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-xs font-bold font-mono-num text-rose-600 dark:text-rose-400">
                                    -Rp {{ number_format($exp->amount, 0, ',', '.') }}
                                </span>
                                <p class="text-[10px] text-slate-400 font-mono-num">{{ \Carbon\Carbon::parse($exp->date)->translatedFormat('d M') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-xs text-slate-400">
                            Belum ada pengeluaran tercatat pada periode ini.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Column 2: Portofolio Alokasi Terkini -->
            <div class="card-solid p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white">Alokasi Investasi Terkini</h2>
                        <p class="text-xs text-slate-500">Finansial & Skill</p>
                    </div>
                    <a href="{{ route('allocations.index') }}" class="text-xs font-semibold text-slate-600 dark:text-slate-400 hover:underline">Buka Hub &rarr;</a>
                </div>

                <div class="space-y-2">
                    @forelse($financialAllocations->concat($skillAllocations)->sortByDesc('date')->take(6) as $alloc)
                        <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-lg flex items-center justify-center shrink-0 {{ $alloc->isFinancial() ? 'bg-blue-50 text-blue-600 dark:bg-blue-950/60 dark:text-blue-400' : 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/60 dark:text-indigo-400' }}">
                                    @if($alloc->isFinancial())
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    @endif
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h4 class="text-xs font-semibold text-slate-900 dark:text-white">{{ $alloc->title }}</h4>
                                        <span class="px-1.5 py-0.2 rounded text-[10px] font-bold {{ $alloc->isFinancial() ? 'bg-blue-50 text-blue-700 dark:bg-blue-950/80 dark:text-blue-300' : 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/80 dark:text-indigo-300' }}">
                                            {{ $alloc->isFinancial() ? 'Finansial' : 'Skill' }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 truncate max-w-[180px] sm:max-w-xs">{{ $alloc->category }} @if($alloc->platform) &bull; {{ $alloc->platform }} @endif</p>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-xs font-bold font-mono-num text-slate-900 dark:text-white">
                                    Rp {{ number_format($alloc->amount, 0, ',', '.') }}
                                </span>
                                <p class="text-[10px] text-slate-400 font-mono-num">{{ \Carbon\Carbon::parse($alloc->date)->translatedFormat('d M') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-xs text-slate-400">
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
                            labels: ['Investasi Finansial', 'Investasi Skill', 'Sisa Cadangan'],
                            datasets: [{
                                data: hasData ? [financial, skill, remaining] : [1],
                                backgroundColor: hasData ? ['#2563eb', '#4f46e5', '#94a3b8'] : ['#e2e8f0'],
                                borderWidth: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            if (!hasData) return 'Belum ada data surplus';
                                            return context.label + ': ' + window.formatIDR(context.parsed);
                                        }
                                    }
                                }
                            },
                            cutout: '76%'
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
                            labels: ['Wajib (Prioritas)', 'Fleksibel (Harian)'],
                            datasets: [{
                                data: hasData ? [priority, flexible] : [1],
                                backgroundColor: hasData ? ['#e11d48', '#d97706'] : ['#e2e8f0'],
                                borderWidth: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            if (!hasData) return 'Belum ada data pengeluaran';
                                            return context.label + ': ' + window.formatIDR(context.parsed);
                                        }
                                    }
                                }
                            },
                            cutout: '76%'
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
                                    borderRadius: 4
                                },
                                {
                                    label: 'Pengeluaran',
                                    data: chartData.expenses,
                                    backgroundColor: '#f43f5e',
                                    borderRadius: 4
                                },
                                {
                                    label: 'Surplus',
                                    data: chartData.surplus,
                                    backgroundColor: '#2563eb',
                                    borderRadius: 4
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
                                    grid: { color: 'rgba(0, 0, 0, 0.05)' },
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
                                    labels: { boxWidth: 10, font: { size: 10 }, color: '#64748b' }
                                },
                                tooltip: {
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
