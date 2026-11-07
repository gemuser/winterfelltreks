<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Expedition extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'title',
        'slug',
        'region',
        'max_elevation',
        'duration_days',
        'technical_grade',
        'route_overview',
        'itinerary',
        'gear_checklist',
        'included_services',
        'excluded_services',
        'featured_image',
        'gallery',
        'is_featured',
        'is_published',
    ];

    protected $casts = [
        'duration_days' => 'integer',
        'itinerary' => 'json',
        'gear_checklist' => 'json',
        'included_services' => 'json',
        'excluded_services' => 'json',
        'gallery' => 'json',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
    ];

    public function getItineraryAttribute($value): array
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : (json_decode($decoded, true) ?? []);
        }
        return is_array($value) ? $value : [];
    }

    public function getGearChecklistAttribute($value): array
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : (json_decode($decoded, true) ?? []);
        }
        return is_array($value) ? $value : [];
    }

    public function getIncludedServicesAttribute($value): array
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : (json_decode($decoded, true) ?? []);
        }
        return is_array($value) ? $value : [];
    }

    public function getExcludedServicesAttribute($value): array
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : (json_decode($decoded, true) ?? []);
        }
        return is_array($value) ? $value : [];
    }

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
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured($query)
    {
        return $query->published()->where('is_featured', true);
    }

    public function scopeInRegion($query, string $region)
    {
        return $query->published()->where('region', $region);
    }

    public function itineraryItems(): array
    {
        return $this->itinerary ?? [];
    }
}
