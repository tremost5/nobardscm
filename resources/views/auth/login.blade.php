<x-guest-layout>
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div>
            <x-input-label for="username" :value="__('Username')" class="text-white" />
            <x-text-input id="username" class="mt-2 block w-full border-white/10 bg-white text-[#000000] placeholder-[#666666] focus:border-amber-300/40 focus:ring-amber-300/10" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" placeholder="brown" />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" class="text-white" />
            <x-text-input id="password" class="mt-2 block w-full border-white/10 bg-white text-[#000000] placeholder-[#666666] focus:border-amber-300/40 focus:ring-amber-300/10" type="password" name="password" required autocomplete="current-password" placeholder="password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <label for="remember_me" class="flex items-center gap-3 text-sm text-slate-300">
            <input id="remember_me" type="checkbox" class="rounded border-white/20 bg-white/5 text-amber-400 shadow-sm focus:ring-amber-300/20" name="remember">
            <span>{{ __('Remember me') }}</span>
        </label>

        <x-primary-button class="w-full justify-center py-3">
            {{ __('Log in') }}
        </x-primary-button>
    </form>
</x-guest-layout>
