<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center rounded-full border border-slate-200 bg-white/90 px-5 py-3 text-sm font-semibold text-slate-800 shadow-sm transition hover:border-brand-200 hover:text-brand-700 hover:shadow-md focus:outline-none focus:ring-4 focus:ring-brand-500/10 active:bg-white']) }}>
    {{ $slot }}
</button>
