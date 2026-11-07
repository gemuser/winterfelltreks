<?php

namespace App\Models;

use App\Collections\ElementCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SectionElement extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'element_key',
        'element_type',
        'content',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'content' => 'json',
    ];

    public function newCollection(array $models = []): ElementCollection
    {
        return new ElementCollection($models);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(PageSection::class, 'section_id');
    }

    public function getContent(): mixed
    {
        $content = $this->content;
        
        // If content is a JSON-encoded string, decode it
        if (is_string($content)) {
            $decoded = json_decode($content, true);
            if (json_last_error() === JSON_ERROR_NONE && (is_array($decoded) || is_object($decoded))) {
                return $decoded;
            }
        }
        
        return $content;
    }

    public function getImageUrl(): ?string
    {
        if ($this->element_type !== 'image') {
            return null;
        }

        $content = $this->getContent();

        if (is_array($content)) {
            return $content['url'] ?? null;
        }

        if (is_string($content)) {
            // Check if it's JSON formatted
            $decoded = json_decode($content, true);
            if (is_array($decoded) && isset($decoded['url'])) {
                return $decoded['url'];
            }
            return $content;
        }

        return null;
    }

    public function getImageAlt(): ?string
    {
        if ($this->element_type !== 'image') {
            return null;
        }

        $content = $this->getContent();

        if (is_array($content)) {
            return $content['alt'] ?? null;
        }

        if (is_string($content)) {
            $decoded = json_decode($content, true);
            if (is_array($decoded) && isset($decoded['alt'])) {
                return $decoded['alt'];
            }
        }

        return null;
    }

    public function getLinkUrl(): ?string
    {
        if ($this->element_type !== 'link') {
            return null;
        }

        $content = $this->getContent();

        if (is_array($content)) {
            return $content['url'] ?? null;
        }

        if (is_string($content)) {
            $decoded = json_decode($content, true);
            if (is_array($decoded) && isset($decoded['url'])) {
                return $decoded['url'];
            }
            return $content;
        }

        return null;
    }

    public function getLinkText(): ?string
    {
        if ($this->element_type !== 'link') {
            return null;
        }

        $content = $this->getContent();

        if (is_array($content)) {
            return $content['text'] ?? null;
        }

        if (is_string($content)) {
            $decoded = json_decode($content, true);
            if (is_array($decoded) && isset($decoded['text'])) {
                return $decoded['text'];
            }
        }

        return null;
    }

    public function getRepeaterItems(): array
    {
        if ($this->element_type !== 'repeater') {
            return [];
        }

        $content = $this->getContent();
        return is_array($content) ? $content : [];
    }

    /**
     * Returns repeater data as a JSON string (used in edit form textarea).
     */
    public function getRepeaterData(): string
    {
        $items = $this->getRepeaterItems();
        return json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?: '[]';
    }
}
