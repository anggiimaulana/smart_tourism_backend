<?php

namespace App\Http\Requests\Recommendation;

use App\Http\Requests\BaseApiRequest;

class RecommendationRequest extends BaseApiRequest
{
    protected function prepareForValidation(): void
    {
        if ($this->has('wilayah') && is_array($this->wilayah)) {
            $this->merge([
                'wilayah' => array_map(fn($w) => ucfirst(strtolower($w)), $this->wilayah)
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'mode'      => 'required|string|in:personal,popular,nearby',
            'wilayah'   => 'nullable|array',
            'wilayah.*' => 'string|in:Cirebon,Indramayu,Majalengka,Kuningan',
            'tipe'      => 'nullable|string|in:wisata,kuliner,nongkrong,all',
            'latitude'  => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'limit'     => 'nullable|integer|min:1|max:50',
        ];
    }
}
