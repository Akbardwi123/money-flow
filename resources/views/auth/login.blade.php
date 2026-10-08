<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-900 dark:text-white">Masuk ke akun</h2>
        <p class="text-xs text-slate-500 mt-1">Masukkan kredensial Anda untuk mengakses catatan keuangan.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Alamat Email</label>
            <input id="email" class="w-full px-3 py-2 text-xs input-solid" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1">
                <label for="password" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">Kata Sandi</label>
                @if (Route::has('password.request'))
                    <a class="text-xs font-medium text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white" href="{{ route('password.request') }}">
                        Lupa sandi?
                    </a>
                @endif
            </div>
            <input id="password" class="w-full px-3 py-2 text-xs input-solid"
                   type="password"
                   name="password"
                   required autocomplete="current-password"
                   placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-slate-900 focus:ring-slate-900 cursor-pointer" name="remember">
                <span class="ms-2 text-xs text-slate-600 dark:text-slate-400">Ingat sesi login saya</span>
            </label>
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full py-2.5 px-4 rounded-lg bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs transition shadow-sm cursor-pointer">
                Masuk
            </button>
        </div>
    </form>

    <div class="mt-6 pt-5 border-t border-slate-100 dark:border-slate-800">
        <form method="POST" action="{{ route('demo.login') }}">
            @csrf
            <button type="submit" class="w-full py-2 px-3 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-semibold text-slate-700 dark:text-slate-300 transition flex items-center justify-center gap-1.5">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Masuk Cepat Demo</span>
            </button>
        </form>

        <p class="text-center text-xs text-slate-500 mt-4">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-semibold text-slate-900 dark:text-white hover:underline ml-1">
                Daftar sekarang
            </a>
        </p>
    </div>
</x-guest-layout>
