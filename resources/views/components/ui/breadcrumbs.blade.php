@props(['items' => []])

<nav aria-label="Breadcrumb" {{ $attributes->merge(['class' => 'flex flex-wrap items-center gap-2 text-sm text-slate-500']) }}>
    @foreach ($items as $index => $item)
        @if ($index > 0)
            <svg class="h-4 w-4 text-slate-300" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 0 1 0-1.414L10.586 10 7.293 6.707a1 1 0 1 1 1.414-1.414l4 4a1 1 0 0 1 0 1.414l-4 4a1 1 0 0 1-1.414 0Z" clip-rule="evenodd" />
            </svg>
        @endif

        @if (! empty($item['url']) && $index !== count($items) - 1)
            <a href="{{ $item['url'] }}" class="font-medium text-slate-600 hover:text-brand-600">
                {{ $item['label'] }}
            </a>
        @else
            <span class="font-medium text-slate-900">{{ $item['label'] }}</span>
        @endif
    @endforeach
</nav>
