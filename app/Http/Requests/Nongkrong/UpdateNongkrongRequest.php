<?php

namespace App\Http\Requests\Nongkrong;

use App\Http\Requests\BaseApiRequest;
use App\Http\Requests\Nongkrong\StoreNongkrongRequest;

class UpdateNongkrongRequest extends BaseApiRequest
{
    public function rules(): array
    {
        return (new StoreNongkrongRequest())->rules();
    }
}
