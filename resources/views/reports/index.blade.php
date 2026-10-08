<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6" x-data="reportsData()">
        <!-- Header & Export Action -->
        <div class="card-solid p-5 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-lg font-bold text-slate-900 dark:text-white">Laporan & Dashboard Portofolio</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Ringkasan portofolio aset, pencapaian keahlian, dan buku kas terintegrasi.</p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <!-- Filter Form -->
                <form method="GET" action="{{ route('reports.index') }}" class="flex items-center gap-2">
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

                    <button type="submit" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-slate-900 text-white dark:bg-white dark:text-slate-900 hover:bg-slate-800 transition">
                        Terapkan
                    </button>
                </form>

                <!-- Export CSV Button -->
                <a href="{{ route('reports.export', ['month' => $selectedMonth, 'year' => $selectedYear]) }}"
                   class="px-3.5 py-1.5 text-xs font-semibold rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white transition shadow-sm flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Unduh CSV</span>
                </a>
            </div>
        </div>

        <!-- PORTOFOLIO BREAKDOWN: FINANSIAL VS SKILL ROADMAP -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Portofolio Aset Finansial -->
            <div class="card-solid p-6 space-y-4">
                <div class="flex items-center justify-between pb-3.5 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-blue-600"></span>
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white">Portofolio Aset Finansial</h2>
                    </div>
                    <span class="text-xs text-slate-400">Total Akumulasi</span>
                </div>

                <div class="space-y-2">
                    @forelse($allTimeFinancial as $item)
                        <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/40 border border-slate-200/60 dark:border-slate-800 flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-slate-900 dark:text-white text-xs">{{ $item->category }}</h3>
                                <p class="text-[11px] text-slate-500 font-mono-num">{{ $item->count }} alokasi</p>
                            </div>
                            <div class="text-right font-bold font-mono-num text-slate-900 dark:text-white text-xs">
                                Rp {{ number_format($item->total_amount, 0, ',', '.') }}
                            </div>
                        </div>
                    @empty
                        <p class="text-center py-6 text-xs text-slate-400">Belum ada portofolio aset finansial.</p>
                    @endforelse
                </div>
            </div>

            <!-- Tracker Roadmap & Milestones Keahlian / Skill -->
            <div class="card-solid p-6 space-y-4">
                <div class="flex items-center justify-between pb-3.5 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-indigo-600"></span>
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white">Investasi Keahlian (Skill)</h2>
                    </div>
                    <span class="text-xs text-slate-400">Progres & Target</span>
                </div>

                <div class="space-y-2 max-h-80 overflow-y-auto pr-1">
                    @forelse($skillMilestones as $skill)
                        <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/40 border border-slate-200/60 dark:border-slate-800 flex items-start justify-between gap-3">
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="font-semibold text-slate-900 dark:text-white text-xs">{{ $skill->title }}</h3>
                                    @if($skill->status === 'completed')
                                        <span class="px-1.5 py-0.2 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/80 dark:text-emerald-300">Lulus</span>
                                    @elseif($skill->status === 'in_progress')
                                        <span class="px-1.5 py-0.2 rounded text-[10px] font-semibold bg-amber-50 text-amber-700 dark:bg-amber-950/80 dark:text-amber-300">Berjalan</span>
                                    @else
                                        <span class="px-1.5 py-0.2 rounded text-[10px] font-semibold bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400">Rencana</span>
                                    @endif
                                </div>
                                <p class="text-[11px] text-slate-500 mt-0.5">{{ $skill->platform }} &bull; {{ $skill->target_objective ?? 'Pengembangan kemampuan' }}</p>
                            </div>
                            <div class="text-right shrink-0 font-bold font-mono-num text-slate-900 dark:text-white text-xs">
                                Rp {{ number_format($skill->amount, 0, ',', '.') }}
                            </div>
                        </div>
                    @empty
                        <p class="text-center py-6 text-xs text-slate-400">Belum ada portofolio investasi skill.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- UNIFIED CASH FLOW LEDGER -->
        <div class="card-solid overflow-hidden">
            <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
                <div>
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white">Riwayat Mutasi Dana Terintegrasi (Unified Ledger)</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Catatan seluruh pergerakan dana masuk, keluar, dan alokasi investasi.</p>
                </div>
                <span class="text-xs text-slate-400 font-mono-num">{{ $ledger->count() }} transaksi</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="px-6 py-3.5">Tanggal</th>
                            <th class="px-6 py-3.5">Jenis Transaksi</th>
                            <th class="px-6 py-3.5">Kategori</th>
                            <th class="px-6 py-3.5">Judul</th>
                            <th class="px-6 py-3.5">Catatan</th>
                            <th class="px-6 py-3.5 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($ledger as $item)
                            <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition">
                                <td class="px-6 py-4 font-mono-num text-slate-500 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($item['date'])->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($item['badge_color'] === 'emerald')
                                        <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                            {{ $item['badge'] }}
                                        </span>
                                    @elseif($item['badge_color'] === 'rose')
                                        <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200 dark:border-rose-800">
                                            {{ $item['badge'] }}
                                        </span>
                                    @elseif($item['badge_color'] === 'amber')
                                        <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                            {{ $item['badge'] }}
                                        </span>
                                    @elseif($item['badge_color'] === 'cyan')
                                        <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                            {{ $item['badge'] }}
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">
                                            {{ $item['badge'] }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-medium text-slate-700 dark:text-slate-300 whitespace-nowrap">
                                    {{ $item['category'] }}
                                </td>
                                <td class="px-6 py-4 font-semibold text-slate-900 dark:text-white">
                                    {{ $item['title'] }}
                                </td>
                                <td class="px-6 py-4 text-slate-500 text-[11px] max-w-xs truncate">
                                    {{ $item['notes'] ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-right font-bold font-mono-num text-sm whitespace-nowrap">
                                    @if($item['direction'] === 'in')
                                        <span class="text-emerald-600 dark:text-emerald-400">+Rp {{ number_format($item['amount'], 0, ',', '.') }}</span>
                                    @elseif($item['direction'] === 'out')
                                        <span class="text-rose-600 dark:text-rose-400">-Rp {{ number_format($item['amount'], 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-slate-900 dark:text-white">Rp {{ number_format($item['amount'], 0, ',', '.') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-slate-400">
                                    Tidak ada data transaksi pada filter yang dipilih.
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
