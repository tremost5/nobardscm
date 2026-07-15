<?php

namespace App\Models;

use App\Enums\EventStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'location',
        'start_date',
        'end_date',
        'hero_image',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'status' => EventStatus::class,
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', EventStatus::Active->value);
    }

    protected function dateRangeLabel(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                $start = $this->start_date?->format('M j, Y');
                $end = $this->end_date?->format('M j, Y');

                if (! $start) {
                    return 'Date to be announced';
                }

                return $start === $end || ! $end
                    ? $start
                    : $start.' - '.$end;
            },
        );
    }

    protected function durationLabel(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                if (! $this->start_date || ! $this->end_date) {
                    return 'Single day event';
                }

                $days = $this->start_date->diffInDays($this->end_date) + 1;

                return $days === 1 ? 'Single day event' : $days.' days';
            },
        );
    }
}
