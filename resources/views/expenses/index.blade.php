<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6"
         x-data="{
             showAddModal: false,
             expenseType: '{{ $typeFilter ?? 'priority' }}',
             inputAmount: 0,
             categoriesPriority: ['Tempat Tinggal', 'Utilitas', 'Makanan Pokok', 'Internet & Komunikasi', 'Asuransi & Proteksi', 'Cicilan & Kewajiban', 'Kesehatan'],
             categoriesFlexible: ['Kuliner & Nongkrong', 'Hiburan & Langganan', 'Hobi & Lifestyle', 'Shopping', 'Transportasi Tambahan', 'Hadiah & Donasi', 'Lainnya']
         }">

        <!-- Header & Action Button -->
        <div class="card-solid p-5 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-lg font-bold text-slate-900 dark:text-white">Pencatatan Pengeluaran</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Pisahkan antara pengeluaran <strong class="text-rose-600 dark:text-rose-400">Prioritas (Wajib)</strong> dan pengeluaran <strong class="text-amber-600 dark:text-amber-400">Fleksibel (Harian)</strong>.</p>
            </div>

            <button @click="showAddModal = true" class="px-4 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs transition shadow-sm flex items-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Catat Pengeluaran</span>
            </button>
        </div>

        <!-- 3 KPI SUMMARY CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="card-solid p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Pengeluaran</p>
                <p class="text-2xl font-bold text-slate-900 dark:text-white font-mono-num mt-1">
                    Rp {{ number_format($totalExpenses, 0, ',', '.') }}
                </p>
                <p class="text-[11px] text-slate-500 mt-1">Total pengeluaran periode ini</p>
            </div>

            <div class="card-solid p-5">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Pengeluaran Prioritas</p>
                    <span class="px-1.5 py-0.2 rounded text-[10px] font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/80 dark:text-rose-400 border border-rose-200 dark:border-rose-900">Wajib</span>
                </div>
                <p class="text-2xl font-bold text-rose-600 dark:text-rose-400 font-mono-num mt-1">
                    Rp {{ number_format($totalPriority, 0, ',', '.') }}
                </p>
                <p class="text-[11px] text-slate-500 mt-1 font-mono-num">
                    {{ $totalExpenses > 0 ? round(($totalPriority / $totalExpenses) * 100, 1) : 0 }}% dari total pengeluaran
                </p>
            </div>

            <div class="card-solid p-5">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Pengeluaran Fleksibel</p>
                    <span class="px-1.5 py-0.2 rounded text-[10px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/80 dark:text-amber-400 border border-amber-200 dark:border-amber-900">Harian</span>
                </div>
                <p class="text-2xl font-bold text-amber-600 dark:text-amber-400 font-mono-num mt-1">
                    Rp {{ number_format($totalFlexible, 0, ',', '.') }}
                </p>
                <p class="text-[11px] text-slate-500 mt-1 font-mono-num">
                    {{ $totalExpenses > 0 ? round(($totalFlexible / $totalExpenses) * 100, 1) : 0 }}% dari total pengeluaran
                </p>
            </div>
        </div>

        <!-- Filter, Tabs & Search Bar -->
        <div class="card-solid p-4 sm:p-5 space-y-3">
            <!-- Tabs -->
            <div class="flex flex-wrap items-center gap-2 border-b border-slate-100 dark:border-slate-800 pb-3">
                <a href="{{ route('expenses.index', array_merge(request()->except('type', 'page'), ['type' => ''])) }}"
                   class="px-3 py-1.5 text-xs font-semibold rounded-md transition {{ empty($typeFilter) ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    Semua Pengeluaran
                </a>

                <a href="{{ route('expenses.index', array_merge(request()->except('type', 'page'), ['type' => 'priority'])) }}"
                   class="px-3 py-1.5 text-xs font-semibold rounded-md transition flex items-center gap-1.5 {{ $typeFilter === 'priority' ? 'bg-rose-50 text-rose-700 dark:bg-rose-950/80 dark:text-rose-300 border border-rose-200 dark:border-rose-900' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                    <span>Prioritas (Wajib)</span>
                </a>

                <a href="{{ route('expenses.index', array_merge(request()->except('type', 'page'), ['type' => 'flexible'])) }}"
                   class="px-3 py-1.5 text-xs font-semibold rounded-md transition flex items-center gap-1.5 {{ $typeFilter === 'flexible' ? 'bg-amber-50 text-amber-700 dark:bg-amber-950/80 dark:text-amber-300 border border-amber-200 dark:border-amber-900' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                    <span>Fleksibel (Harian)</span>
                </a>
            </div>

            <!-- Form Filters -->
            <form method="GET" action="{{ route('expenses.index') }}" class="flex flex-wrap items-center gap-3">
                <input type="hidden" name="type" value="{{ $typeFilter }}">

                <div class="relative flex-1 min-w-[200px]">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau peruntukan..."
                           class="w-full pl-9 pr-3 py-1.5 text-xs input-solid">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>

                <select name="category" class="px-3 py-1.5 text-xs font-medium input-solid">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ $categoryFilter === $cat ? 'selected' : '' }}>{{ $cat }}</option>
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

                @if($search || $categoryFilter || $selectedMonth || $typeFilter)
                    <a href="{{ route('expenses.index') }}" class="px-2.5 py-1.5 text-xs text-slate-500 hover:text-slate-800 transition">
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
                            <th class="px-6 py-3.5">Pengeluaran</th>
                            <th class="px-6 py-3.5">Klasifikasi</th>
                            <th class="px-6 py-3.5">Kategori & Catatan</th>
                            <th class="px-6 py-3.5 text-right">Nominal</th>
                            <th class="px-6 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($expenses as $expense)
                            <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition">
                                <td class="px-6 py-4 font-mono-num text-slate-500 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($expense->date)->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-900 dark:text-white">{{ $expense->title }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($expense->isPriority())
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[11px] font-medium bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200 dark:border-rose-900">
                                            Prioritas (Wajib)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[11px] font-medium bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200 dark:border-amber-900">
                                            Fleksibel (Harian)
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-slate-800 dark:text-slate-200">{{ $expense->category }}</div>
                                    <div class="text-slate-500 text-[11px] mt-0.5">{{ $expense->notes ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 text-right font-bold font-mono-num text-sm whitespace-nowrap {{ $expense->isPriority() ? 'text-rose-600 dark:text-rose-400' : 'text-amber-600 dark:text-amber-400' }}">
                                    -Rp {{ number_format($expense->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('expenses.edit', $expense) }}" class="p-1 rounded text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>

                                        <form method="POST" action="{{ route('expenses.destroy', $expense) }}" onsubmit="return confirm('Hapus catatan pengeluaran ini?');" class="inline">
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
                                <td colspan="6" class="px-6 py-10 text-center text-slate-500">
                                    Belum ada catatan pengeluaran pada periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($expenses->hasPages())
                <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                    {{ $expenses->links() }}
                </div>
            @endif
        </div>

        <!-- MODAL TAMBAH PENGELUARAN -->
        <div x-show="showAddModal" x-cloak style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showAddModal" x-transition.opacity
                     class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs" @click="showAddModal = false"></div>

                <div x-show="showAddModal" x-transition
                     class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform card-solid shadow-xl relative">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Tambah Catatan Pengeluaran</h3>
                        <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('expenses.store') }}" class="mt-4 space-y-4">
                        @csrf

                        <!-- Toggle Prioritas vs Fleksibel -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">Klasifikasi Pengeluaran <span class="text-rose-500">*</span></label>
                            <div class="grid grid-cols-2 gap-2 p-1 rounded-lg bg-slate-100 dark:bg-slate-800">
                                <label class="flex items-center justify-center gap-1.5 p-2 rounded-md text-xs font-semibold cursor-pointer transition"
                                       :class="expenseType === 'priority' ? 'bg-white dark:bg-slate-900 text-rose-700 dark:text-rose-400 shadow-xs border border-slate-200 dark:border-slate-700' : 'text-slate-600 dark:text-slate-400'">
                                    <input type="radio" name="type" value="priority" x-model="expenseType" class="hidden">
                                    <span>Prioritas (Wajib)</span>
                                </label>

                                <label class="flex items-center justify-center gap-1.5 p-2 rounded-md text-xs font-semibold cursor-pointer transition"
                                       :class="expenseType === 'flexible' ? 'bg-white dark:bg-slate-900 text-amber-700 dark:text-amber-400 shadow-xs border border-slate-200 dark:border-slate-700' : 'text-slate-600 dark:text-slate-400'">
                                    <input type="radio" name="type" value="flexible" x-model="expenseType" class="hidden">
                                    <span>Fleksibel (Harian)</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Pengeluaran <span class="text-rose-500">*</span></label>
                            <input type="text" name="title" required placeholder="Contoh: Sewa Kos, Kopi, Belanja Bulanan"
                                   class="w-full px-3 py-2 text-xs input-solid">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Kategori <span class="text-rose-500">*</span></label>
                                <select name="category" required class="w-full px-3 py-2 text-xs input-solid">
                                    <template x-if="expenseType === 'priority'">
                                        <template x-for="cat in categoriesPriority" :key="cat">
                                            <option :value="cat" x-text="cat"></option>
                                        </template>
                                    </template>
                                    <template x-if="expenseType === 'flexible'">
                                        <template x-for="cat in categoriesFlexible" :key="cat">
                                            <option :value="cat" x-text="cat"></option>
                                        </template>
                                    </template>
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
                                <span class="text-xs font-mono-num font-bold text-rose-600 dark:text-rose-400" x-text="window.formatIDR(inputAmount)"></span>
                            </div>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-xs font-bold text-slate-400">Rp</span>
                                <input type="number" step="0.01" min="1" name="amount" required x-model.number="inputAmount" placeholder="0"
                                       class="w-full pl-9 pr-3 py-2 text-sm font-bold font-mono-num input-solid">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Catatan Detail (Opsional)</label>
                            <textarea name="notes" rows="2" placeholder="Keterangan peruntukan pengeluaran..."
                                      class="w-full px-3 py-1.5 text-xs input-solid"></textarea>
                        </div>

                        <div class="pt-3 flex items-center justify-end gap-2 border-t border-slate-100 dark:border-slate-800">
                            <button type="button" @click="showAddModal = false" class="px-3.5 py-1.5 text-xs font-medium rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-1.5 text-xs font-semibold rounded-lg bg-rose-600 hover:bg-rose-700 text-white transition shadow-sm">
                                Simpan Pengeluaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
