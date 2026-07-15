<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-full bg-rose-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-rose-600/20 transition hover:bg-rose-500 focus:outline-none focus:ring-4 focus:ring-rose-500/20 active:bg-rose-700']) }}>
    {{ $slot }}
</button>
