<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    use HasFactory;

    protected $table = 'questionnaires';

    protected $fillable = [
        'email',
        'nama',
        'jenis_kelamin',
        'usia',
        'pekerjaan',
        'institusi',
        'domisili',
        'no_telepon',
        'tujuan_kunjungan',
        'frekuensi_kunjungan',
        'fitur_favorit',
        'kemudahan_navigasi',
        'kecepatan_akses',
        'tampilan_desain',
        'kelengkapan_konten',
        'rating',
        'kesan',
        'pesan',
        'saran',
    ];
}
