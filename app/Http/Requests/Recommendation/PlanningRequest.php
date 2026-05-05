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

    public function rules(): array
    {
        return [
            'jumlah_hari'    => 'required|integer|min:1|max:7',
            'wilayah'        => 'required|array|min:1',
            'wilayah.*'      => 'string|in:Cirebon,Indramayu,Majalengka,Kuningan',
            'preferensi'     => 'nullable|array',
            'preferensi.*'   => 'string|max:50',
            'budget'         => 'nullable|string|in:murah,sedang,mahal',
            'latitude'       => 'nullable|numeric|between:-90,90',
            'longitude'      => 'nullable|numeric|between:-180,180',
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
