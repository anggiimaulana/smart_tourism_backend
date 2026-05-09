<?php

namespace App\Http\Requests\Recommendation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PlanningRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

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
            'jumlah_hari'           => 'required|integer|min:1|max:14',
            'wilayah'               => 'required|array|min:1',
            'wilayah.*'             => 'string|in:Cirebon,Indramayu,Majalengka,Kuningan',
            'kategori_preferensi'   => 'nullable|array',
            'kategori_preferensi.*' => 'string|max:50',
            'budget'                => 'nullable', // Flexible: can be numeric or string
            'latitude'              => 'nullable|numeric|between:-90,90',
            'longitude'             => 'nullable|numeric|between:-180,180',
        ];
    }

    protected function failedValidation(Validator $validator): never
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validasi gagal.',
            'errors'  => $validator->errors(),
            'data' => null,
        ], 422));
    }
}
