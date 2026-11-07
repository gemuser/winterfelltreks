<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PageSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'section_type',
        'custom_anchor_id',
        'sort_order',
        'is_visible',
        'settings',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_visible' => 'boolean',
        'settings' => 'json',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function elements(): HasMany
    {
        return $this->hasMany(SectionElement::class, 'section_id')->orderBy('sort_order');
    }

    public function getElement(string $key): ?SectionElement
    {
        return $this->elements()->where('element_key', $key)->first();
    }

    public function getElementContent(string $key, mixed $default = null): mixed
    {
        return $this->getElement($key)?->content ?? $default;
    }

    public function hasElement(string $key): bool
    {
        return $this->elements()->where('element_key', $key)->exists();
    }
}
