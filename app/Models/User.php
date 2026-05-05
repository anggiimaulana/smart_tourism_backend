<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    protected $table    = 'users';
    protected $keyType  = 'string';     // UUID
    public $incrementing = false;        // UUID tidak auto-increment

    protected $fillable = [
        'nama',
        'email',
        'password_hash',    // Kolom di DB adalah password_hash
        'role',
        'avatar_url',
        'is_active',
    ];

    protected $hidden = ['password_hash'];

    protected function casts(): array
    {
        return [
            'password_hash' => 'hashed',
            'is_active'     => 'boolean',
            'created_at'    => 'datetime',
            'updated_at'    => 'datetime',
        ];
    }

    // ── Auth: Laravel expects getAuthPassword() ──────────────
    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }

    // ── Helpers ───────────────────────────────────────────────
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // ── Relations ─────────────────────────────────────────────
    public function histories(): HasMany
    {
        return $this->hasMany(UserHistory::class, 'user_id');
    }

    public function preferences(): HasOne
    {
        return $this->hasOne(UserPreferences::class, 'user_id');
    }

    public function chatbotSessions(): HasMany
    {
        return $this->hasMany(ChatbotSession::class, 'user_id');
    }

    public function plannings(): HasMany
    {
        return $this->hasMany(PlanningWisata::class, 'user_id');
    }
}
