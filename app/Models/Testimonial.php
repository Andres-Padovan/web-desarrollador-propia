<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedFields;
use Database\Factories\TestimonialFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'company', 'project_type', 'quote', 'rating', 'is_visible', 'sort'])]
class Testimonial extends Model
{
    /** @use HasFactory<TestimonialFactory> */
    use HasFactory, HasLocalizedFields;

    protected function casts(): array
    {
        return [
            'project_type' => 'array',
            'quote' => 'array',
            'is_visible' => 'boolean',
        ];
    }

    /** @param  Builder<Testimonial>  $query */
    public function scopePublished(Builder $query): void
    {
        $query->where('is_visible', true)->orderBy('sort');
    }
}
