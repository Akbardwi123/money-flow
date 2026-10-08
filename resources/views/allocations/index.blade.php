<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8"
         x-data="{
             showAddModal: false,
             allocType: '{{ $typeFilter ?? 'financial' }}',
             inputAmount: 0,
             remainingSurplus: {{ max(0, $metrics['remaining_surplus']) }},
             finCategories: ['Saham', 'Crypto', 'Reksa Dana', 'Obligasi / SBN', 'Emas', 'P2P Lending', 'Deposito'],
             skillCategories: ['Kursus / Lab Cybersecurity', 'Sertifikasi Internasional', 'Buku / Literatur', 'Bootcamp Coding', 'Mentoring / Konsultasi', 'Workshop']
         }">

        <!-- Top Header & Action -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="h-12 w-12 rounded-2xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl font-extrabold text-slate-900 dark:text-white">Hub Alokasi Investasi</h1>
                        <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-indigo-100 text-indigo-700 dark:bg-indigo-950/80 dark:text-indigo-300">Surplus Engine</span>
                    </div>
                    <p class="text-xs text-slate-500">Distribusikan surplus dana bersih ke <strong>Investasi Finansial</strong> dan <strong>Investasi Leher ke Atas (Skill)</strong>.</p>
                </div>
            </div>

            <button @click="showAddModal = true" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 via-teal-600 to-emerald-600 hover:from-indigo-500 hover:to-emerald-500 text-white font-bold text-xs transition shadow-md shadow-indigo-500/20 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>+ Alokasikan Surplus Baru</span>
            </button>
        </div>

        <!-- REAL-TIME SURPLUS ALLOCATION GAUGE -->
        <div class="p-6 sm:p-8 rounded-3xl bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 border border-slate-800 text-white shadow-xl relative overflow-hidden">
            <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-center">
                <!-- Total Gross Surplus -->
                <div>
                    <span class="text-xs uppercase font-bold text-slate-400 tracking-wider">Total Surplus Periode</span>
                    <p class="text-2xl sm:text-3xl font-extrabold font-mono-num text-white mt-1">
                        Rp {{ number_format($metrics['gross_surplus'], 0, ',', '.') }}
                    </p>
                    <span class="text-[11px] text-emerald-400 mt-1 inline-block">Surplus Bersih = In - Out</span>
                </div>

                <!-- Financial Allocation -->
                <div class="p-4 rounded-2xl bg-slate-950/60 border border-cyan-500/30">
                    <div class="flex items-center gap-1.5 text-xs font-semibold text-cyan-300 mb-1">
                        <span class="h-2 w-2 rounded-full bg-cyan-400"></span>
                        <span>Investasi Finansial</span>
                    </div>
                    <p class="text-xl font-bold font-mono-num text-white">
                        Rp {{ number_format($metrics['financial_investment'], 0, ',', '.') }}
                    </p>
                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $metrics['financial_allocation_percent'] }}% dari total surplus</p>
                </div>

                <!-- Skill Allocation -->
                <div class="p-4 rounded-2xl bg-slate-950/60 border border-violet-500/30">
                    <div class="flex items-center gap-1.5 text-xs font-semibold text-violet-300 mb-1">
                        <span class="h-2 w-2 rounded-full bg-violet-400"></span>
                        <span>Leher ke Atas (Skill)</span>
                    </div>
                    <p class="text-xl font-bold font-mono-num text-white">
                        Rp {{ number_format($metrics['skill_investment'], 0, ',', '.') }}
                    </p>
                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $metrics['skill_allocation_percent'] }}% dari total surplus</p>
                </div>

                <!-- Sisa Surplus Siap Alokasi -->
                <div class="p-4 rounded-2xl bg-slate-950/60 border border-emerald-500/40">
                    <div class="flex items-center gap-1.5 text-xs font-semibold text-emerald-300 mb-1">
                        <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>Sisa Surplus Siap Alokasi</span>
                    </div>
                    <p class="text-xl font-bold font-mono-num {{ $metrics['remaining_surplus'] >= 0 ? 'text-emerald-300' : 'text-rose-400' }}">
                        Rp {{ number_format($metrics['remaining_surplus'], 0, ',', '.') }}
                    </p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Dana likuid tersisa</p>
                </div>
            </div>
        </div>

        <!-- DUAL COLUMN ALLOCATION CARDS -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- COLUMN A: INVESTASI FINANSIAL -->
            <div class="p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2.5">
                        <div class="h-9 w-9 rounded-xl bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-900 dark:text-white">1. Investasi Finansial</h2>
                            <p class="text-xs text-slate-500">Saham, Crypto, Reksa Dana, Obligasi / SBN</p>
                        </div>
                    </div>

                    <span class="text-xs font-bold font-mono-num text-cyan-600 dark:text-cyan-400">
                        Total: Rp {{ number_format($metrics['financial_investment'], 0, ',', '.') }}
                    </span>
                </div>

                <div class="space-y-3">
                    @forelse($financialList as $fin)
                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 hover:border-cyan-500/40 transition">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-bold text-slate-900 dark:text-white text-sm">{{ $fin->title }}</h3>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-cyan-100 text-cyan-800 dark:bg-cyan-950/80 dark:text-cyan-300">
                                            {{ $fin->category }}
                                        </span>
                                    </div>
                                    @if($fin->platform)
                                        <p class="text-xs text-slate-500 mt-1">Platform: <strong class="text-slate-700 dark:text-slate-300">{{ $fin->platform }}</strong></p>
                                    @endif
                                    @if($fin->target_objective)
                                        <p class="text-[11px] text-slate-400 mt-1 italic">Strategi: "{{ $fin->target_objective }}"</p>
                                    @endif
                                </div>

                                <div class="text-right shrink-0">
                                    <div class="text-sm font-extrabold font-mono-num text-cyan-600 dark:text-cyan-400">
                                        Rp {{ number_format($fin->amount, 0, ',', '.') }}
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($fin->date)->translatedFormat('d M Y') }}</p>

                                    <div class="mt-2 flex items-center justify-end gap-1.5">
                                        <a href="{{ route('allocations.edit', $fin) }}" class="p-1 text-slate-400 hover:text-indigo-600 transition" title="Edit">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form method="POST" action="{{ route('allocations.destroy', $fin) }}" onsubmit="return confirm('Hapus alokasi ini?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 text-slate-400 hover:text-rose-600 transition" title="Hapus">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-xs text-slate-400">
                            Belum ada alokasi aset finansial pada periode ini.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- COLUMN B: INVESTASI LEHER KE ATAS (SKILL) -->
            <div class="p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2.5">
                        <div class="h-9 w-9 rounded-xl bg-violet-500/10 text-violet-600 dark:text-violet-400 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-900 dark:text-white">2. Investasi Leher ke Atas (Skill)</h2>
                            <p class="text-xs text-slate-500">Kelas, Cybersecurity, Buku, Sertifikasi</p>
                        </div>
                    </div>

                    <span class="text-xs font-bold font-mono-num text-violet-600 dark:text-violet-400">
                        Total: Rp {{ number_format($metrics['skill_investment'], 0, ',', '.') }}
                    </span>
                </div>

                <div class="space-y-3">
                    @forelse($skillList as $sk)
                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 hover:border-violet-500/40 transition">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-bold text-slate-900 dark:text-white text-sm">{{ $sk->title }}</h3>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-violet-100 text-violet-800 dark:bg-violet-950/80 dark:text-violet-300">
                                            {{ $sk->category }}
                                        </span>
                                    </div>
                                    @if($sk->platform)
                                        <p class="text-xs text-slate-500 mt-1">Provider / Platform: <strong class="text-slate-700 dark:text-slate-300">{{ $sk->platform }}</strong></p>
                                    @endif
                                    @if($sk->target_objective)
                                        <p class="text-[11px] text-slate-400 mt-1 italic">Target Capaian: "{{ $sk->target_objective }}"</p>
                                    @endif

                                    <!-- Status Pill -->
                                    <div class="mt-2">
                                        @if($sk->status === 'completed')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                Selesai / Lulus
                                            </span>
                                        @elseif($sk->status === 'in_progress')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-950/80 dark:text-amber-300">
                                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                Sedang Belajar
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                                Direncanakan
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="text-right shrink-0">
                                    <div class="text-sm font-extrabold font-mono-num text-violet-600 dark:text-violet-400">
                                        Rp {{ number_format($sk->amount, 0, ',', '.') }}
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($sk->date)->translatedFormat('d M Y') }}</p>

                                    <div class="mt-2 flex items-center justify-end gap-1.5">
                                        <a href="{{ route('allocations.edit', $sk) }}" class="p-1 text-slate-400 hover:text-indigo-600 transition" title="Edit">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form method="POST" action="{{ route('allocations.destroy', $sk) }}" onsubmit="return confirm('Hapus alokasi ini?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 text-slate-400 hover:text-rose-600 transition" title="Hapus">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-xs text-slate-400">
                            Belum ada alokasi investasi skill pada periode ini.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- MODAL ALOKASI SURPLUS BARU -->
        <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showAddModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     class="fixed inset-0 transition-opacity bg-slate-950/70 backdrop-blur-sm" @click="showAddModal = false"></div>

                <div x-show="showAddModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-800">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-2.5">
                            <div class="h-9 w-9 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-white">Alokasikan Surplus Dana</h3>
                        </div>
                        <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('allocations.store') }}" class="mt-4 space-y-4">
                        @csrf

                        <!-- Toggle Finansial vs Skill -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">Pilihan Jalur Investasi <span class="text-rose-500">*</span></label>
                            <div class="grid grid-cols-2 gap-3 p-1 rounded-2xl bg-slate-100 dark:bg-slate-800">
                                <label class="flex items-center justify-center gap-2 p-2.5 rounded-xl text-xs font-bold cursor-pointer transition"
                                       :class="allocType === 'financial' ? 'bg-white dark:bg-slate-900 text-cyan-600 dark:text-cyan-400 shadow-sm border border-slate-200 dark:border-slate-700' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900'">
                                    <input type="radio" name="type" value="financial" x-model="allocType" class="hidden">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Investasi Finansial</span>
                                </label>

                                <label class="flex items-center justify-center gap-2 p-2.5 rounded-xl text-xs font-bold cursor-pointer transition"
                                       :class="allocType === 'skill' ? 'bg-white dark:bg-slate-900 text-violet-600 dark:text-violet-400 shadow-sm border border-slate-200 dark:border-slate-700' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900'">
                                    <input type="radio" name="type" value="skill" x-model="allocType" class="hidden">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    <span>Leher ke Atas (Skill)</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1" x-text="allocType === 'financial' ? 'Nama Aset / Ticker Saham / Token *' : 'Nama Kelas / Sertifikasi / Buku *'"></label>
                            <input type="text" name="title" required :placeholder="allocType === 'financial' ? 'Contoh: Saham BBCA, Bitcoin DCA, SBN ORI' : 'Contoh: Kelas Cybersecurity Pentest, CompTIA Security+, Buku Clean Architecture'"
                                   class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Kategori Instrumen <span class="text-rose-500">*</span></label>
                                <select name="category" required class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none">
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
                                <input type="text" name="platform" :placeholder="allocType === 'financial' ? 'Contoh: Stockbit, Bibit, Binance' : 'Contoh: HackTheBox, OffSec, Coursera'"
                                       class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Tanggal Alokasi <span class="text-rose-500">*</span></label>
                                <input type="date" name="date" required value="{{ date('Y-m-d') }}"
                                       class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            </div>

                            <template x-if="allocType === 'skill'">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Status Progres Target</label>
                                    <select name="status" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                        <option value="planned">Direncanakan</option>
                                        <option value="in_progress">Sedang Belajar</option>
                                        <option value="completed">Selesai / Lulus</option>
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
                                <input type="number" step="0.01" min="1" name="amount" required x-model.number="inputAmount" placeholder="0"
                                       class="w-full pl-10 pr-3.5 py-2.5 text-sm font-bold font-mono-num rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            </div>

                            <!-- Real-time surplus check warning -->
                            <div x-show="inputAmount > remainingSurplus && remainingSurplus > 0" class="mt-2 p-2.5 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-600 dark:text-amber-400 text-[11px] flex items-center gap-2">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                <span>Nominal melebihi sisa surplus periode aktif (<span class="font-bold" x-text="window.formatIDR(remainingSurplus)"></span>). Alokasi tetap dapat disimpan sebagai proyeksi.</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Target / Keterangan Objektif</label>
                            <textarea name="target_objective" rows="2" placeholder="Contoh: Target lulus sertifikasi Q4 2026, atau Dividen compounding jangka 5 tahun..."
                                      class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none"></textarea>
                        </div>

                        <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100 dark:border-slate-800">
                            <button type="button" @click="showAddModal = false" class="px-4 py-2.5 text-xs font-semibold rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2.5 text-xs font-bold rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white transition shadow-sm">
                                Simpan Alokasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
