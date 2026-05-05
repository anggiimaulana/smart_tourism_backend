<?php

namespace App\Http\Requests\Nongkrong;

use App\Http\Requests\BaseApiRequest;

class StoreNongkrongRequest extends BaseApiRequest
{
    public function rules(): array
    {
        return [
            'kode' => 'nullable|string|max:50',
            'id_wisata_ref' => 'nullable|string|max:50',
            'nama' => 'required|string|min:2|max:255',
            'wilayah' => 'required|string|in:Cirebon,Indramayu,Majalengka,Kuningan',
            'kecamatan' => 'nullable|string|max:150',
            'alamat_lengkap' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'konsep_suasana' => 'nullable|string|max:150',
            'target_pengunjung' => 'nullable|string|max:150',
            'cocok_untuk' => 'nullable|string|max:150',
            'menu_best_seller' => 'nullable|string|max:255',
            'harga_menu_min' => 'nullable|integer|min:0',
            'harga_menu_max' => 'nullable|integer|min:0',
            'jam_buka' => 'nullable|string|max:50',
            'jam_tutup' => 'nullable|string|max:50',
            'kapasitas_orang' => 'nullable|integer|min:0',
            'fasilitas' => 'nullable|array',
            'batas_waktu_duduk' => 'nullable|string|max:50',
            'rating_google' => 'nullable|numeric|between:0,5',
            'minimal_order' => 'nullable|integer|min:0',
            'link_google_maps' => 'nullable|url|max:500',
            'kontak' => 'nullable|string|max:100',
            'gambar' => 'nullable|array',
            'sumber_data' => 'nullable|string|max:100',
            'status' => 'nullable|string|in:draft,aktif,nonaktif',
            'sentimen' => 'nullable|string|in:positif,negatif,netral,belum_dianalisis',
            'skor_sentimen' => 'nullable|numeric|between:-1,1',
        ];
    }
}
