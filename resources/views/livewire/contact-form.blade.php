<div class="rounded-2xl border border-line bg-panel/80 p-6 backdrop-blur sm:p-9">
    @if ($sent)
        <div class="flex flex-col items-start gap-4 py-10" role="status">
            <span class="grid size-12 place-items-center rounded-full bg-leaf/15 text-leaf"><x-heroicon-o-check class="size-6" /></span>
            <p class="font-mono text-sm text-malbec">$ mensaje --enviar ✓</p>
            <p class="text-lg">{{ __('site.contact.sent') }}</p>
        </div>
    @else
        <form wire:submit="send" class="flex flex-col gap-5" novalidate>
            <div class="grid gap-5 sm:grid-cols-2">
                <x-home.field id="contact-name" :label="__('site.contact.name')" model="name" autocomplete="name" />
                <x-home.field id="contact-email" :label="__('site.contact.email')" model="email" type="email" autocomplete="email" />
                <x-home.field id="contact-phone" :label="__('site.contact.phone')" model="phone" type="tel" autocomplete="tel" />

                <div class="flex flex-col gap-2">
                    <label for="contact-budget" class="font-mono text-[11px] tracking-[0.18em] text-muted uppercase">{{ __('site.contact.budget') }}</label>
                    <select id="contact-budget" wire:model="budget" class="h-12 rounded-lg border border-line bg-night px-4 text-fg transition outline-none focus:border-malbec">
                        <option value="">—</option>
                        @foreach (__('site.contact.budgets') as $option)
                            <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex flex-col gap-2">
                <label for="contact-message" class="font-mono text-[11px] tracking-[0.18em] text-muted uppercase">{{ __('site.contact.message') }}</label>
                <textarea id="contact-message" wire:model="message" rows="6"
                          class="rounded-lg border border-line bg-night px-4 py-3 text-fg transition outline-none focus:border-malbec"></textarea>
                @error('message') <p class="font-mono text-xs text-malbec-soft">{{ $message }}</p> @enderror
            </div>

            <div class="absolute -left-[9999px]" aria-hidden="true">
                <label for="contact-website">Website</label>
                <input id="contact-website" type="text" wire:model="website" tabindex="-1" autocomplete="off">
            </div>

            @if ($throttled)
                <p class="rounded-lg border border-sun/40 bg-sun/10 p-3 text-sm text-sun" role="alert">{{ __('site.contact.throttled') }}</p>
            @endif

            <button type="submit" class="btn btn-primary w-full sm:w-auto sm:self-start" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="send">{{ __('site.contact.send') }}</span>
                <span wire:loading wire:target="send">{{ __('site.contact.sending') }}</span>
                <x-heroicon-m-paper-airplane class="size-4" wire:loading.remove wire:target="send" />
            </button>
        </form>
    @endif
</div>
