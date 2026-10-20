@props(['project'])

@php
    $accent = $project->accent ?: '#ff3d7f';
@endphp

{{-- Portada generada cuando el proyecto no tiene imagen: ventana de editor con el nombre del proyecto --}}
<div class="absolute inset-0 opacity-70" style="background: radial-gradient(circle at 80% 0%, {{ $accent }}44, transparent 55%)"></div>
<div class="absolute inset-x-6 top-6 bottom-0 rounded-t-lg border border-b-0 border-line bg-night/90 p-4 font-mono text-[11px] leading-relaxed transition duration-700 group-hover:translate-y-[-6px]">
    <div class="mb-3 flex gap-1.5"><span class="size-2 rounded-full bg-line"></span><span class="size-2 rounded-full bg-line"></span><span class="size-2 rounded-full bg-line"></span></div>
    <p class="text-dim">// {{ $project->language ?: 'project' }}</p>
    <p><span style="color: {{ $accent }}">const</span> <span class="text-fg">{{ \Illuminate\Support\Str::camel($project->slug) }}</span> = {</p>
    <p class="pl-4 text-muted">status: <span class="text-sun">'{{ $project->status }}'</span>,</p>
    <p class="pl-4 text-muted">stack: [{{ collect($project->stack)->take(3)->map(fn ($tech) => "'".$tech."'")->join(', ') }}],</p>
    <p>}</p>
    <p class="mt-4 font-display text-2xl font-black tracking-tight uppercase" style="color: {{ $accent }}">{{ $project->localized('title') }}</p>
</div>
