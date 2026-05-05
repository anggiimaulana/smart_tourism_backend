<?php

namespace App\Http\Requests\Recommendation;

use App\Http\Requests\BaseApiRequest;

class RecommendationRequest extends BaseApiRequest
{
    public function rules(): array
    {
        return [
            'mode' => 'required|string|in:personal,explore,nearest',
            'wilayah' => 'nullable|string|in:Cirebon,Indramayu,Majalengka,Kuningan',
            'tipe' => 'nullable|string|in:wisata,kuliner,nongkrong',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'limit' => 'nullable|integer|min:1|max:30',
        ];
    }
}
