<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SentimentResult extends Model
{
    protected $table     = 'sentiment_results';
    public $timestamps   = false;           // Hanya ada created_at, tidak ada updated_at

    // Definisikan created_at manual
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'tipe_tempat',      // wisata | kuliner | nongkrong  (enum)
        'tempat_id',        // integer FK logis
        'tempat_kode',      // VARCHAR(20) — redundant tapi berguna untuk query cepat
        'ulasan_asli',
        'ulasan_bersih',
        'sentimen',         // positif | negatif | netral  (enum)
        'confidence',       // 0.0000 – 1.0000
        'model_used',       // indobert | naive_bayes | svm | decision_tree  (enum)
        'sumber_scraping',  // google_maps | tripadvisor | dll
        'scraped_at',
    ];

    protected function casts(): array
    {
        return [
            'tempat_id'  => 'integer',
            'confidence' => 'float',
            'scraped_at' => 'datetime',
            'created_at' => 'datetime',
        ];
    }
}
