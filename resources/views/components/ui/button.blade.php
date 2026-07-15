@props([
    'href' => null,
    'variant' => 'primary',
    'type' => 'button',
])

@php
    $base = 'inline-flex items-center justify-center gap-2 rounded-full px-5 py-3 text-sm font-semibold transition duration-200 focus:outline-none focus:ring-4 focus:ring-brand-500/20 disabled:pointer-events-none disabled:opacity-60';

    $variants = [
        'primary' => 'bg-brand-500 text-white shadow-lg shadow-brand-500/25 hover:bg-brand-400 hover:shadow-xl hover:shadow-brand-500/30',
        'secondary' => 'border border-slate-200/80 bg-white/80 text-slate-900 shadow-sm hover:border-brand-200 hover:bg-white',
        'ghost' => 'bg-transparent text-slate-100 hover:bg-white/10',
        'danger' => 'bg-rose-600 text-white shadow-lg shadow-rose-600/20 hover:bg-rose-500',
    ];

    $classes = $base.' '.($variants[$variant] ?? $variants['primary']);
@endphp

@if ($href)
    <a {{ $attributes->merge(['href' => $href, 'class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => $type, 'class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
