<?php

namespace App\Models;

use App\Casts\PgArray;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Nongkrong extends Model
{
    use HasFactory;

    protected $table     = 'nongkrong';
    protected $keyType   = 'integer';
    public $incrementing = true;

    protected $fillable = [
        'kode',
        'id_wisata_ref',
        'nama',
        'wilayah',
        'kecamatan',
        'alamat_lengkap',
        'latitude',
        'longitude',
        'konsep_suasana',
        'target_pengunjung',
        'cocok_untuk',
        'menu_best_seller',
        'harga_menu_min',
        'harga_menu_max',
        'jam_buka',
        'jam_tutup',
        'kapasitas_orang',
        'fasilitas',            // TEXT[]
        'batas_waktu_duduk',
        'rating_google',
        'minimal_order',
        'link_google_maps',
        'kontak',
        'gambar',               // TEXT[]
        'sumber_data',
        'catatan',
        'status',
        'sentimen',
        'skor_sentimen',
        'total_ulasan_scraped',
        'total_positif',
        'total_negatif',
    ];

    protected function casts(): array
    {
        return [
            'latitude'             => 'float',
            'longitude'            => 'float',
            'harga_menu_min'       => 'integer',
            'harga_menu_max'       => 'integer',
            'kapasitas_orang'      => 'integer',
            'minimal_order'        => 'integer',
            'rating_google'        => 'float',
            'jumlah_ulasan_google' => 'integer',
            'skor_sentimen'        => 'float',
            'total_ulasan_scraped' => 'integer',
            'total_positif'        => 'integer',
            'total_negatif'        => 'integer',
            'fasilitas'            => PgArray::class,
            'gambar'               => PgArray::class,
            'created_at'           => 'datetime',
            'updated_at'           => 'datetime',
        ];
    }

    public function scopeAktif(Builder $q): Builder
    {
        return $q->where('status', 'aktif');
    }

    public function scopeByWilayah(Builder $q, string $wilayah): Builder
    {
        return $q->where('wilayah', $wilayah);
    }

    public function scopeBySentimen(Builder $q, string $sentimen): Builder
    {
        return $q->where('sentimen', $sentimen);
    }

    public function scopePopular(Builder $q): Builder
    {
        return $q->orderByDesc('rating_google');
    }

    public function wisataRef(): BelongsTo
    {
        return $this->belongsTo(Wisata::class, 'id_wisata_ref', 'kode');
    }

    public function sentimentResults(): HasMany
    {
        return $this->hasMany(SentimentResult::class, 'tempat_id')
            ->where('tipe_tempat', 'nongkrong');
    }
}
