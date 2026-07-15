<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'checked_in_by',
        'checked_in_at',
        'checkin_method',
        'ip_address',
        'browser',
        'device',
    ];

    protected function casts(): array
    {
        return [
            'checked_in_at' => 'datetime',
            'checkin_method' => 'string',
        ];
    }

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function checkedInBy()
    {
        return $this->belongsTo(User::class, 'checked_in_by');
    }

    public function isQrScan(): bool
    {
        return $this->checkin_method === 'qr';
    }

    public function isManual(): bool
    {
        return $this->checkin_method === 'manual';
    }
}
