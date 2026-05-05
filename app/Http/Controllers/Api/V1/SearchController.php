<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends BaseApiController
{
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'q'       => 'required|string|min:2|max:100',
            'wilayah' => 'nullable|string|in:Indramayu,Cirebon,Majalengka,Kuningan',
            'tipe'    => 'nullable|string|in:wisata,kuliner,nongkrong',
            'limit'   => 'nullable|integer|min:1|max:30',
        ]);

        $q      = $request->input('q');
        $wilayah = $request->input('wilayah');
        $tipe   = $request->input('tipe');
        $limit  = $request->input('limit', 10);

        // Gunakan view v_all_tempat + FTS dari 03_fts.sql
        // Fallback ke ILIKE jika FTS tidak match
        $bindings = ['query' => $q, 'limit' => (int) $limit];
        $wilayahClause = $wilayah ? "AND wilayah = :wilayah" : "";
        $tipeClause    = $tipe    ? "AND tipe = :tipe"       : "";

        if ($wilayah) $bindings['wilayah'] = $wilayah;
        if ($tipe)    $bindings['tipe']    = $tipe;

        $results = DB::select("
            SELECT
                kode, nama, tipe, wilayah, kecamatan,
                alamat_lengkap, gambar,
                harga_min, harga_max,
                jam_buka, jam_tutup,
                rating_google, sentimen, skor_sentimen,
                link_google_maps,
                -- Ranking: FTS score
                ts_rank(fts, plainto_tsquery('indonesian', :query)) AS rank
            FROM v_all_tempat
            WHERE (
                fts @@ plainto_tsquery('indonesian', :query)
                OR nama ILIKE '%' || :query || '%'
            )
            {$wilayahClause}
            {$tipeClause}
            ORDER BY rank DESC, rating_google DESC NULLS LAST
            LIMIT :limit
        ", $bindings);

        return $this->success($results, 'Hasil pencarian.', 200, [
            'query' => $q,
            'total' => count($results),
        ]);
    }
}
