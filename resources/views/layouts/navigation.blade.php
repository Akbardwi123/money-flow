<nav x-data="{ open: false }" class="bg-[#070a13]/85 backdrop-blur-2xl border-b border-white/10 sticky top-0 z-50 transition-colors">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-18 py-2">
            <div class="flex items-center gap-8">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="focus:outline-none transition-transform hover:scale-105 duration-200">
                        <x-application-logo />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex md:items-center md:space-x-1.5 lg:space-x-2">
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-emerald-500/20 to-teal-500/20 text-emerald-400 border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.2)]' : 'text-slate-400 hover:text-white hover:bg-white/5 border border-transparent' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('dashboard') ? 'text-emerald-400' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('incomes.index') }}"
                       class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('incomes.*') ? 'bg-gradient-to-r from-emerald-500/20 to-teal-500/20 text-emerald-400 border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.2)]' : 'text-slate-400 hover:text-white hover:bg-white/5 border border-transparent' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('incomes.*') ? 'text-emerald-400' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                        <span>Pemasukan</span>
                    </a>

                    <a href="{{ route('expenses.index') }}"
                       class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('expenses.*') ? 'bg-gradient-to-r from-emerald-500/20 to-teal-500/20 text-emerald-400 border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.2)]' : 'text-slate-400 hover:text-white hover:bg-white/5 border border-transparent' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('expenses.*') ? 'text-emerald-400' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
                        <span>Pengeluaran</span>
                    </a>

                    <a href="{{ route('allocations.index') }}"
                       class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('allocations.*') ? 'bg-gradient-to-r from-indigo-500/20 to-cyan-500/20 text-cyan-300 border border-cyan-500/30 shadow-[0_0_15px_rgba(6,182,212,0.2)]' : 'text-slate-400 hover:text-white hover:bg-white/5 border border-transparent' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('allocations.*') ? 'text-cyan-400' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <span class="flex items-center gap-1.5">
                            Hub Alokasi
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-extrabold uppercase tracking-wider bg-cyan-500/20 text-cyan-300 border border-cyan-500/30">Surplus</span>
                        </span>
                    </a>

                    <a href="{{ route('reports.index') }}"
                       class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('reports.*') ? 'bg-gradient-to-r from-emerald-500/20 to-teal-500/20 text-emerald-400 border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.2)]' : 'text-slate-400 hover:text-white hover:bg-white/5 border border-transparent' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('reports.*') ? 'text-emerald-400' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        <span>Laporan & Portofolio</span>
                    </a>
                </div>
            </div>

            <!-- Right Profile & Theme Action -->
            <div class="hidden md:flex md:items-center md:gap-3">
                <div class="text-right hidden lg:block">
                    <p class="text-xs font-bold text-slate-200 leading-tight">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-emerald-400 font-mono-num font-medium">Akun Terverifikasi</p>
                </div>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center justify-center h-10 w-10 rounded-xl bg-gradient-to-tr from-emerald-500/20 to-teal-500/20 border border-emerald-500/30 text-emerald-400 font-bold text-sm hover:border-emerald-400/60 transition shadow-[0_0_12px_rgba(16,185,129,0.2)]">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-white/10 bg-[#0c1222]">
                            <p class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Masuk Sebagai</p>
                            <p class="text-xs font-bold text-white truncate mt-0.5">{{ Auth::user()->name }}</p>
                            <p class="text-[11px] text-slate-400 truncate">{{ Auth::user()->email }}</p>
                        </div>

                        <div class="bg-[#0e1526] py-1">
                            <x-dropdown-link :href="route('profile.edit')" class="text-slate-300 hover:text-white hover:bg-white/5 text-xs font-medium">
                                {{ __('Profil Akun') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="text-rose-400 hover:text-rose-300 hover:bg-rose-500/10 text-xs font-medium">
                                    {{ __('Keluar (Logout)') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile Hamburger -->
            <div class="-me-2 flex items-center md:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 border border-white/5 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden border-t border-white/10 bg-[#090e1a]/95 backdrop-blur-2xl">
        <div class="pt-3 pb-3 space-y-1.5 px-4">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="rounded-xl text-xs font-semibold">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('incomes.index')" :active="request()->routeIs('incomes.*')" class="rounded-xl text-xs font-semibold">
                {{ __('Pemasukan') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('expenses.index')" :active="request()->routeIs('expenses.*')" class="rounded-xl text-xs font-semibold">
                {{ __('Pengeluaran') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('allocations.index')" :active="request()->routeIs('allocations.*')" class="rounded-xl text-xs font-semibold">
                {{ __('Hub Alokasi Investasi') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')" class="rounded-xl text-xs font-semibold">
                {{ __('Laporan & Portofolio') }}
            </x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-3 border-t border-white/10 px-4 bg-[#0c1220]">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 font-bold flex items-center justify-center">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <div class="font-bold text-sm text-white">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-slate-400">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="rounded-xl text-xs">
                    {{ __('Profil Akun') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="rounded-xl text-xs text-rose-400">
                        {{ __('Keluar') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
