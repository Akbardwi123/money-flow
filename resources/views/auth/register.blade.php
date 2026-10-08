<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-900 dark:text-white">Daftar Akun Baru</h2>
        <p class="text-xs text-slate-500 mt-1">Mulai kelola arus kas dan alokasi surplus investasi Anda.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap</label>
            <input id="name" class="w-full px-3 py-2 text-xs input-solid" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Nama Anda" />
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Alamat Email</label>
            <input id="email" class="w-full px-3 py-2 text-xs input-solid" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Kata Sandi</label>
            <input id="password" class="w-full px-3 py-2 text-xs input-solid"
                   type="password"
                   name="password"
                   required autocomplete="new-password"
                   placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Konfirmasi Kata Sandi</label>
            <input id="password_confirmation" class="w-full px-3 py-2 text-xs input-solid"
                   type="password"
                   name="password_confirmation"
                   required autocomplete="new-password"
                   placeholder="Ketik ulang kata sandi" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <button type="submit" class="w-full py-2.5 px-4 rounded-lg bg-slate-900 hover:bg-slate-800 text-white dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100 font-semibold text-xs transition shadow-sm mt-2">
            Buat Akun
        </button>

        <p class="text-center text-xs text-slate-500 pt-3">
            Sudah memiliki akun?
            <a href="{{ route('login') }}" class="font-semibold text-slate-900 dark:text-white hover:underline ml-1">
                Masuk di sini
            </a>
        </p>
    </form>
</x-guest-layout>
