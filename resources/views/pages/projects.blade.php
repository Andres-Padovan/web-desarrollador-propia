<x-layouts.site :title="__('site.pages.projects.title')" :description="__('site.pages.projects.description')">
    <div class="pt-16">
        <x-home.projects :projects="$projects" :categories="$categories" :profile="config('profile')" />
    </div>
</x-layouts.site>
