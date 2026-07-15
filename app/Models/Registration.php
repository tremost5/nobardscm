<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'church_name',
        'whatsapp_number',
        'bring_snack',
        'registration_number',
        'ticket_token',
    ];

    protected function casts(): array
    {
        return [
            'bring_snack' => 'boolean',
        ];
    }

    public function getBringSnackTextAttribute(): string
    {
        return $this->bring_snack ? 'Ya' : 'Tidak';
    }

    public function attendance()
    {
        return $this->hasOne(Attendance::class);
    }
}
