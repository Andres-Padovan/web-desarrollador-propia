<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedFields;
use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable(['slug', 'title', 'excerpt', 'body', 'category', 'image', 'is_published', 'published_at'])]
class Post extends Model
{
    /** @use HasFactory<PostFactory> */
    use HasFactory, HasLocalizedFields;

    /** Topics shown as a label on each article. Labels live in lang/{es,en}/site.php under blog.categories. */
    public const CATEGORIES = ['ai' => 'Inteligencia artificial', 'web' => 'Sitios web', 'business' => 'Negocio'];

    protected function casts(): array
    {
        return [
            'title' => 'array',
            'excerpt' => 'array',
            'body' => 'array',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    /** @param  Builder<Post>  $query */
    public function scopePublished(Builder $query): void
    {
        $query->where('is_published', true)->where('published_at', '<=', now())->orderByDesc('published_at');
    }

    public function isPublished(): bool
    {
        return $this->is_published && $this->published_at?->isPast();
    }

    /** The article body, written in Markdown, rendered as HTML for the active locale. */
    public function bodyHtml(): string
    {
        return Str::markdown((string) $this->localized('body'), ['html_input' => 'strip', 'allow_unsafe_links' => false]);
    }

    public function readingMinutes(): int
    {
        return max(1, (int) ceil(str_word_count(strip_tags((string) $this->localized('body'))) / 200));
    }
}
