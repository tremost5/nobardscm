<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'phone',
        'password',
        'role',
        'status',
        'force_password_change',
        'password_sent_at',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => 'string',
            'status' => 'string',
            'force_password_change' => 'boolean',
            'password_sent_at' => 'datetime',
            'last_login_at' => 'datetime',
        ];
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'checked_in_by');
    }

    public function isSuperadmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isPanitia(): bool
    {
        return $this->role === 'panitia';
    }

    public function isActive(): bool
    {
        return $this->status === 'aktif';
    }
}
