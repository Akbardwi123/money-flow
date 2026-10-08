<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        <div class="flex items-center justify-between">
            <a href="{{ route('allocations.index') }}" class="text-xs font-bold text-slate-400 hover:text-cyan-400 flex items-center gap-1.5 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                <span>&larr; Kembali ke Hub Alokasi Investasi</span>
            </a>
        </div>

        <div class="fintech-card rounded-3xl p-7 sm:p-8"
             x-data="{
                 allocType: '{{ old('type', $allocation->type) }}',
                 inputAmount: {{ old('amount', $allocation->amount) }},
                 finCategories: ['Saham', 'Crypto', 'Reksa Dana', 'Obligasi / SBN', 'Emas', 'P2P Lending', 'Deposito'],
                 skillCategories: ['Kursus / Lab Cybersecurity', 'Sertifikasi Internasional', 'Buku / Literatur', 'Bootcamp Coding', 'Mentoring / Konsultasi', 'Workshop']
             }">

            <div class="flex items-center gap-3.5 pb-5 border-b border-white/10">
                <div class="h-10 w-10 rounded-xl bg-cyan-500/15 border border-cyan-500/30 text-cyan-400 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div>
                    <h1 class="text-base font-bold text-white">Edit Alokasi Investasi</h1>
                    <p class="text-xs text-slate-400">Perbarui nominal aset finansial atau progres target skill.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('allocations.update', $allocation) }}" class="mt-6 space-y-4">
                @csrf
                @method('PUT')

                <!-- Toggle Finansial vs Skill -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">Pilihan Jalur Investasi <span class="text-rose-400">*</span></label>
                    <div class="grid grid-cols-2 gap-3 p-1 rounded-2xl bg-[#090e1c] border border-white/10">
                        <label class="flex items-center justify-center gap-2 p-2.5 rounded-xl text-xs font-bold cursor-pointer transition"
                               :class="allocType === 'financial' ? 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/40 shadow-[0_0_12px_rgba(6,182,212,0.3)]' : 'text-slate-400 hover:text-white'">
                            <input type="radio" name="type" value="financial" x-model="allocType" class="hidden">
                            <span>Investasi Finansial</span>
                        </label>

                        <label class="flex items-center justify-center gap-2 p-2.5 rounded-xl text-xs font-bold cursor-pointer transition"
                               :class="allocType === 'skill' ? 'bg-violet-500/20 text-violet-300 border border-violet-500/40 shadow-[0_0_12px_rgba(139,92,246,0.3)]' : 'text-slate-400 hover:text-white'">
                            <input type="radio" name="type" value="skill" x-model="allocType" class="hidden">
                            <span>Leher ke Atas (Skill)</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5" x-text="allocType === 'financial' ? 'Nama Aset / Ticker Saham / Token *' : 'Nama Kelas / Sertifikasi / Buku *'"></label>
                    <input type="text" name="title" required value="{{ old('title', $allocation->title) }}"
                           class="w-full px-3.5 py-2.5 text-xs rounded-xl fintech-input focus:outline-none">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Kategori Instrumen <span class="text-rose-400">*</span></label>
                        <select name="category" required class="w-full px-3.5 py-2.5 text-xs rounded-xl fintech-input focus:outline-none">
                            <template x-if="allocType === 'financial'">
                                <template x-for="cat in finCategories" :key="cat">
                                    <option :value="cat" :selected="cat === '{{ old('category', $allocation->category) }}'" x-text="cat"></option>
                                </template>
                            </template>
                            <template x-if="allocType === 'skill'">
                                <template x-for="cat in skillCategories" :key="cat">
                                    <option :value="cat" :selected="cat === '{{ old('category', $allocation->category) }}'" x-text="cat"></option>
                                </template>
                            </template>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5" x-text="allocType === 'financial' ? 'Platform / Sekuritas' : 'Penyelenggara / Penerbit'"></label>
                        <input type="text" name="platform" value="{{ old('platform', $allocation->platform) }}"
                               class="w-full px-3.5 py-2.5 text-xs rounded-xl fintech-input focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Tanggal Alokasi <span class="text-rose-400">*</span></label>
                        <input type="date" name="date" required value="{{ old('date', \Carbon\Carbon::parse($allocation->date)->format('Y-m-d')) }}"
                               class="w-full px-3.5 py-2.5 text-xs rounded-xl fintech-input focus:outline-none">
                    </div>

                    <template x-if="allocType === 'skill'">
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Status Progres Target</label>
                            <select name="status" class="w-full px-3.5 py-2.5 text-xs rounded-xl fintech-input focus:outline-none">
                                <option value="planned" {{ old('status', $allocation->status) === 'planned' ? 'selected' : '' }}>Direncanakan</option>
                                <option value="in_progress" {{ old('status', $allocation->status) === 'in_progress' ? 'selected' : '' }}>Sedang Belajar</option>
                                <option value="completed" {{ old('status', $allocation->status) === 'completed' ? 'selected' : '' }}>Selesai / Lulus</option>
                            </select>
                        </div>
                    </template>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-semibold text-slate-300">Nominal Alokasi (IDR) <span class="text-rose-400">*</span></label>
                        <span class="text-xs font-mono-num font-bold text-cyan-400" x-text="window.formatIDR(inputAmount)"></span>
                    </div>
                    <div class="relative">
                        <span class="absolute left-3.5 top-2.5 text-xs font-bold text-slate-500">Rp</span>
                        <input type="number" step="0.01" min="1" name="amount" required x-model.number="inputAmount"
                               class="w-full pl-10 pr-3.5 py-2.5 text-sm font-bold font-mono-num rounded-xl fintech-input focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Target / Keterangan Objektif</label>
                    <textarea name="target_objective" rows="3"
                              class="w-full px-3.5 py-2 text-xs rounded-xl fintech-input focus:outline-none">{{ old('target_objective', $allocation->target_objective) }}</textarea>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-white/10">
                    <a href="{{ route('allocations.index') }}" class="px-4 py-2.5 text-xs font-bold rounded-xl text-slate-400 hover:text-white hover:bg-white/5 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 text-xs font-bold uppercase tracking-wider rounded-xl bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-white transition shadow-[0_0_15px_rgba(6,182,212,0.3)]">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
