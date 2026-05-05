<?php

namespace App\Models;

use App\Casts\PgArray;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanningWisata extends Model
{
    use HasFactory;

    protected $table = 'planning_wisata';

    protected $fillable = [
        'user_id',
        'judul',
        'wilayah',          // TEXT[]
        'tanggal_mulai',
        'tanggal_selesai',
        'jumlah_orang',
        'budget_total',
        'catatan',
        'items',            // JSONB
        'status',           // draft | finalized | selesai
    ];

    protected function casts(): array
    {
        return [
            'wilayah'        => PgArray::class,
            'items'          => 'array',        // JSONB
            'tanggal_mulai'  => 'date',
            'tanggal_selesai' => 'date',
            'jumlah_orang'   => 'integer',
            'budget_total'   => 'integer',
            'created_at'     => 'datetime',
            'updated_at'     => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
