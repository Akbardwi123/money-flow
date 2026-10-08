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
        <div class="fintech-card rounded-3xl p-6 sm:p-7 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="h-12 w-12 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center justify-center shrink-0 shadow-[0_0_15px_rgba(244,63,94,0.2)]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
                </div>
                <div>
                    <h1 class="text-xl font-black text-white tracking-tight">Pencatatan Pengeluaran (Expenses)</h1>
                    <p class="text-xs text-slate-400 mt-0.5">Pemisahan terarah antara kebutuhan <strong class="text-rose-400">Prioritas (Wajib)</strong> dan pengeluaran <strong class="text-amber-400">Fleksibel (Harian)</strong>.</p>
                </div>
            </div>

            <button @click="showAddModal = true" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-400 hover:to-pink-400 text-white font-bold text-xs uppercase tracking-wider transition shadow-[0_0_20px_rgba(244,63,94,0.35)] flex items-center gap-2 cursor-pointer transform hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>+ Catat Pengeluaran Baru</span>
            </button>
        </div>

        <!-- 3 KPI SUMMARY CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- Total Pengeluaran -->
            <div class="fintech-card rounded-2xl p-5 hover:border-white/20 transition">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Pengeluaran Periode</p>
                <p class="text-2xl font-black text-white font-mono-num mt-1">
                    Rp {{ number_format($totalExpenses, 0, ',', '.') }}
                </p>
                <p class="text-[11px] text-slate-500 mt-1">Kombinasi biaya wajib & pengeluaran gaya hidup</p>
            </div>

            <!-- Total Prioritas -->
            <div class="fintech-card rounded-2xl p-5 hover:border-rose-500/40 transition relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-rose-400">Pengeluaran Prioritas</p>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wider bg-rose-500/20 text-rose-300 border border-rose-500/30">Wajib / Tagihan</span>
                </div>
                <p class="text-2xl font-black text-rose-400 font-mono-num mt-1">
                    Rp {{ number_format($totalPriority, 0, ',', '.') }}
                </p>
                <p class="text-[11px] text-slate-400 mt-1 font-mono-num">
                    {{ $totalExpenses > 0 ? round(($totalPriority / $totalExpenses) * 100, 1) : 0 }}% dari total biaya
                </p>
            </div>

            <!-- Total Fleksibel -->
            <div class="fintech-card rounded-2xl p-5 hover:border-amber-500/40 transition relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-amber-400">Pengeluaran Fleksibel</p>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wider bg-amber-500/20 text-amber-300 border border-amber-500/30">Harian / Opsional</span>
                </div>
                <p class="text-2xl font-black text-amber-400 font-mono-num mt-1">
                    Rp {{ number_format($totalFlexible, 0, ',', '.') }}
                </p>
                <p class="text-[11px] text-slate-400 mt-1 font-mono-num">
                    {{ $totalExpenses > 0 ? round(($totalFlexible / $totalExpenses) * 100, 1) : 0 }}% dari total biaya
                </p>
            </div>
        </div>

        <!-- Filter, Type Tabs & Search Bar -->
        <div class="fintech-card rounded-2xl p-4 sm:p-5 space-y-3.5">
            <!-- Tabs: Semua, Prioritas, Fleksibel -->
            <div class="flex flex-wrap items-center gap-2 border-b border-white/10 pb-3.5">
                <a href="{{ route('expenses.index', array_merge(request()->except('type', 'page'), ['type' => ''])) }}"
                   class="px-4 py-2 text-xs font-bold rounded-xl transition {{ empty($typeFilter) ? 'bg-white text-slate-950 shadow-[0_0_15px_rgba(255,255,255,0.3)]' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                    Semua Pengeluaran
                </a>

                <a href="{{ route('expenses.index', array_merge(request()->except('type', 'page'), ['type' => 'priority'])) }}"
                   class="px-4 py-2 text-xs font-bold rounded-xl transition flex items-center gap-1.5 {{ $typeFilter === 'priority' ? 'bg-rose-500/20 text-rose-300 border border-rose-500/40 shadow-[0_0_15px_rgba(244,63,94,0.3)]' : 'text-slate-400 hover:text-rose-400 hover:bg-white/5' }}">
                    <span class="h-2 w-2 rounded-full bg-rose-400"></span>
                    <span>Prioritas (Wajib)</span>
                </a>

                <a href="{{ route('expenses.index', array_merge(request()->except('type', 'page'), ['type' => 'flexible'])) }}"
                   class="px-4 py-2 text-xs font-bold rounded-xl transition flex items-center gap-1.5 {{ $typeFilter === 'flexible' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/40 shadow-[0_0_15px_rgba(245,158,11,0.3)]' : 'text-slate-400 hover:text-amber-400 hover:bg-white/5' }}">
                    <span class="h-2 w-2 rounded-full bg-amber-400"></span>
                    <span>Fleksibel (Harian)</span>
                </a>
            </div>

            <!-- Form Filters -->
            <form method="GET" action="{{ route('expenses.index') }}" class="flex flex-wrap items-center gap-3">
                <input type="hidden" name="type" value="{{ $typeFilter }}">

                <div class="relative flex-1 min-w-[200px]">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau peruntukan..."
                           class="w-full pl-9 pr-3.5 py-2 text-xs rounded-xl fintech-input focus:outline-none">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>

                <select name="category" class="px-3.5 py-2 text-xs rounded-xl fintech-input focus:outline-none">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ $categoryFilter === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>

                <select name="month" class="px-3.5 py-2 text-xs rounded-xl fintech-input focus:outline-none">
                    <option value="">Semua Bulan</option>
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(2026, $m, 1)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>

                <select name="year" class="px-3.5 py-2 text-xs rounded-xl fintech-input focus:outline-none">
                    @for ($y = 2024; $y <= 2027; $y++)
                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>

                <button type="submit" class="px-4 py-2 text-xs font-bold rounded-xl bg-white/10 hover:bg-white/15 border border-white/10 text-white transition">
                    Filter
                </button>

                @if($search || $categoryFilter || $selectedMonth || $typeFilter)
                    <a href="{{ route('expenses.index') }}" class="px-3 py-2 text-xs text-slate-400 hover:text-white transition">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Table Listing -->
        <div class="fintech-card rounded-3xl overflow-hidden border border-white/10">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#090e1c] border-b border-white/10 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Nama Pengeluaran</th>
                            <th class="px-6 py-4">Klasifikasi</th>
                            <th class="px-6 py-4">Kategori & Catatan Detail</th>
                            <th class="px-6 py-4 text-right">Nominal</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($expenses as $expense)
                            <tr class="hover:bg-white/[0.03] transition">
                                <td class="px-6 py-4 font-mono-num text-slate-400 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($expense->date)->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-white text-sm">{{ $expense->title }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($expense->isPriority())
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-rose-500/15 text-rose-300 border border-rose-500/30">
                                            <span class="h-1.5 w-1.5 rounded-full bg-rose-400"></span> Prioritas (Wajib)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-500/15 text-amber-300 border border-amber-500/30">
                                            <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span> Fleksibel (Harian)
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-200">{{ $expense->category }}</div>
                                    <div class="text-slate-400 text-[11px] mt-0.5">{{ $expense->notes ?? 'Tanpa catatan detail' }}</div>
                                </td>
                                <td class="px-6 py-4 text-right font-black font-mono-num text-sm whitespace-nowrap {{ $expense->isPriority() ? 'text-rose-400' : 'text-amber-400' }}">
                                    -Rp {{ number_format($expense->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('expenses.edit', $expense) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-emerald-400 hover:bg-white/5 transition" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>

                                        <form method="POST" action="{{ route('expenses.destroy', $expense) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pengeluaran ini?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-400 hover:bg-white/5 transition" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                    <div class="h-12 w-12 rounded-2xl bg-white/5 border border-white/5 flex items-center justify-center mx-auto mb-3 text-slate-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                    </div>
                                    <p class="font-bold text-white">Belum ada catatan pengeluaran</p>
                                    <p class="text-xs text-slate-400 mt-1">Gunakan tombol "+ Catat Pengeluaran Baru" di atas untuk menambahkan data.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($expenses->hasPages())
                <div class="p-4 border-t border-white/10">
                    {{ $expenses->links() }}
                </div>
            @endif
        </div>

        <!-- MODAL TAMBAH PENGELUARAN -->
        <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showAddModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     class="fixed inset-0 transition-opacity bg-black/80 backdrop-blur-md" @click="showAddModal = false"></div>

                <div x-show="showAddModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block w-full max-w-lg p-7 my-8 overflow-hidden text-left align-middle transition-all transform fintech-card rounded-3xl border border-white/15 shadow-2xl relative">
                    <div class="flex items-center justify-between pb-4 border-b border-white/10">
                        <div class="flex items-center gap-2.5">
                            <div class="h-9 w-9 rounded-xl bg-rose-500/15 border border-rose-500/30 text-rose-400 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            </div>
                            <h3 class="text-base font-bold text-white">Tambah Catatan Pengeluaran</h3>
                        </div>
                        <button @click="showAddModal = false" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-white/5 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('expenses.store') }}" class="mt-5 space-y-4">
                        @csrf

                        <!-- Toggle Prioritas vs Fleksibel -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-2">Klasifikasi Pengeluaran <span class="text-rose-400">*</span></label>
                            <div class="grid grid-cols-2 gap-3 p-1 rounded-2xl bg-[#090e1c] border border-white/10">
                                <label class="flex items-center justify-center gap-2 p-2.5 rounded-xl text-xs font-bold cursor-pointer transition"
                                       :class="expenseType === 'priority' ? 'bg-rose-500/20 text-rose-300 border border-rose-500/40 shadow-[0_0_12px_rgba(244,63,94,0.3)]' : 'text-slate-400 hover:text-white'">
                                    <input type="radio" name="type" value="priority" x-model="expenseType" class="hidden">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    <span>Prioritas (Wajib)</span>
                                </label>

                                <label class="flex items-center justify-center gap-2 p-2.5 rounded-xl text-xs font-bold cursor-pointer transition"
                                       :class="expenseType === 'flexible' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/40 shadow-[0_0_12px_rgba(245,158,11,0.3)]' : 'text-slate-400 hover:text-white'">
                                    <input type="radio" name="type" value="flexible" x-model="expenseType" class="hidden">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    <span>Fleksibel (Harian)</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Nama Pengeluaran <span class="text-rose-400">*</span></label>
                            <input type="text" name="title" required placeholder="Contoh: Sewa Kos, Kopi Sore, Belanja Bulanan"
                                   class="w-full px-3.5 py-2.5 text-xs rounded-xl fintech-input focus:outline-none">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Kategori Pengeluaran <span class="text-rose-400">*</span></label>
                                <select name="category" required class="w-full px-3.5 py-2.5 text-xs rounded-xl fintech-input focus:outline-none">
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
                                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Tanggal Transaksi <span class="text-rose-400">*</span></label>
                                <input type="date" name="date" required value="{{ date('Y-m-d') }}"
                                       class="w-full px-3.5 py-2.5 text-xs rounded-xl fintech-input focus:outline-none">
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-xs font-semibold text-slate-300">Nominal Pengeluaran (IDR) <span class="text-rose-400">*</span></label>
                                <span class="text-xs font-mono-num font-bold text-rose-400" x-text="window.formatIDR(inputAmount)"></span>
                            </div>
                            <div class="relative">
                                <span class="absolute left-3.5 top-2.5 text-xs font-bold text-slate-500">Rp</span>
                                <input type="number" step="0.01" min="1" name="amount" required x-model.number="inputAmount" placeholder="0"
                                       class="w-full pl-10 pr-3.5 py-2.5 text-sm font-bold font-mono-num rounded-xl fintech-input focus:outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Catatan Detail Peruntukan <span class="text-slate-500">(Opsional)</span></label>
                            <textarea name="notes" rows="2" placeholder="Tuliskan keterangan detail keperluan, tempat, atau peruntukan..."
                                      class="w-full px-3.5 py-2 text-xs rounded-xl fintech-input focus:outline-none"></textarea>
                        </div>

                        <div class="pt-4 flex items-center justify-end gap-3 border-t border-white/10">
                            <button type="button" @click="showAddModal = false" class="px-4 py-2.5 text-xs font-bold rounded-xl text-slate-400 hover:text-white hover:bg-white/5 transition">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2.5 text-xs font-bold rounded-xl bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-400 hover:to-pink-400 text-white transition shadow-[0_0_15px_rgba(244,63,94,0.3)]">
                                Simpan Pengeluaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
