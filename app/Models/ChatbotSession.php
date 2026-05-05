<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatbotSession extends Model
{
    use HasUuids;

    protected $table     = 'chatbot_sessions';
    protected $keyType   = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'session_token',
        'messages',             // JSONB
        'latitude',
        'longitude',
        'wilayah_terdeteksi',   // wilayah_enum
    ];

    protected function casts(): array
    {
        return [
            'messages'   => 'array',    // JSONB → PHP array
            'latitude'   => 'float',
            'longitude'  => 'float',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
