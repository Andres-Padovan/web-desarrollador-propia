@props(['status'])

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 rounded-sm border border-line px-2 py-0.5 font-mono text-[10px] tracking-widest uppercase '.($status === 'live' ? 'text-leaf' : 'text-sun')]) }}>
    <span class="size-1.5 rounded-full bg-current"></span>{{ __('site.projects.status.'.$status) }}
</span>
