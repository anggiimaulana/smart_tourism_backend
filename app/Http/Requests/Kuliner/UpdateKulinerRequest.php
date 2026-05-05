<?php

namespace App\Http\Requests\Kuliner;

use App\Http\Requests\BaseApiRequest;
use App\Http\Requests\Kuliner\StoreKulinerRequest;

class UpdateKulinerRequest extends BaseApiRequest
{
    public function rules(): array
    {
        return (new StoreKulinerRequest())->rules();
    }
}
