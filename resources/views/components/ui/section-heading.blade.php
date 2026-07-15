@props([
    'eyebrow' => null,
    'title',
    'description' => null,
    'align' => 'left',
])

@php
    $alignment = $align === 'center' ? 'text-center mx-auto' : 'text-left';
@endphp

<div {{ $attributes->merge(['class' => 'max-w-3xl '.$alignment]) }}>
    @if ($eyebrow)
        <p class="mb-3 text-xs font-semibold uppercase tracking-[0.3em] text-brand-300">
            {{ $eyebrow }}
        </p>
    @endif

    <h2 class="font-display text-3xl font-semibold tracking-tight text-slate-50 sm:text-4xl">
        {{ $title }}
    </h2>

    @if ($description)
        <p class="mt-4 text-base leading-7 text-slate-300 sm:text-lg">
            {{ $description }}
        </p>
    @endif
</div>
