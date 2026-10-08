<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6"
         x-data="{
             showAddModal: false,
             allocType: '{{ $typeFilter ?? 'financial' }}',
             inputAmount: 0,
             remainingSurplus: {{ max(0, $metrics['remaining_surplus']) }},
             finCategories: ['Saham', 'Crypto', 'Reksa Dana', 'Obligasi / SBN', 'Emas', 'P2P Lending', 'Deposito'],
             skillCategories: ['Kursus / Lab Cybersecurity', 'Sertifikasi Internasional', 'Buku / Literatur', 'Bootcamp Coding', 'Mentoring / Konsultasi', 'Workshop']
         }">

        <!-- Top Header & Action -->
        <div class="card-solid p-5 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-lg font-bold text-slate-900 dark:text-white">Alokasi Surplus Investasi</h1>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800/60">Surplus</span>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Alokasikan surplus dana bersih ke <strong class="text-slate-800 dark:text-slate-200">Investasi Finansial</strong> dan <strong class="text-slate-800 dark:text-slate-200">Pengembangan Keahlian (Skill)</strong>.</p>
            </div>

            <button @click="showAddModal = true" class="px-4 py-2 rounded-lg bg-slate-900 hover:bg-slate-800 text-white dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100 font-semibold text-xs transition shadow-sm flex items-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Alokasikan Surplus</span>
            </button>
        </div>

        <!-- REAL-TIME SURPLUS ALLOCATION GAUGE -->
        <div class="card-solid p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-center">
                <!-- Total Gross Surplus -->
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Surplus Periode</span>
                    <p class="text-2xl sm:text-3xl font-bold font-mono-num text-slate-900 dark:text-white mt-1">
                        Rp {{ number_format($metrics['gross_surplus'], 0, ',', '.') }}
                    </p>
                    <span class="text-[11px] text-slate-500 mt-1 inline-block">Pemasukan − Pengeluaran</span>
                </div>

                <!-- Financial Allocation -->
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200/80 dark:border-slate-700/60">
                    <div class="flex items-center gap-1.5 text-xs font-semibold text-blue-700 dark:text-blue-400 mb-1">
                        <span class="h-2 w-2 rounded-full bg-blue-600"></span>
                        <span>Investasi Finansial</span>
                    </div>
                    <p class="text-xl font-bold font-mono-num text-slate-900 dark:text-white">
                        Rp {{ number_format($metrics['financial_investment'], 0, ',', '.') }}
                    </p>
                    <p class="text-[10px] text-slate-500 mt-0.5 font-mono-num">{{ $metrics['financial_allocation_percent'] }}% dari surplus</p>
                </div>

                <!-- Skill Allocation -->
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200/80 dark:border-slate-700/60">
                    <div class="flex items-center gap-1.5 text-xs font-semibold text-indigo-700 dark:text-indigo-400 mb-1">
                        <span class="h-2 w-2 rounded-full bg-indigo-600"></span>
                        <span>Investasi Skill</span>
                    </div>
                    <p class="text-xl font-bold font-mono-num text-slate-900 dark:text-white">
                        Rp {{ number_format($metrics['skill_investment'], 0, ',', '.') }}
                    </p>
                    <p class="text-[10px] text-slate-500 mt-0.5 font-mono-num">{{ $metrics['skill_allocation_percent'] }}% dari surplus</p>
                </div>

                <!-- Sisa Surplus Siap Alokasi -->
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200/80 dark:border-slate-700/60">
                    <div class="flex items-center gap-1.5 text-xs font-semibold text-emerald-700 dark:text-emerald-400 mb-1">
                        <span class="h-2 w-2 rounded-full bg-emerald-600"></span>
                        <span>Sisa Surplus Likuid</span>
                    </div>
                    <p class="text-xl font-bold font-mono-num {{ $metrics['remaining_surplus'] >= 0 ? 'text-emerald-700 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                        Rp {{ number_format($metrics['remaining_surplus'], 0, ',', '.') }}
                    </p>
                    <p class="text-[10px] text-slate-500 mt-0.5">Siap dialokasikan</p>
                </div>
            </div>
        </div>

        <!-- DUAL COLUMN ALLOCATION CARDS -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- COLUMN A: INVESTASI FINANSIAL -->
            <div class="card-solid p-6 space-y-4">
                <div class="flex items-center justify-between pb-3.5 border-b border-slate-100 dark:border-slate-800">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white">1. Investasi Finansial</h2>
                        <p class="text-xs text-slate-500">Saham, Reksa Dana, Crypto, SBN, Deposito</p>
                    </div>

                    <span class="text-xs font-bold font-mono-num text-slate-900 dark:text-white">
                        Total: Rp {{ number_format($metrics['financial_investment'], 0, ',', '.') }}
                    </span>
                </div>

                <div class="space-y-2.5">
                    @forelse($financialList as $fin)
                        <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-200/70 dark:border-slate-800">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-semibold text-slate-900 dark:text-white text-xs">{{ $fin->title }}</h3>
                                        <span class="px-1.5 py-0.2 rounded text-[10px] font-medium bg-blue-50 text-blue-700 dark:bg-blue-950/80 dark:text-blue-300 border border-blue-200 dark:border-blue-900">
                                            {{ $fin->category }}
                                        </span>
                                    </div>
                                    @if($fin->platform)
                                        <p class="text-xs text-slate-500 mt-1">Platform: <strong class="text-slate-700 dark:text-slate-300">{{ $fin->platform }}</strong></p>
                                    @endif
                                    @if($fin->target_objective)
                                        <p class="text-[11px] text-slate-500 mt-0.5 italic">"{{ $fin->target_objective }}"</p>
                                    @endif
                                </div>

                                <div class="text-right shrink-0">
                                    <div class="text-xs font-bold font-mono-num text-slate-900 dark:text-white">
                                        Rp {{ number_format($fin->amount, 0, ',', '.') }}
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-0.5 font-mono-num">{{ \Carbon\Carbon::parse($fin->date)->translatedFormat('d M Y') }}</p>

                                    <div class="mt-2 flex items-center justify-end gap-1.5">
                                        <a href="{{ route('allocations.edit', $fin) }}" class="p-1 rounded text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition" title="Edit">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form method="POST" action="{{ route('allocations.destroy', $fin) }}" onsubmit="return confirm('Hapus alokasi ini?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 rounded text-slate-400 hover:text-rose-600 transition" title="Hapus">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-xs text-slate-400">
                            Belum ada alokasi aset finansial pada periode ini.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- COLUMN B: INVESTASI LEHER KE ATAS (SKILL) -->
            <div class="card-solid p-6 space-y-4">
                <div class="flex items-center justify-between pb-3.5 border-b border-slate-100 dark:border-slate-800">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white">2. Investasi Keahlian (Skill)</h2>
                        <p class="text-xs text-slate-500">Buku, Pelatihan, Kursus, Sertifikasi</p>
                    </div>

                    <span class="text-xs font-bold font-mono-num text-slate-900 dark:text-white">
                        Total: Rp {{ number_format($metrics['skill_investment'], 0, ',', '.') }}
                    </span>
                </div>

                <div class="space-y-2.5">
                    @forelse($skillList as $sk)
                        <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-200/70 dark:border-slate-800">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-semibold text-slate-900 dark:text-white text-xs">{{ $sk->title }}</h3>
                                        <span class="px-1.5 py-0.2 rounded text-[10px] font-medium bg-indigo-50 text-indigo-700 dark:bg-indigo-950/80 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-900">
                                            {{ $sk->category }}
                                        </span>
                                    </div>
                                    @if($sk->platform)
                                        <p class="text-xs text-slate-500 mt-1">Provider: <strong class="text-slate-700 dark:text-slate-300">{{ $sk->platform }}</strong></p>
                                    @endif
                                    @if($sk->target_objective)
                                        <p class="text-[11px] text-slate-500 mt-0.5 italic">Target: "{{ $sk->target_objective }}"</p>
                                    @endif

                                    <!-- Status Pill -->
                                    <div class="mt-2">
                                        @if($sk->status === 'completed')
                                            <span class="inline-flex items-center gap-1 px-1.5 py-0.2 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                                Selesai / Lulus
                                            </span>
                                        @elseif($sk->status === 'in_progress')
                                            <span class="inline-flex items-center gap-1 px-1.5 py-0.2 rounded text-[10px] font-semibold bg-amber-50 text-amber-700 dark:bg-amber-950/80 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                                Sedang Berjalan
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-1.5 py-0.2 rounded text-[10px] font-semibold bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400">
                                                Direncanakan
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="text-right shrink-0">
                                    <div class="text-xs font-bold font-mono-num text-slate-900 dark:text-white">
                                        Rp {{ number_format($sk->amount, 0, ',', '.') }}
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-0.5 font-mono-num">{{ \Carbon\Carbon::parse($sk->date)->translatedFormat('d M Y') }}</p>

                                    <div class="mt-2 flex items-center justify-end gap-1.5">
                                        <a href="{{ route('allocations.edit', $sk) }}" class="p-1 rounded text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition" title="Edit">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form method="POST" action="{{ route('allocations.destroy', $sk) }}" onsubmit="return confirm('Hapus alokasi ini?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 rounded text-slate-400 hover:text-rose-600 transition" title="Hapus">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-xs text-slate-400">
                            Belum ada alokasi investasi skill pada periode ini.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- MODAL ALOKASI SURPLUS BARU -->
        <div x-show="showAddModal" x-cloak style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showAddModal" x-transition.opacity
                     class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs" @click="showAddModal = false"></div>

                <div x-show="showAddModal" x-transition
                     class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform card-solid shadow-xl relative">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Alokasikan Surplus Dana</h3>
                        <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('allocations.store') }}" class="mt-4 space-y-4">
                        @csrf

                        <!-- Toggle Finansial vs Skill -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">Pilihan Jalur Investasi <span class="text-rose-500">*</span></label>
                            <div class="grid grid-cols-2 gap-2 p-1 rounded-lg bg-slate-100 dark:bg-slate-800">
                                <label class="flex items-center justify-center gap-1.5 p-2 rounded-md text-xs font-semibold cursor-pointer transition"
                                       :class="allocType === 'financial' ? 'bg-white dark:bg-slate-900 text-blue-700 dark:text-blue-400 shadow-xs border border-slate-200 dark:border-slate-700' : 'text-slate-600 dark:text-slate-400'">
                                    <input type="radio" name="type" value="financial" x-model="allocType" class="hidden">
                                    <span>Investasi Finansial</span>
                                </label>

                                <label class="flex items-center justify-center gap-1.5 p-2 rounded-md text-xs font-semibold cursor-pointer transition"
                                       :class="allocType === 'skill' ? 'bg-white dark:bg-slate-900 text-indigo-700 dark:text-indigo-400 shadow-xs border border-slate-200 dark:border-slate-700' : 'text-slate-600 dark:text-slate-400'">
                                    <input type="radio" name="type" value="skill" x-model="allocType" class="hidden">
                                    <span>Investasi Skill</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1" x-text="allocType === 'financial' ? 'Nama Aset / Ticker / Instrumen *' : 'Nama Pelatihan / Buku / Kursus *'"></label>
                            <input type="text" name="title" required :placeholder="allocType === 'financial' ? 'Contoh: Saham BBCA, Reksa Dana Pasar Uang' : 'Contoh: Kursus Keamanan Siber, Buku Arsitektur Software'"
                                   class="w-full px-3 py-2 text-xs input-solid">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Kategori <span class="text-rose-500">*</span></label>
                                <select name="category" required class="w-full px-3 py-2 text-xs input-solid">
                                    <template x-if="allocType === 'financial'">
                                        <template x-for="cat in finCategories" :key="cat">
                                            <option :value="cat" x-text="cat"></option>
                                        </template>
                                    </template>
                                    <template x-if="allocType === 'skill'">
                                        <template x-for="cat in skillCategories" :key="cat">
                                            <option :value="cat" x-text="cat"></option>
                                        </template>
                                    </template>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1" x-text="allocType === 'financial' ? 'Platform / Sekuritas' : 'Penyelenggara / Penerbit'"></label>
                                <input type="text" name="platform" :placeholder="allocType === 'financial' ? 'Contoh: Stockbit, Bibit' : 'Contoh: Dicoding, Coursera'"
                                       class="w-full px-3 py-2 text-xs input-solid">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Tanggal Alokasi <span class="text-rose-500">*</span></label>
                                <input type="date" name="date" required value="{{ date('Y-m-d') }}"
                                       class="w-full px-3 py-2 text-xs input-solid">
                            </div>

                            <template x-if="allocType === 'skill'">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Status Progres</label>
                                    <select name="status" class="w-full px-3 py-2 text-xs input-solid">
                                        <option value="planned">Direncanakan</option>
                                        <option value="in_progress">Sedang Berjalan</option>
                                        <option value="completed">Selesai / Lulus</option>
                                    </select>
                                </div>
                            </template>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">Nominal Alokasi (IDR) <span class="text-rose-500">*</span></label>
                                <span class="text-xs font-mono-num font-bold text-slate-900 dark:text-white" x-text="window.formatIDR(inputAmount)"></span>
                            </div>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-xs font-bold text-slate-400">Rp</span>
                                <input type="number" step="0.01" min="1" name="amount" required x-model.number="inputAmount" placeholder="0"
                                       class="w-full pl-9 pr-3 py-2 text-sm font-bold font-mono-num input-solid">
                            </div>

                            <!-- Real-time surplus check warning -->
                            <div x-show="inputAmount > remainingSurplus && remainingSurplus > 0" class="mt-2 p-2.5 rounded-lg bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-300 text-xs flex items-center gap-2">
                                <svg class="w-4 h-4 shrink-0 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                <span>Nominal melebihi sisa surplus periode (<span class="font-bold font-mono-num" x-text="window.formatIDR(remainingSurplus)"></span>).</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Target / Keterangan Objektif</label>
                            <textarea name="target_objective" rows="2" placeholder="Tujuan atau rencana pencapaian..."
                                      class="w-full px-3 py-1.5 text-xs input-solid"></textarea>
                        </div>

                        <div class="pt-3 flex items-center justify-end gap-2 border-t border-slate-100 dark:border-slate-800">
                            <button type="button" @click="showAddModal = false" class="px-3.5 py-1.5 text-xs font-medium rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-1.5 text-xs font-semibold rounded-lg bg-slate-900 text-white dark:bg-white dark:text-slate-900 hover:bg-slate-800 transition shadow-sm">
                                Simpan Alokasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
