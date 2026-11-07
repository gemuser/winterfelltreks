<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Experience extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'short_description',
        'overview',
        'detailed_description',
        'available_locations',
        'activities',
        'recommended_duration',
        'important_information',
        'hero_image',
        'gallery',
        'related_package_ids',
        'is_published',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'available_locations' => 'json',
        'activities' => 'json',
        'gallery' => 'json',
        'related_package_ids' => 'json',
        'is_published' => 'boolean',
    ];

    public function getAvailableLocationsAttribute($value): array
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : (json_decode($decoded, true) ?? []);
        }
        return is_array($value) ? $value : [];
    }

    public function getActivitiesAttribute($value): array
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

    public function getRelatedPackageIdsAttribute($value): array
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

    public function scopeInCategory($query, string $category)
    {
        return $query->published()->where('category', $category);
    }

    public function relatedExpeditions()
    {
        $ids = $this->related_package_ids;
        if (empty($ids)) {
            return collect();
        }
        return Expedition::whereIn('id', $ids)->published()->get();
    }

    public function galleryImages(): array
    {
        return $this->gallery ?? [];
    }
}
