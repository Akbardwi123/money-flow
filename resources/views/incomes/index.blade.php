<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6" x-data="{ showAddModal: false }">
        <!-- Header & Action Button -->
        <div class="card-solid p-5 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-lg font-bold text-slate-900 dark:text-white">Pencatatan Pemasukan</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Kelola arus kas masuk Anda dari berbagai sumber penghasilan.</p>
            </div>

            <button @click="showAddModal = true" class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition shadow-sm flex items-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Catat Pemasukan</span>
            </button>
        </div>

        <!-- Summary KPI Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="card-solid p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Pemasukan</p>
                <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 font-mono-num mt-1">
                    Rp {{ number_format($totalIncome, 0, ',', '.') }}
                </p>
                <p class="text-[11px] text-slate-500 mt-1">Total akumulasi periode aktif</p>
            </div>

            <div class="card-solid p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Jumlah Transaksi</p>
                <p class="text-2xl font-bold text-slate-900 dark:text-white font-mono-num mt-1">
                    {{ $incomes->total() }} <span class="text-xs font-normal text-slate-500">transaksi</span>
                </p>
                <p class="text-[11px] text-slate-500 mt-1">Tercatat dalam filter saat ini</p>
            </div>

            <div class="card-solid p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Rata-rata per Transaksi</p>
                <p class="text-2xl font-bold text-slate-900 dark:text-white font-mono-num mt-1">
                    Rp {{ number_format($incomes->total() > 0 ? $totalIncome / $incomes->total() : 0, 0, ',', '.') }}
                </p>
                <p class="text-[11px] text-slate-500 mt-1">Rata-rata nominal per sumber</p>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="card-solid p-4">
            <form method="GET" action="{{ route('incomes.index') }}" class="flex flex-wrap items-center gap-3">
                <div class="relative flex-1 min-w-[200px]">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau keterangan..."
                           class="w-full pl-9 pr-3 py-1.5 text-xs input-solid">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>

                <select name="source" class="px-3 py-1.5 text-xs font-medium input-solid">
                    <option value="">Semua Sumber</option>
                    @foreach($sources as $src)
                        <option value="{{ $src }}" {{ $sourceFilter === $src ? 'selected' : '' }}>{{ $src }}</option>
                    @endforeach
                </select>

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

                <button type="submit" class="px-3.5 py-1.5 text-xs font-semibold rounded-lg bg-slate-900 text-white dark:bg-white dark:text-slate-900 hover:bg-slate-800 transition">
                    Filter
                </button>

                @if($search || $sourceFilter || $selectedMonth)
                    <a href="{{ route('incomes.index') }}" class="px-2.5 py-1.5 text-xs text-slate-500 hover:text-slate-800 transition">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Table Listing -->
        <div class="card-solid overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="px-6 py-3.5">Tanggal</th>
                            <th class="px-6 py-3.5">Keterangan</th>
                            <th class="px-6 py-3.5">Sumber Dana</th>
                            <th class="px-6 py-3.5 text-right">Nominal</th>
                            <th class="px-6 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($incomes as $income)
                            <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition">
                                <td class="px-6 py-4 font-mono-num text-slate-500 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($income->date)->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-900 dark:text-white">{{ $income->title }}</div>
                                    @if($income->notes)
                                        <div class="text-slate-500 text-[11px] mt-0.5">{{ $income->notes }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                        {{ $income->source }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-emerald-600 dark:text-emerald-400 font-mono-num text-sm whitespace-nowrap">
                                    +Rp {{ number_format($income->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('incomes.edit', $income) }}" class="p-1 rounded text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>

                                        <form method="POST" action="{{ route('incomes.destroy', $income) }}" onsubmit="return confirm('Hapus catatan pemasukan ini?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 rounded text-slate-400 hover:text-rose-600 transition" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-slate-500">
                                    Belum ada catatan pemasukan pada periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($incomes->hasPages())
                <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                    {{ $incomes->links() }}
                </div>
            @endif
        </div>

        <!-- MODAL TAMBAH PEMASUKAN -->
        <div x-show="showAddModal" x-cloak style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showAddModal" x-transition.opacity
                     class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs" @click="showAddModal = false"></div>

                <div x-show="showAddModal" x-transition
                     class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform card-solid shadow-xl relative"
                     x-data="{ inputAmount: 0 }">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Tambah Catatan Pemasukan</h3>
                        <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('incomes.store') }}" class="mt-4 space-y-4">
                        @csrf

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Judul Pemasukan <span class="text-rose-500">*</span></label>
                            <input type="text" name="title" required placeholder="Contoh: Gaji Pokok, Freelance Project"
                                   class="w-full px-3 py-2 text-xs input-solid">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Sumber Arus Masuk <span class="text-rose-500">*</span></label>
                                <select name="source" required class="w-full px-3 py-2 text-xs input-solid">
                                    <option value="Gaji Pokok">Gaji Pokok</option>
                                    <option value="Freelance">Freelance</option>
                                    <option value="Bisnis / Usaha">Bisnis / Usaha</option>
                                    <option value="Dividen / Passive Income">Dividen / Passive Income</option>
                                    <option value="Bonus & THR">Bonus & THR</option>
                                    <option value="Penjualan Aset">Penjualan Aset</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Tanggal Transaksi <span class="text-rose-500">*</span></label>
                                <input type="date" name="date" required value="{{ date('Y-m-d') }}"
                                       class="w-full px-3 py-2 text-xs input-solid">
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">Nominal (IDR) <span class="text-rose-500">*</span></label>
                                <span class="text-xs font-mono-num font-bold text-emerald-600 dark:text-emerald-400" x-text="window.formatIDR(inputAmount)"></span>
                            </div>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-xs font-bold text-slate-400">Rp</span>
                                <input type="number" step="0.01" min="1" name="amount" required x-model.number="inputAmount" placeholder="0"
                                       class="w-full pl-9 pr-3 py-2 text-sm font-bold font-mono-num input-solid">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Catatan Tambahan (Opsional)</label>
                            <textarea name="notes" rows="2" placeholder="Keterangan detail transaksi..."
                                      class="w-full px-3 py-1.5 text-xs input-solid"></textarea>
                        </div>

                        <div class="pt-3 flex items-center justify-end gap-2 border-t border-slate-100 dark:border-slate-800">
                            <button type="button" @click="showAddModal = false" class="px-3.5 py-1.5 text-xs font-medium rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-1.5 text-xs font-semibold rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white transition shadow-sm">
                                Simpan Pemasukan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
