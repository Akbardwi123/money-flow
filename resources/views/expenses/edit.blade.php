<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        <div class="flex items-center justify-between">
            <a href="{{ route('expenses.index') }}" class="text-xs font-bold text-slate-400 hover:text-rose-400 flex items-center gap-1.5 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                <span>&larr; Kembali ke Daftar Pengeluaran</span>
            </a>
        </div>

        <div class="fintech-card rounded-3xl p-7 sm:p-8"
             x-data="{
                 expenseType: '{{ old('type', $expense->type) }}',
                 inputAmount: {{ old('amount', $expense->amount) }},
                 categoriesPriority: ['Tempat Tinggal', 'Utilitas', 'Makanan Pokok', 'Internet & Komunikasi', 'Asuransi & Proteksi', 'Cicilan & Kewajiban', 'Kesehatan'],
                 categoriesFlexible: ['Kuliner & Nongkrong', 'Hiburan & Langganan', 'Hobi & Lifestyle', 'Shopping', 'Transportasi Tambahan', 'Hadiah & Donasi', 'Lainnya']
             }">

            <div class="flex items-center gap-3.5 pb-5 border-b border-white/10">
                <div class="h-10 w-10 rounded-xl bg-rose-500/15 border border-rose-500/30 text-rose-400 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div>
                    <h1 class="text-base font-bold text-white">Edit Catatan Pengeluaran</h1>
                    <p class="text-xs text-slate-400">Perbarui nominal, kategori, peruntukan atau klasifikasi.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('expenses.update', $expense) }}" class="mt-6 space-y-4">
                @csrf
                @method('PUT')

                <!-- Toggle Prioritas vs Fleksibel -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">Klasifikasi Pengeluaran <span class="text-rose-400">*</span></label>
                    <div class="grid grid-cols-2 gap-3 p-1 rounded-2xl bg-[#090e1c] border border-white/10">
                        <label class="flex items-center justify-center gap-2 p-2.5 rounded-xl text-xs font-bold cursor-pointer transition"
                               :class="expenseType === 'priority' ? 'bg-rose-500/20 text-rose-300 border border-rose-500/40 shadow-[0_0_12px_rgba(244,63,94,0.3)]' : 'text-slate-400 hover:text-white'">
                            <input type="radio" name="type" value="priority" x-model="expenseType" class="hidden">
                            <span>Prioritas (Wajib)</span>
                        </label>

                        <label class="flex items-center justify-center gap-2 p-2.5 rounded-xl text-xs font-bold cursor-pointer transition"
                               :class="expenseType === 'flexible' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/40 shadow-[0_0_12px_rgba(245,158,11,0.3)]' : 'text-slate-400 hover:text-white'">
                            <input type="radio" name="type" value="flexible" x-model="expenseType" class="hidden">
                            <span>Fleksibel (Harian)</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Nama Pengeluaran <span class="text-rose-400">*</span></label>
                    <input type="text" name="title" required value="{{ old('title', $expense->title) }}"
                           class="w-full px-3.5 py-2.5 text-xs rounded-xl fintech-input focus:outline-none">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Kategori Pengeluaran <span class="text-rose-400">*</span></label>
                        <select name="category" required class="w-full px-3.5 py-2.5 text-xs rounded-xl fintech-input focus:outline-none">
                            <template x-if="expenseType === 'priority'">
                                <template x-for="cat in categoriesPriority" :key="cat">
                                    <option :value="cat" :selected="cat === '{{ old('category', $expense->category) }}'" x-text="cat"></option>
                                </template>
                            </template>
                            <template x-if="expenseType === 'flexible'">
                                <template x-for="cat in categoriesFlexible" :key="cat">
                                    <option :value="cat" :selected="cat === '{{ old('category', $expense->category) }}'" x-text="cat"></option>
                                </template>
                            </template>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Tanggal Transaksi <span class="text-rose-400">*</span></label>
                        <input type="date" name="date" required value="{{ old('date', \Carbon\Carbon::parse($expense->date)->format('Y-m-d')) }}"
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
                        <input type="number" step="0.01" min="1" name="amount" required x-model.number="inputAmount"
                               class="w-full pl-10 pr-3.5 py-2.5 text-sm font-bold font-mono-num rounded-xl fintech-input focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Catatan Detail Peruntukan <span class="text-slate-500">(Opsional)</span></label>
                    <textarea name="notes" rows="3"
                              class="w-full px-3.5 py-2 text-xs rounded-xl fintech-input focus:outline-none">{{ old('notes', $expense->notes) }}</textarea>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-white/10">
                    <a href="{{ route('expenses.index') }}" class="px-4 py-2.5 text-xs font-bold rounded-xl text-slate-400 hover:text-white hover:bg-white/5 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 text-xs font-bold uppercase tracking-wider rounded-xl bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-400 hover:to-pink-400 text-white transition shadow-[0_0_15px_rgba(244,63,94,0.3)]">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
