<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedFields;
use Database\Factories\ServiceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['group', 'title', 'description', 'details', 'includes', 'delivery', 'stack', 'icon', 'is_highlighted', 'is_visible', 'sort'])]
class Service extends Model
{
    /** @use HasFactory<ServiceFactory> */
    use HasFactory, HasLocalizedFields;

    /** Tabs of the services page, in order. Labels live in lang/{es,en}/site.php under services.groups. */
    public const GROUPS = ['sites' => 'Sitios y tiendas', 'systems' => 'Sistemas y apps', 'ai' => 'IA y automatización', 'care' => 'Acompañamiento'];

    protected function casts(): array
    {
        return [
            'title' => 'array',
            'description' => 'array',
            'details' => 'array',
            'includes' => 'array',
            'delivery' => 'array',
            'stack' => 'array',
            'is_highlighted' => 'boolean',
            'is_visible' => 'boolean',
        ];
    }

    /** @param  Builder<Service>  $query */
    public function scopePublished(Builder $query): void
    {
        $query->where('is_visible', true)->orderBy('sort');
    }
}
