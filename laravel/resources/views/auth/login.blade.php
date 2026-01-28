<x-guest-layout>
    <x-auth-session-status :status="session('status')" />

    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Selamat Datang</h2>
        <p class="mt-2 text-gray-600">Masuk untuk mengelola perpustakaan</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
            <input id="email"
                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition"
                   type="email"
                   name="email"
                   value="{{ old('email') }}"
                   required
                   autofocus
                   autocomplete="username"
                   placeholder="nama@email.com">
            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
            <input id="password"
                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition"
                   type="password"
                   name="password"
                   required
                   autocomplete="current-password"
                   placeholder="••••••••">
            @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center cursor-pointer">
                <input type="checkbox"
                       name="remember"
                       class="w-4 h-4 text-blue-700 border-gray-300 rounded focus:ring-blue-700">
                <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm font-medium text-blue-700 hover:text-blue-600">
                    Lupa password?
                </a>
            @endif
        </div>

        <button type="submit"
                class="w-full py-3 px-4 bg-blue-800 hover:bg-blue-900 text-white font-semibold rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-700 transition-all duration-200">
            Masuk
        </button>
    </form>

    <div class="mt-8 pt-6 border-t border-gray-100">
        <p class="text-sm text-gray-600 mb-3">Akun Demo:</p>
        <div class="bg-gray-50 rounded-xl p-4 text-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-gray-600">Email:</span>
                <code class="bg-white px-3 py-1 rounded-lg text-gray-900 font-mono">admin@kampus.ac.id</code>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-600">Password:</span>
                <code class="bg-white px-3 py-1 rounded-lg text-gray-900 font-mono">password</code>
            </div>
        </div>
    </div>
</x-guest-layout>
