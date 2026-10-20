<x-layouts.site :title="__('site.pages.about.title')" :description="__('site.pages.about.description')">
    <div class="pt-16">
        <x-home.about :profile="config('profile')" />
        <x-home.stack :profile="config('profile')" />
        @if ($testimonials->isNotEmpty())
            <x-home.testimonials :testimonials="$testimonials" />
        @endif
    </div>
</x-layouts.site>
