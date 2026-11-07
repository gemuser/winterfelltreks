<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Page extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'title',
        'slug',
        'layout_type',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'canonical_url',
        'robots_index',
        'is_published',
        'show_in_navbar',
        'navbar_title',
        'navbar_order',
        'show_in_sidebar',
        'sidebar_title',
        'sidebar_order',
        'show_in_footer',
        'footer_column',
        'footer_order',
    ];

    protected $casts = [
        'robots_index' => 'boolean',
        'is_published' => 'boolean',
        'show_in_navbar' => 'boolean',
        'navbar_order' => 'integer',
        'show_in_sidebar' => 'boolean',
        'sidebar_order' => 'integer',
        'show_in_footer' => 'boolean',
        'footer_column' => 'integer',
        'footer_order' => 'integer',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function sections(): HasMany
    {
        return $this->hasMany(PageSection::class)->orderBy('sort_order');
    }

    public function visibleSections(): HasMany
    {
        return $this->sections()->where('is_visible', true);
    }

    public function navTitle(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->navbar_title ?? $this->title,
        );
    }

    public function sidebarTitleAttr(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->sidebar_title ?? $this->title,
        );
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeInNavbar($query)
    {
        return $query->published()->where('show_in_navbar', true)->orderBy('navbar_order');
    }

    public function scopeInSidebar($query)
    {
        return $query->published()->where('show_in_sidebar', true)->orderBy('sidebar_order');
    }

    public function scopeInFooter($query)
    {
        return $query->published()->where('show_in_footer', true)->orderBy('footer_order');
    }

    public function scopeInFooterColumn($query, int $column)
    {
        return $query->published()->where('show_in_footer', true)->where('footer_column', $column)->orderBy('footer_order');
    }
}