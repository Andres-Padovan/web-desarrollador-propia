<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedFields;
use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'slug', 'title', 'summary', 'category', 'stack', 'status', 'repo_url', 'live_url', 'image', 'accent',
    'source', 'github_id', 'language', 'stars', 'is_private', 'is_featured', 'is_visible', 'sort', 'pushed_at',
])]
class Project extends Model
{
    /** @use HasFactory<ProjectFactory> */
    use HasFactory, HasLocalizedFields;

    public const STATUSES = ['live' => 'Live', 'beta' => 'Beta', 'wip' => 'En desarrollo', 'archived' => 'Archivado'];

    protected function casts(): array
    {
        return [
            'title' => 'array',
            'summary' => 'array',
            'category' => 'array',
            'stack' => 'array',
            'is_private' => 'boolean',
            'is_featured' => 'boolean',
            'is_visible' => 'boolean',
            'pushed_at' => 'datetime',
        ];
    }

    /** @param  Builder<Project>  $query */
    public function scopePublished(Builder $query): void
    {
        $query->where('is_visible', true)->orderByDesc('is_featured')->orderBy('sort')->orderByDesc('pushed_at');
    }

    public function imageUrl(): ?string
    {
        return $this->image ? Storage::disk('public')->url($this->image) : null;
    }

    /** The first public link worth sending a visitor to. */
    public function primaryUrl(): ?string
    {
        return $this->live_url ?: ($this->is_private ? null : $this->repo_url);
    }
}
