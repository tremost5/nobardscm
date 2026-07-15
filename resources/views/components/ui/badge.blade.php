@props(['tone' => 'slate'])

@php
    $tones = [
        'slate' => 'bg-slate-100 text-slate-700 ring-slate-200',
        'emerald' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'amber' => 'bg-amber-50 text-amber-700 ring-amber-200',
        'rose' => 'bg-rose-50 text-rose-700 ring-rose-200',
        'brand' => 'bg-brand-50 text-brand-700 ring-brand-200',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset '.($tones[$tone] ?? $tones['slate'])]) }}>
    {{ $slot }}
</span>
