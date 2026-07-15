@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-2xl border-slate-200 bg-white/90 text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-brand-400 focus:ring-4 focus:ring-brand-500/10']) }}>
