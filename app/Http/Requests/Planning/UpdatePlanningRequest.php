<?php

namespace App\Http\Requests\Planning;

use App\Http\Requests\BaseApiRequest;

class UpdatePlanningRequest extends BaseApiRequest
{
    public function rules(): array
    {
        return [
            'judul' => 'sometimes|nullable|string|max:255',
            'wilayah' => 'sometimes|required|array|min:1',
            'wilayah.*' => 'string|in:Cirebon,Indramayu,Majalengka,Kuningan',
            'tanggal_mulai' => 'sometimes|nullable|date',
            'tanggal_selesai' => 'sometimes|nullable|date|after_or_equal:tanggal_mulai',
            'jumlah_orang' => 'sometimes|nullable|integer|min:1|max:100',
            'budget_total' => 'sometimes|nullable|integer|min:0',
            'catatan' => 'sometimes|nullable|string|max:1000',
            'items' => 'sometimes|nullable|array',
            'status' => 'sometimes|nullable|string|in:draft,finalized,selesai',
        ];
    }
}
