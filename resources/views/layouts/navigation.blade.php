<nav x-data="{ open: false }" class="bg-white dark:bg-[#0e1422] border-b border-slate-200 dark:border-slate-800 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-8">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
                        <div class="h-8 w-8 rounded-lg bg-slate-900 dark:bg-white text-white dark:text-slate-900 flex items-center justify-center font-black text-sm shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="font-bold text-base text-slate-900 dark:text-white tracking-tight">MoneyFlow</span>
                    </a>
                </div>

                <!-- Navigation Links (Stripe / Wise tab navigation) -->
                <div class="hidden md:flex md:space-x-1">
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-md transition-colors {{ request()->routeIs('dashboard') ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/50' }}">
                        Dashboard
                    </a>

                    <a href="{{ route('incomes.index') }}"
                       class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-md transition-colors {{ request()->routeIs('incomes.*') ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/50' }}">
                        Pemasukan
                    </a>

                    <a href="{{ route('expenses.index') }}"
                       class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-md transition-colors {{ request()->routeIs('expenses.*') ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/50' }}">
                        Pengeluaran
                    </a>

                    <a href="{{ route('allocations.index') }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-md transition-colors {{ request()->routeIs('allocations.*') ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/50' }}">
                        <span>Alokasi Investasi</span>
                        <span class="inline-block px-1.5 py-0.2 rounded text-[10px] font-bold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800/50">Surplus</span>
                    </a>

                    <a href="{{ route('reports.index') }}"
                       class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-md transition-colors {{ request()->routeIs('reports.*') ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/50' }}">
                        Laporan
                    </a>
                </div>
            </div>

            <!-- Right Profile Action -->
            <div class="hidden md:flex md:items-center md:gap-3">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 px-2.5 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/60 text-xs font-semibold text-slate-800 dark:text-slate-200 transition">
                            <span class="h-6 w-6 rounded-full bg-slate-900 dark:bg-slate-100 text-white dark:text-slate-900 flex items-center justify-center font-bold text-[11px]">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </span>
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2.5 border-b border-slate-100 dark:border-slate-800">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Akun Terdaftar</p>
                            <p class="text-xs font-semibold text-slate-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                            <p class="text-[11px] text-slate-500 truncate">{{ Auth::user()->email }}</p>
                        </div>

                        <div class="py-1">
                            <x-dropdown-link :href="route('profile.edit')" class="text-xs">
                                {{ __('Profil Akun') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="text-rose-600 dark:text-rose-400 text-xs">
                                    {{ __('Keluar (Logout)') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile Hamburger -->
            <div class="-me-2 flex items-center md:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0e1422]">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-xs font-semibold">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('incomes.index')" :active="request()->routeIs('incomes.*')" class="text-xs font-semibold">
                {{ __('Pemasukan') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('expenses.index')" :active="request()->routeIs('expenses.*')" class="text-xs font-semibold">
                {{ __('Pengeluaran') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('allocations.index')" :active="request()->routeIs('allocations.*')" class="text-xs font-semibold">
                {{ __('Alokasi Investasi') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')" class="text-xs font-semibold">
                {{ __('Laporan') }}
            </x-responsive-nav-link>
        </div>

        <div class="pt-3 pb-3 border-t border-slate-200 dark:border-slate-800 px-4">
            <div class="text-xs font-bold text-slate-800 dark:text-slate-200">{{ Auth::user()->name }}</div>
            <div class="text-[11px] text-slate-500">{{ Auth::user()->email }}</div>

            <div class="mt-2 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-xs">
                    {{ __('Profil Akun') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-xs text-rose-600">
                        {{ __('Keluar') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
