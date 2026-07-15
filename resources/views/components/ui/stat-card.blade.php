@props(['label', 'value', 'hint' => null])

<x-ui.card>
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
            <p class="mt-3 text-3xl font-semibold tracking-tight text-slate-950">{{ $value }}</p>
        </div>
        <div class="rounded-2xl bg-brand-50 px-3 py-2 text-brand-700 ring-1 ring-inset ring-brand-100">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 18V6m0 12h16M8 14l3-3 3 2 4-5" />
            </svg>
        </div>
    </div>

    @if ($hint)
        <p class="mt-4 text-sm leading-6 text-slate-500">{{ $hint }}</p>
    @endif
</x-ui.card>
