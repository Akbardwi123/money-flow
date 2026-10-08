<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        <div class="flex items-center justify-between">
            <a href="{{ route('incomes.index') }}" class="text-xs font-bold text-slate-400 hover:text-emerald-400 flex items-center gap-1.5 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                <span>&larr; Kembali ke Daftar Pemasukan</span>
            </a>
        </div>

        <div class="fintech-card rounded-3xl p-7 sm:p-8"
             x-data="{ inputAmount: {{ $income->amount }} }">
            <div class="flex items-center gap-3.5 pb-5 border-b border-white/10">
                <div class="h-10 w-10 rounded-xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div>
                    <h1 class="text-base font-bold text-white">Edit Catatan Pemasukan</h1>
                    <p class="text-xs text-slate-400">Perbarui informasi nominal, sumber arus masuk, atau catatan.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('incomes.update', $income) }}" class="mt-6 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Judul / Deskripsi Pemasukan <span class="text-rose-400">*</span></label>
                    <input type="text" name="title" required value="{{ old('title', $income->title) }}"
                           class="w-full px-3.5 py-2.5 text-xs rounded-xl fintech-input focus:outline-none">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Sumber Arus Masuk <span class="text-rose-400">*</span></label>
                        <select name="source" required class="w-full px-3.5 py-2.5 text-xs rounded-xl fintech-input focus:outline-none">
                            @foreach(['Gaji Pokok', 'Freelance', 'Bisnis / Usaha', 'Dividen / Passive Income', 'Bonus & THR', 'Penjualan Aset', 'Lainnya'] as $src)
                                <option value="{{ $src }}" {{ old('source', $income->source) === $src ? 'selected' : '' }}>{{ $src }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Tanggal Transaksi <span class="text-rose-400">*</span></label>
                        <input type="date" name="date" required value="{{ old('date', \Carbon\Carbon::parse($income->date)->format('Y-m-d')) }}"
                               class="w-full px-3.5 py-2.5 text-xs rounded-xl fintech-input focus:outline-none">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-semibold text-slate-300">Nominal Pemasukan (IDR) <span class="text-rose-400">*</span></label>
                        <span class="text-xs font-mono-num font-bold text-emerald-400" x-text="window.formatIDR(inputAmount)"></span>
                    </div>
                    <div class="relative">
                        <span class="absolute left-3.5 top-2.5 text-xs font-bold text-slate-500">Rp</span>
                        <input type="number" step="0.01" min="1" name="amount" required x-model.number="inputAmount"
                               class="w-full pl-10 pr-3.5 py-2.5 text-sm font-bold font-mono-num rounded-xl fintech-input focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Catatan Tambahan (Opsional)</label>
                    <textarea name="notes" rows="3"
                              class="w-full px-3.5 py-2 text-xs rounded-xl fintech-input focus:outline-none">{{ old('notes', $income->notes) }}</textarea>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-white/10">
                    <a href="{{ route('incomes.index') }}" class="px-4 py-2.5 text-xs font-bold rounded-xl text-slate-400 hover:text-white hover:bg-white/5 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 text-xs font-bold uppercase tracking-wider rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-slate-950 transition shadow-[0_0_15px_rgba(16,185,129,0.3)]">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
