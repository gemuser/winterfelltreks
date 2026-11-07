<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Destination extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'country',
        'region',
        'short_description',
        'full_description',
        'featured_image',
        'gallery',
        'best_time_to_visit',
        'travel_information',
        'category',
        'is_published',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'gallery' => 'json',
        'is_published' => 'boolean',
    ];

    public function getGalleryAttribute($value): array
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : (json_decode($decoded, true) ?? []);
        }
        return is_array($value) ? $value : [];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeInCountry($query, string $country)
    {
        return $query->published()->where('country', $country);
    }

    public function scopeInCategory($query, string $category)
    {
        return $query->published()->where('category', $category);
    }

    public function galleryImages(): array
    {
        return $this->gallery ?? [];
    }
}
