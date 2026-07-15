@props(['label' => null, 'hint' => null, 'error' => null])

<div>
    @if ($label)
        <label class="mb-2 block text-sm font-medium text-slate-700">{{ $label }}</label>
    @endif

    <textarea {{ $attributes->merge(['class' => 'block w-full rounded-2xl border-slate-200 bg-white/90 px-4 py-3 text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-brand-400 focus:ring-4 focus:ring-brand-500/10']) }}></textarea>

    @if ($hint)
        <p class="mt-2 text-xs text-slate-500">{{ $hint }}</p>
    @endif

    @if ($error)
        <p class="mt-2 text-sm text-rose-600">{{ $error }}</p>
    @endif
</div>
