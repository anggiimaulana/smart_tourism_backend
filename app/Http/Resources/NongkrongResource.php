<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NongkrongResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $isDetail = $request->routeIs('api.v1.nongkrong.show');

        return [
            'kode'              => $this->kode,
            'nama'              => $this->nama,
            'wilayah'           => $this->wilayah,
            'kecamatan'         => $this->kecamatan,
            'konsep_suasana'    => $this->konsep_suasana,
            'target_pengunjung' => $this->when($isDetail, $this->target_pengunjung),
            'cocok_untuk'       => $this->when($isDetail, $this->cocok_untuk),

            'menu_best_seller'  => $this->when($isDetail, $this->menu_best_seller),
            'harga_menu_min'    => $this->harga_menu_min,
            'harga_menu_max'    => $this->harga_menu_max,
            'minimal_order'     => $this->minimal_order,

            'alamat_lengkap'    => $this->alamat_lengkap,
            'koordinat'         => $this->when(
                $this->latitude !== null,
                ['lat' => $this->latitude, 'lng' => $this->longitude]
            ),
            'link_google_maps'  => $this->link_google_maps,

            'jam_buka'          => $this->jam_buka,
            'jam_tutup'         => $this->jam_tutup,
            'kapasitas_orang'   => $this->when($isDetail, $this->kapasitas_orang),
            'batas_waktu_duduk' => $this->when($isDetail, $this->batas_waktu_duduk),

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

            // Wisata referensi — hanya load jika sudah di-eager-load
            'wisata_referensi'  => $this->when(
                $isDetail && $this->relationLoaded('wisataRef'),
                fn() => $this->wisataRef
                    ? ['kode' => $this->wisataRef->kode, 'nama' => $this->wisataRef->nama]
                    : null
            ),
        ];
    }
}
