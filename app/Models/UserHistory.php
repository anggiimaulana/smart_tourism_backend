<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserHistory extends Model
{
    protected $table   = 'user_history';    // Nama tabel: user_history (bukan user_histories)
    public $timestamps = false;             // Hanya created_at
    const CREATED_AT   = 'created_at';
    const UPDATED_AT   = null;

    protected $fillable = [
        'user_id',          // UUID
        'tipe_tempat',      // enum
        'tempat_id',        // integer
        'tempat_kode',      // VARCHAR
        'aksi',             // klik | kunjungi | simpan | rating | share
        'nilai_rating',     // 1.0 – 5.0
        'durasi_detik',     // integer (engagement signal)
    ];

    protected function casts(): array
    {
        return [
            'tempat_id'   => 'integer',
            'nilai_rating' => 'float',
            'durasi_detik' => 'integer',
            'created_at'  => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
