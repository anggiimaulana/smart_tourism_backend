<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser, HasName, HasAvatar
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin() && $this->is_active;
    }

    public function getFilamentName(): string
    {
        return $this->nama;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        if (! $this->avatar_url) {
            return null;
        }
        if (str_starts_with($this->avatar_url, 'http://') || str_starts_with($this->avatar_url, 'https://')) {
            return $this->avatar_url;
        }
        if (str_starts_with($this->avatar_url, 'storage/')) {
            return url($this->avatar_url);
        }
        return Storage::url($this->avatar_url);
    }

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

    // ── Auth: Tell Laravel the password column name ──────────
    public function getAuthPasswordName(): string
    {
        return 'password_hash';
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
