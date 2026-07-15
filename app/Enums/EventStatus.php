<?php

namespace App\Enums;

enum EventStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Active => 'Active',
            self::Archived => 'Archived',
        };
    }

    public function tone(): string
    {
        return match ($this) {
            self::Draft => 'amber',
            self::Active => 'emerald',
            self::Archived => 'slate',
        };
    }
}
