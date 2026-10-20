<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedFields;
use Database\Factories\ServiceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'description', 'stack', 'icon', 'span', 'is_visible', 'sort'])]
class Service extends Model
{
    /** @use HasFactory<ServiceFactory> */
    use HasFactory, HasLocalizedFields;

    protected function casts(): array
    {
        return [
            'title' => 'array',
            'description' => 'array',
            'stack' => 'array',
            'is_visible' => 'boolean',
        ];
    }

    /** @param  Builder<Service>  $query */
    public function scopePublished(Builder $query): void
    {
        $query->where('is_visible', true)->orderBy('sort');
    }
}
