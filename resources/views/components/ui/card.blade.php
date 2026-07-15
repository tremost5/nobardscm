@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'rounded-[1.75rem] border border-white/10 bg-white/80 p-6 text-slate-900 shadow-[0_24px_80px_rgba(15,23,42,0.10)] backdrop-blur-xl '.$class]) }}>
    {{ $slot }}
</div>
