<x-layouts.site :title="__('site.pages.services.title')" :description="__('site.pages.services.description')">
    <div class="pt-16">
        <x-home.services :services="$services" />
        <x-home.stack :profile="config('profile')" />
    </div>
</x-layouts.site>
