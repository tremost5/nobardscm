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
        'wa_status',
        'wa_sent_at',
        'wa_error',
        'wa_retry_count',
    ];

    protected function casts(): array
    {
        return [
            'bring_snack' => 'boolean',
            'wa_sent_at' => 'datetime',
        ];
    }

    public function getBringSnackTextAttribute(): string
    {
        return $this->bring_snack ? 'Ya' : 'Tidak';
    }

    public function getWaStatusLabelAttribute(): string
    {
        return match ($this->wa_status) {
            'sent' => 'Terkirim',
            'failed' => 'Gagal',
            default => 'Pending',
        };
    }

    public function attendance()
    {
        return $this->hasOne(Attendance::class);
    }
}
