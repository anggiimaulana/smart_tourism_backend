<?php

namespace App\Models;

use App\Casts\PgArray;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wisata extends Model
{
    use HasFactory;

    protected $table     = 'wisata';
    protected $keyType   = 'integer';
    public $incrementing = true;

    protected $fillable = [
        'kode',
        'nama',
        'wilayah',
        'kecamatan',
        'alamat_lengkap',
        'latitude',
        'longitude',
        'kategori_utama',
        'sub_kategori',
        'jenis_tempat',
        'deskripsi',
        'harga_tiket_min',
        'harga_tiket_max',
        'gratis',
        'jam_buka',
        'jam_tutup',
        'hari_libur_operasional',
        'estimasi_durasi_jam',
        'fasilitas',            // TEXT[]
        'aksesibilitas',
        'moda_transportasi',
        'rating_google',
        'jumlah_ulasan_google',
        'link_google_maps',
        'link_instagram',
        'link_website',
        'kontak',
        'gambar',               // TEXT[]
        'sumber_data',
        'diinput_oleh',
        'status',
        // Kolom AI — biasanya diisi oleh sistem, bukan user
        'sentimen',
        'skor_sentimen',
        'total_ulasan_scraped',
        'total_positif',
        'total_negatif',
    ];

    protected function casts(): array
    {
        return [
            'latitude'              => 'float',
            'longitude'             => 'float',
            'harga_tiket_min'       => 'integer',
            'harga_tiket_max'       => 'integer',
            'gratis'                => 'boolean',
            'estimasi_durasi_jam'   => 'float',
            'rating_google'         => 'float',
            'jumlah_ulasan_google'  => 'integer',
            'skor_sentimen'         => 'float',
            'total_ulasan_scraped'  => 'integer',
            'total_positif'         => 'integer',
            'total_negatif'         => 'integer',
            'fasilitas'             => PgArray::class,   // TEXT[]
            'gambar'                => PgArray::class,   // TEXT[]
            'created_at'            => 'datetime',
            'updated_at'            => 'datetime',
        ];
    }

    // ── Scopes ────────────────────────────────────────────────
    public function scopeAktif(Builder $q): Builder
    {
        return $q->where('status', 'aktif');
    }

    public function scopeByWilayah(Builder $q, string $wilayah): Builder
    {
        return $q->where('wilayah', $wilayah);
    }

    public function scopeByKategori(Builder $q, string $kategori): Builder
    {
        return $q->where('kategori_utama', $kategori);
    }

    public function scopeBySentimen(Builder $q, string $sentimen): Builder
    {
        return $q->where('sentimen', $sentimen);
    }

    public function scopePopular(Builder $q): Builder
    {
        return $q->orderByDesc('rating_google')->orderByDesc('jumlah_ulasan_google');
    }

    // ── Relations ─────────────────────────────────────────────
    public function sentimentResults(): HasMany
    {
        // Relasi ke sentiment_results via tempat_id (integer)
        return $this->hasMany(SentimentResult::class, 'tempat_id')
            ->where('tipe_tempat', 'wisata');
    }

    public function kuliners(): HasMany
    {
        // Kuliner yang berelasi ke wisata ini via id_wisata_terdekat
        return $this->hasMany(Kuliner::class, 'id_wisata_terdekat', 'kode');
    }
}
