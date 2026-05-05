<?php

namespace App\Http\Requests\Wisata;

use App\Http\Requests\BaseApiRequest;

class StoreWisataRequest extends BaseApiRequest
{
    public function rules(): array
    {
        return [
            'kode' => 'nullable|string|max:50',
            'nama' => 'required|string|min:2|max:255',
            'wilayah' => 'required|string|in:Cirebon,Indramayu,Majalengka,Kuningan',
            'kecamatan' => 'nullable|string|max:150',
            'alamat_lengkap' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'kategori_utama' => 'nullable|string|max:100',
            'sub_kategori' => 'nullable|string|max:100',
            'jenis_tempat' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string',
            'harga_tiket_min' => 'nullable|integer|min:0',
            'harga_tiket_max' => 'nullable|integer|min:0',
            'gratis' => 'nullable|boolean',
            'jam_buka' => 'nullable|string|max:50',
            'jam_tutup' => 'nullable|string|max:50',
            'fasilitas' => 'nullable|array',
            'aksesibilitas' => 'nullable|array',
            'moda_transportasi' => 'nullable|array',
            'rating_google' => 'nullable|numeric|between:0,5',
            'jumlah_ulasan_google' => 'nullable|integer|min:0',
            'link_google_maps' => 'nullable|url|max:500',
            'link_instagram' => 'nullable|url|max:500',
            'link_website' => 'nullable|url|max:500',
            'kontak' => 'nullable|string|max:100',
            'gambar' => 'nullable|array',
            'sumber_data' => 'nullable|string|max:100',
            'status' => 'nullable|string|in:draft,aktif,nonaktif',
            'sentimen' => 'nullable|string|in:positif,negatif,netral',
            'skor_sentimen' => 'nullable|numeric|between:-1,1',
        ];
    }
}
