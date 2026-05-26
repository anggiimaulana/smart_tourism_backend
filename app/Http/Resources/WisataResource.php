<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WisataResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            // Identitas
            'kode'              => $this->kode,
            'nama'              => $this->nama,
            'wilayah'           => $this->wilayah,
            'kecamatan'         => $this->kecamatan,
            'kategori_utama'    => $this->kategori_utama,
            'sub_kategori'      => $this->sub_kategori,
            'jenis_tempat'      => $this->jenis_tempat,

            // Deskripsi — hanya tampil di detail
            'deskripsi'         => $this->when(
                $request->routeIs('api.v1.wisata.show'),
                $this->deskripsi
            ),

            // Lokasi
            'alamat_lengkap'    => $this->alamat_lengkap,
            'koordinat'         => $this->when(
                $this->latitude !== null,
                ['lat' => $this->latitude, 'lng' => $this->longitude]
            ),
            'link_google_maps'  => $this->link_google_maps,

            // Operasional
            'jam_buka'          => $this->jam_buka,
            'jam_tutup'         => $this->jam_tutup,
            'hari_libur_operasional' => $this->when(
                $request->routeIs('api.v1.wisata.show'),
                $this->hari_libur_operasional
            ),
            'estimasi_durasi_jam' => $this->when(
                $request->routeIs('api.v1.wisata.show'),
                $this->estimasi_durasi_jam
            ),

            // Harga
            'gratis'            => $this->gratis,
            'harga_tiket_min'   => $this->harga_tiket_min,
            'harga_tiket_max'   => $this->harga_tiket_max,

            // Rating & ulasan
            'rating_google'     => $this->rating_google,
            'jumlah_ulasan_google' => $this->jumlah_ulasan_google,

            // Fasilitas (hanya di detail)
            'fasilitas'         => $this->when(
                $request->routeIs('api.v1.wisata.show'),
                $this->fasilitas ?? []
            ),

            // Gambar (array URL)
            'gambar'            => $this->gambar ?? [],
            'gambar_utama'      => ($this->gambar ?? [])[0] ?? null,

            // Sentimen (dari AI)
            'sentimen'          => $this->sentimen,
            'skor_sentimen'     => $this->skor_sentimen,
            'total_ulasan_scraped' => $this->when(
                $request->routeIs('api.v1.wisata.show'),
                $this->total_ulasan_scraped
            ),
            'total_positif'     => $this->when(
                $request->routeIs('api.v1.wisata.show'),
                $this->total_positif
            ),
            'total_negatif'     => $this->when(
                $request->routeIs('api.v1.wisata.show'),
                $this->total_negatif
            ),

            // Kontak & sosmed (hanya di detail)
            'kontak'            => $this->when(
                $request->routeIs('api.v1.wisata.show'),
                $this->kontak
            ),
            'link_instagram'    => $this->when(
                $request->routeIs('api.v1.wisata.show'),
                $this->link_instagram
            ),
            'link_website'      => $this->when(
                $request->routeIs('api.v1.wisata.show'),
                $this->link_website
            ),

            'status'            => $this->status,

            // Relasi Kuliner & Nongkrong — hanya load jika sudah di-eager-load
            'kuliner_terdekat' => $this->when(
                $request->routeIs('api.v1.wisata.show') && $this->relationLoaded('kuliners'),
                fn() => KulinerResource::collection($this->kuliners)
            ),
            'nongkrong_terdekat' => $this->when(
                $request->routeIs('api.v1.wisata.show') && $this->relationLoaded('nongkrongs'),
                fn() => NongkrongResource::collection($this->nongkrongs)
            ),
        ];
    }
}
