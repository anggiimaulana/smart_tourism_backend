<?php

namespace App\Http\Requests\Kuliner;

use App\Http\Requests\BaseApiRequest;

class StoreKulinerRequest extends BaseApiRequest
{
    public function rules(): array
    {
        return [
            'kode' => 'nullable|string|max:50',
            'id_wisata_terdekat' => 'nullable|string|max:50',
            'nama' => 'required|string|min:2|max:255',
            'wilayah' => 'required|string|in:Cirebon,Indramayu,Majalengka,Kuningan',
            'kecamatan' => 'nullable|string|max:150',
            'alamat_lengkap' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'jenis_tempat' => 'nullable|string|max:100',
            'kategori_menu_utama' => 'nullable|string|max:100',
            'menu_unggulan' => 'nullable|string|max:255',
            'makanan_khas_daerah' => 'nullable|boolean',
            'nama_makanan_khas' => 'nullable|string|max:255',
            'harga_menu_min' => 'nullable|integer|min:0',
            'harga_menu_max' => 'nullable|integer|min:0',
            'jam_buka' => 'nullable|string|max:50',
            'jam_tutup' => 'nullable|string|max:50',
            'kapasitas_orang' => 'nullable|integer|min:0',
            'fasilitas' => 'nullable|array',
            'sertifikat_halal' => 'nullable|boolean',
            'rating_google' => 'nullable|numeric|between:0,5',
            'jumlah_ulasan_google' => 'nullable|integer|min:0',
            'link_google_maps' => 'nullable|url|max:500',
            'kontak' => 'nullable|string|max:100',
            'gambar' => 'nullable|array',
            'sumber_data' => 'nullable|string|max:100',
            'status' => 'nullable|string|in:draft,aktif,nonaktif',
            'sentimen' => 'nullable|string|in:positif,negatif,netral',
            'skor_sentimen' => 'nullable|numeric|between:-1,1',
        ];
    }
}
