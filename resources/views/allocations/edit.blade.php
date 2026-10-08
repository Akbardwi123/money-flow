<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        <div class="flex items-center justify-between">
            <a href="{{ route('allocations.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 flex items-center gap-1.5 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                <span>Kembali ke Hub Alokasi Investasi</span>
            </a>
        </div>

        <div class="p-8 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm"
             x-data="{
                 allocType: '{{ old('type', $allocation->type) }}',
                 inputAmount: {{ old('amount', $allocation->amount) }},
                 finCategories: ['Saham', 'Crypto', 'Reksa Dana', 'Obligasi / SBN', 'Emas', 'P2P Lending', 'Deposito'],
                 skillCategories: ['Kursus / Lab Cybersecurity', 'Sertifikasi Internasional', 'Buku / Literatur', 'Bootcamp Coding', 'Mentoring / Konsultasi', 'Workshop']
             }">

            <div class="flex items-center gap-3 pb-6 border-b border-slate-100 dark:border-slate-800">
                <div class="h-10 w-10 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-slate-900 dark:text-white">Edit Alokasi Investasi</h1>
                    <p class="text-xs text-slate-500">Perbarui nominal aset finansial atau progres target skill.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('allocations.update', $allocation) }}" class="mt-6 space-y-4">
                @csrf
                @method('PUT')

                <!-- Toggle Finansial vs Skill -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">Pilihan Jalur Investasi <span class="text-rose-500">*</span></label>
                    <div class="grid grid-cols-2 gap-3 p-1 rounded-2xl bg-slate-100 dark:bg-slate-800">
                        <label class="flex items-center justify-center gap-2 p-2.5 rounded-xl text-xs font-bold cursor-pointer transition"
                               :class="allocType === 'financial' ? 'bg-white dark:bg-slate-900 text-cyan-600 dark:text-cyan-400 shadow-sm border border-slate-200 dark:border-slate-700' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900'">
                            <input type="radio" name="type" value="financial" x-model="allocType" class="hidden">
                            <span>Investasi Finansial</span>
                        </label>

                        <label class="flex items-center justify-center gap-2 p-2.5 rounded-xl text-xs font-bold cursor-pointer transition"
                               :class="allocType === 'skill' ? 'bg-white dark:bg-slate-900 text-violet-600 dark:text-violet-400 shadow-sm border border-slate-200 dark:border-slate-700' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900'">
                            <input type="radio" name="type" value="skill" x-model="allocType" class="hidden">
                            <span>Leher ke Atas (Skill)</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1" x-text="allocType === 'financial' ? 'Nama Aset / Ticker Saham / Token *' : 'Nama Kelas / Sertifikasi / Buku *'"></label>
                    <input type="text" name="title" required value="{{ old('title', $allocation->title) }}"
                           class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Kategori Instrumen <span class="text-rose-500">*</span></label>
                        <select name="category" required class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none">
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
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1" x-text="allocType === 'financial' ? 'Platform / Sekuritas' : 'Penyelenggara / Penerbit'"></label>
                        <input type="text" name="platform" value="{{ old('platform', $allocation->platform) }}"
                               class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Tanggal Alokasi <span class="text-rose-500">*</span></label>
                        <input type="date" name="date" required value="{{ old('date', \Carbon\Carbon::parse($allocation->date)->format('Y-m-d')) }}"
                               class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <template x-if="allocType === 'skill'">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Status Progres Target</label>
                            <select name="status" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                <option value="planned" {{ old('status', $allocation->status) === 'planned' ? 'selected' : '' }}>Direncanakan</option>
                                <option value="in_progress" {{ old('status', $allocation->status) === 'in_progress' ? 'selected' : '' }}>Sedang Belajar</option>
                                <option value="completed" {{ old('status', $allocation->status) === 'completed' ? 'selected' : '' }}>Selesai / Lulus</option>
                            </select>
                        </div>
                    </template>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">Nominal Alokasi (IDR) <span class="text-rose-500">*</span></label>
                        <span class="text-xs font-mono-num font-bold text-indigo-600 dark:text-indigo-400" x-text="window.formatIDR(inputAmount)"></span>
                    </div>
                    <div class="relative">
                        <span class="absolute left-3.5 top-2.5 text-xs font-bold text-slate-400">Rp</span>
                        <input type="number" step="0.01" min="1" name="amount" required x-model.number="inputAmount"
                               class="w-full pl-10 pr-3.5 py-2.5 text-sm font-bold font-mono-num rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Target / Keterangan Objektif</label>
                    <textarea name="target_objective" rows="3"
                              class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none">{{ old('target_objective', $allocation->target_objective) }}</textarea>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100 dark:border-slate-800">
                    <a href="{{ route('allocations.index') }}" class="px-4 py-2.5 text-xs font-semibold rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 text-xs font-bold rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white transition shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
