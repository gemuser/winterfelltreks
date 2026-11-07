<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Enquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number',
        'enquiry_type',
        'destination_id',
        'package_id',
        'experience_id',
        'full_name',
        'email',
        'phone',
        'country',
        'travel_type',
        'expected_departure_date',
        'expected_return_date',
        'number_of_travellers',
        'number_of_adults',
        'number_of_children',
        'accommodation_preference',
        'budget_range',
        'preferred_activities',
        'travel_flexibility',
        'additional_message',
        'special_requirements',
        'dietary_requirements',
        'accessibility_requirements',
        'consent',
        'status',
        'assigned_staff_id',
        'internal_notes',
        'submitted_at',
    ];

    protected $casts = [
        'expected_departure_date' => 'date',
        'expected_return_date' => 'date',
        'number_of_travellers' => 'integer',
        'number_of_adults' => 'integer',
        'number_of_children' => 'integer',
        'preferred_activities' => 'json',
        'consent' => 'boolean',
        'submitted_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($enquiry) {
            if (empty($enquiry->reference_number)) {
                $enquiry->reference_number = 'WTE-' . strtoupper(uniqid());
            }
            if (empty($enquiry->submitted_at)) {
                $enquiry->submitted_at = now();
            }
        });
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    public function expedition(): BelongsTo
    {
        return $this->belongsTo(Expedition::class, 'package_id');
    }

    public function experience(): BelongsTo
    {
        return $this->belongsTo(Experience::class, 'experience_id');
    }

    public function assignedStaff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_staff_id');
    }

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function getContextName(): string
    {
        return match ($this->enquiry_type) {
            'destination' => $this->destination?->name ?? 'Destination',
            'package' => $this->expedition?->title ?? 'Package',
            'experience' => $this->experience?->name ?? 'Experience',
            default => 'General Enquiry',
        };
    }
}