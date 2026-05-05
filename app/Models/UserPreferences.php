<?php

namespace App\Models;

use App\Casts\PgArray;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPreferences extends Model
{
    protected $table   = 'user_preferences';
    const CREATED_AT   = null;              // Tidak ada created_at
    const UPDATED_AT   = 'updated_at';

    protected $fillable = [
        'user_id',
        'kategori_favorit',  // TEXT[]
        'wilayah_favorit',   // TEXT[]
        'budget_min',
        'budget_max',
        'tipe_wisata',       // TEXT[]
    ];

    protected function casts(): array
    {
        return [
            'kategori_favorit' => PgArray::class,
            'wilayah_favorit'  => PgArray::class,
            'tipe_wisata'      => PgArray::class,
            'budget_min'       => 'integer',
            'budget_max'       => 'integer',
            'updated_at'       => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
