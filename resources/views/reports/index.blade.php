<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8" x-data="reportsData()">
        <!-- Header & Export Action -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="h-12 w-12 rounded-2xl bg-teal-500/10 text-teal-600 dark:text-teal-400 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold text-slate-900 dark:text-white">Dashboard Portofolio & Laporan Menyeluruh</h1>
                    <p class="text-xs text-slate-500">Analitik statistik pengeluaran, portofolio aset & skill, serta ledger riwayat mutasi dana.</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <!-- Filter Form -->
                <form method="GET" action="{{ route('reports.index') }}" class="flex items-center gap-2">
                    <select name="month" class="px-3 py-2 text-xs font-medium rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:outline-none">
                        <option value="">Semua Bulan</option>
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create(2026, $m, 1)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>

                    <select name="year" class="px-3 py-2 text-xs font-medium rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:outline-none">
                        @for ($y = 2024; $y <= 2027; $y++)
                            <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>

                    <button type="submit" class="px-3 py-2 text-xs font-semibold rounded-xl bg-slate-800 dark:bg-slate-700 text-white">
                        Terapkan
                    </button>
                </form>

                <!-- Export CSV Button -->
                <a href="{{ route('reports.export', ['month' => $selectedMonth, 'year' => $selectedYear]) }}"
                   class="px-4 py-2 text-xs font-bold rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white transition shadow-sm flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Unduh CSV</span>
                </a>
            </div>
        </div>

        <!-- PORTOFOLIO BREAKDOWN: FINANSIAL VS SKILL ROADMAP -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Portofolio Aset Finansial -->
            <div class="p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2">
                        <span class="h-3 w-3 rounded-full bg-cyan-500"></span>
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white">Portofolio Aset Finansial</h2>
                    </div>
                    <span class="text-xs text-slate-400">Total Akumulasi</span>
                </div>

                <div class="space-y-3">
                    @forelse($allTimeFinancial as $item)
                        <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 flex items-center justify-between">
                            <div>
                                <h3 class="font-bold text-slate-800 dark:text-white text-xs">{{ $item->category }}</h3>
                                <p class="text-[11px] text-slate-500">{{ $item->count }} transaksi alokasi</p>
                            </div>
                            <div class="text-right font-extrabold font-mono-num text-cyan-600 dark:text-cyan-400 text-sm">
                                Rp {{ number_format($item->total_amount, 0, ',', '.') }}
                            </div>
                        </div>
                    @empty
                        <p class="text-center py-6 text-xs text-slate-400">Belum ada portofolio aset finansial.</p>
                    @endforelse
                </div>
            </div>

            <!-- Tracker Roadmap & Milestones Keahlian / Skill -->
            <div class="p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2">
                        <span class="h-3 w-3 rounded-full bg-violet-500"></span>
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white">Roadmap Investasi Leher ke Atas (Skill)</h2>
                    </div>
                    <span class="text-xs text-slate-400">Target & Sertifikasi</span>
                </div>

                <div class="space-y-3 max-h-80 overflow-y-auto pr-1">
                    @forelse($skillMilestones as $skill)
                        <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 flex items-start justify-between gap-3">
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="font-bold text-slate-800 dark:text-white text-xs">{{ $skill->title }}</h3>
                                    @if($skill->status === 'completed')
                                        <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">Lulus</span>
                                    @elseif($skill->status === 'in_progress')
                                        <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-950/80 dark:text-amber-300">Sedang Belajar</span>
                                    @else
                                        <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300">Direncanakan</span>
                                    @endif
                                </div>
                                <p class="text-[11px] text-slate-500 mt-0.5">{{ $skill->platform }} &bull; {{ $skill->target_objective ?? 'Pengembangan kemampuan' }}</p>
                            </div>
                            <div class="text-right shrink-0 font-extrabold font-mono-num text-violet-600 dark:text-violet-400 text-xs">
                                Rp {{ number_format($skill->amount, 0, ',', '.') }}
                            </div>
                        </div>
                    @empty
                        <p class="text-center py-6 text-xs text-slate-400">Belum ada portofolio investasi skill.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- UNIFIED CASH FLOW LEDGER (RIWAYAT MUTASI DANA MENYELURUH) -->
        <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
                <div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white">Riwayat Mutasi Dana Terintegrasi (Unified Ledger)</h2>
                    <p class="text-xs text-slate-500">Pemasukan, Pengeluaran Prioritas & Fleksibel, serta Distribusi Investasi dalam satu buku kas.</p>
                </div>
                <span class="text-xs text-slate-400 font-mono-num">{{ $ledger->count() }} mutasi transaksi</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 dark:bg-slate-800/60 border-b border-slate-200 dark:border-slate-800 text-slate-500 font-bold uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Jenis Transaksi</th>
                            <th class="px-6 py-4">Kategori / Sumber</th>
                            <th class="px-6 py-4">Deskripsi / Judul</th>
                            <th class="px-6 py-4">Catatan / Target</th>
                            <th class="px-6 py-4 text-right">Arus Dana & Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($ledger as $item)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition">
                                <td class="px-6 py-4 font-mono-num text-slate-500 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($item['date'])->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($item['badge_color'] === 'emerald')
                                        <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-500/20">
                                            {{ $item['badge'] }}
                                        </span>
                                    @elseif($item['badge_color'] === 'rose')
                                        <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-500/20">
                                            {{ $item['badge'] }}
                                        </span>
                                    @elseif($item['badge_color'] === 'amber')
                                        <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-500/20">
                                            {{ $item['badge'] }}
                                        </span>
                                    @elseif($item['badge_color'] === 'cyan')
                                        <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-cyan-50 text-cyan-700 dark:bg-cyan-950/60 dark:text-cyan-300 border border-cyan-500/20">
                                            {{ $item['badge'] }}
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-violet-50 text-violet-700 dark:bg-violet-950/60 dark:text-violet-300 border border-violet-500/20">
                                            {{ $item['badge'] }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-semibold text-slate-700 dark:text-slate-300 whitespace-nowrap">
                                    {{ $item['category'] }}
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">
                                    {{ $item['title'] }}
                                </td>
                                <td class="px-6 py-4 text-slate-500 text-[11px] max-w-xs truncate">
                                    {{ $item['notes'] ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-right font-extrabold font-mono-num text-sm whitespace-nowrap">
                                    @if($item['direction'] === 'in')
                                        <span class="text-emerald-600 dark:text-emerald-400">+Rp {{ number_format($item['amount'], 0, ',', '.') }}</span>
                                    @elseif($item['direction'] === 'out')
                                        <span class="text-rose-600 dark:text-rose-400">-Rp {{ number_format($item['amount'], 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-indigo-600 dark:text-indigo-400">Rp {{ number_format($item['amount'], 0, ',', '.') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-slate-400">
                                    Tidak ada data mutasi pada filter yang dipilih.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function reportsData() {
            return {};
        }
    </script>
</x-app-layout>
