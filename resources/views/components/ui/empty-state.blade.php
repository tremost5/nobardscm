@props([
    'title',
    'description',
    'actionLabel' => null,
    'actionHref' => null,
])

<div {{ $attributes->merge(['class' => 'rounded-[1.75rem] border border-dashed border-slate-300 bg-white/60 p-8 text-center shadow-sm']) }}>
    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-50 text-brand-600 ring-1 ring-inset ring-brand-100">
        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6v12m6-6H6" />
        </svg>
    </div>

    <h3 class="mt-5 text-lg font-semibold text-slate-950">{{ $title }}</h3>
    <p class="mt-2 text-sm leading-6 text-slate-500">{{ $description }}</p>

    @if ($actionLabel && $actionHref)
        <div class="mt-6">
            <x-ui.button :href="$actionHref">{{ $actionLabel }}</x-ui.button>
        </div>
    @endif
</div>
