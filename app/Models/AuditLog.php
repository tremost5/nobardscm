<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'user_id',
        'logged_at',
        'method',
        'event_type',
        'action',
        'description',
        'metadata',
        'ip_address',
        'browser',
        'device',
    ];

    protected function casts(): array
    {
        return [
            'logged_at' => 'datetime',
            'method' => 'string',
            'event_type' => 'string',
            'action' => 'string',
            'description' => 'string',
            'metadata' => 'array',
        ];
    }

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
