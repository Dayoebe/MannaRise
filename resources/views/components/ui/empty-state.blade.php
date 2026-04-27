@props(['title' => 'Nothing here yet', 'message' => 'Content will appear here once it is available.', 'actionLabel' => null, 'actionHref' => null])

<div {{ $attributes->merge(['class' => 'empty-state']) }}>
    <div class="empty-state-icon">✨</div>

    <div>
        <h3 class="text-lg font-black tracking-normal text-slate-950">{{ $title }}</h3>
        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-600">{{ $message }}</p>
    </div>

    @if ($actionLabel && $actionHref)
        <a href="{{ $actionHref }}" class="btn-secondary mt-2">{{ $actionLabel }}</a>
    @endif
</div>
