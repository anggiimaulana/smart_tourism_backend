<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KulinerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $isDetail = $request->routeIs('api.v1.kuliner.show');

        return [
            'kode'              => $this->kode,
            'nama'              => $this->nama,
            'wilayah'           => $this->wilayah,
            'kecamatan'         => $this->kecamatan,
            'jenis_tempat'      => $this->jenis_tempat,
            'kategori_menu_utama' => $this->kategori_menu_utama,

            // Detail saja
            'menu_unggulan'     => $this->when($isDetail, $this->menu_unggulan),
            'makanan_khas_daerah' => $this->when($isDetail, $this->makanan_khas_daerah),
            'nama_makanan_khas' => $this->when($isDetail, $this->nama_makanan_khas),

            'alamat_lengkap'    => $this->alamat_lengkap,
            'koordinat'         => $this->when(
                $this->latitude !== null,
                ['lat' => $this->latitude, 'lng' => $this->longitude]
            ),
            'link_google_maps'  => $this->link_google_maps,

            'jam_buka'          => $this->jam_buka,
            'jam_tutup'         => $this->jam_tutup,
            'harga_menu_min'    => $this->harga_menu_min,
            'harga_menu_max'    => $this->harga_menu_max,

            'kapasitas_orang'   => $this->when($isDetail, $this->kapasitas_orang),
            'sertifikat_halal'  => $this->sertifikat_halal,

            'fasilitas'         => $this->when($isDetail, $this->fasilitas ?? []),
            'gambar'            => $this->gambar ?? [],
            'gambar_utama'      => $this->gambar[0] ?? null,

            'rating_google'     => $this->rating_google,
            'jumlah_ulasan_google' => $this->jumlah_ulasan_google,

            'sentimen'          => $this->sentimen,
            'skor_sentimen'     => $this->skor_sentimen,
            'total_positif'     => $this->when($isDetail, $this->total_positif),
            'total_negatif'     => $this->when($isDetail, $this->total_negatif),

            'kontak'            => $this->when($isDetail, $this->kontak),
            'catatan'           => $this->when($isDetail, $this->catatan),
            'status'            => $this->status,

            // Wisata terdekat — hanya load jika sudah di-eager-load
            'wisata_terdekat'   => $this->when(
                $isDetail && $this->relationLoaded('wisataTerdekat'),
                fn() => $this->wisataTerdekat
                    ? ['kode' => $this->wisataTerdekat->kode, 'nama' => $this->wisataTerdekat->nama]
                    : null
            ),
        ];
    }
}
