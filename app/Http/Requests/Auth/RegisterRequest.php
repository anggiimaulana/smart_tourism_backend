<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Kolom di DB adalah 'nama', bukan 'name'
            'nama'     => 'required|string|min:2|max:150',
            'email'    => 'required|email:rfc,dns|unique:users,email|max:255',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required'       => 'Nama wajib diisi.',
            'email.unique'        => 'Email sudah terdaftar.',
            'password.confirmed'  => 'Konfirmasi password tidak cocok.',
        ];
    }

    protected function failedValidation(Validator $validator): never
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validasi gagal.',
            'errors'  => $validator->errors(),
            'data'    => null,
        ], 422));
    }
}
