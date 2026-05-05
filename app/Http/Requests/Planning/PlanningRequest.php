<?php

namespace App\Http\Requests\Planning;

use App\Http\Requests\BaseApiRequest;

class PlanningRequest extends BaseApiRequest
{
    public function rules(): array
    {
        return [
            'judul' => 'nullable|string|max:255',
            'wilayah' => 'required|array|min:1',
            'wilayah.*' => 'string|in:Cirebon,Indramayu,Majalengka,Kuningan',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'jumlah_orang' => 'nullable|integer|min:1|max:100',
            'budget_total' => 'nullable|integer|min:0',
            'catatan' => 'nullable|string|max:1000',
            'items' => 'nullable|array',
            'status' => 'nullable|string|in:draft,finalized,selesai',
        ];
    }
}
