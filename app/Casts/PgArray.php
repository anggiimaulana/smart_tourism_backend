<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * Cast untuk kolom PostgreSQL TEXT[] array.
 * DB menyimpan: {"wifi","parkir","toilet"}
 * PHP menerima: ['wifi', 'parkir', 'toilet']
 */
class PgArray implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): array
    {
        if (is_null($value)) return [];

        // Format PostgreSQL: {"val1","val2"} atau {val1,val2}
        $value = trim($value, '{}');
        if ($value === '') return [];

        // Pecah, bersihkan kutip
        return array_map(
            fn($v) => trim($v, '"'),
            str_getcsv($value)
        );
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        if (is_null($value) || $value === []) return '{}';

        // Konversi PHP array → format PostgreSQL array literal
        $escaped = array_map(
            fn($v) => '"' . addslashes((string) $v) . '"',
            (array) $value
        );

        return '{' . implode(',', $escaped) . '}';
    }
}
