@props(['id', 'label', 'model', 'type' => 'text', 'autocomplete' => null])

<div class="flex flex-col gap-2">
    <label for="{{ $id }}" class="font-mono text-[11px] tracking-[0.18em] text-muted uppercase">{{ $label }}</label>
    <input id="{{ $id }}" type="{{ $type }}" wire:model="{{ $model }}" @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif
           class="h-12 rounded-lg border border-line bg-night px-4 text-fg transition outline-none focus:border-malbec">
    @error($model) <p class="font-mono text-xs text-malbec-soft">{{ $message }}</p> @enderror
</div>
