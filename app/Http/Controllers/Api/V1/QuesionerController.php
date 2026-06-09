<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Questionnaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuesionerController extends Controller
{
    public function index()
    {
        $data = Questionnaire::latest()->paginate(10);

        return response()->json([
            'message' => 'Data retrieved successfully',
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'usia' => 'nullable|integer|min:10|max:150',
            'pekerjaan' => 'required|string|max:100',
            'institusi' => 'nullable|string|max:255',
            'domisili' => 'nullable|string|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'tujuan_kunjungan' => 'nullable|string|max:100',
            'frekuensi_kunjungan' => 'nullable|string|max:50',
            'fitur_favorit' => 'nullable|string|max:100',
            'kemudahan_navigasi' => 'nullable|integer|min:1|max:5',
            'kecepatan_akses' => 'nullable|integer|min:1|max:5',
            'tampilan_desain' => 'nullable|integer|min:1|max:5',
            'kelengkapan_konten' => 'nullable|integer|min:1|max:5',
            'rating' => 'nullable|integer|min:1|max:5',
            'kesan' => 'nullable|string',
            'pesan' => 'nullable|string',
            'saran' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $questionnaire = Questionnaire::create($request->only([
            'email', 'nama', 'jenis_kelamin', 'usia', 'pekerjaan',
            'institusi', 'domisili', 'no_telepon',
            'tujuan_kunjungan', 'frekuensi_kunjungan', 'fitur_favorit',
            'kemudahan_navigasi', 'kecepatan_akses', 'tampilan_desain',
            'kelengkapan_konten', 'rating', 'kesan', 'pesan', 'saran',
        ]));

        return response()->json([
            'message' => 'Data kuesioner berhasil disimpan',
            'data' => $questionnaire
        ], 201);
    }
}
