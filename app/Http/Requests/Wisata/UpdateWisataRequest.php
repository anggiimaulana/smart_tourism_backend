<?php

namespace App\Http\Requests\Wisata;

use App\Http\Requests\BaseApiRequest;
use App\Http\Requests\Wisata\StoreWisataRequest;

class UpdateWisataRequest extends BaseApiRequest
{
    public function rules(): array
    {
        return (new StoreWisataRequest())->rules();
    }
}
