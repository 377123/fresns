<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace App\Fresns\Words\Basic\DTO;

use Fresns\DTO\DTO;

class UploadSessionLogDTO extends DTO
{
    public function rules(): array
    {
        return [
            'type' => ['integer', 'required'],
            'platformId' => ['integer', 'required', 'between:1,13'],
            'version' => ['string', 'required'],
            'appId' => ['string', 'nullable'],
            'langTag' => ['string', 'nullable'],
            'fskey' => ['string', 'nullable', 'exists:App\Models\Plugin,fskey'],
            'aid' => ['string', 'nullable'],
            'uid' => ['integer', 'nullable'],
            'objectName' => ['string', 'required'],
            'objectAction' => ['string', 'required'],
            'objectResult' => ['integer', 'required', 'in:1,2,3'],
            'objectOrderId' => ['integer', 'nullable'],
            'deviceInfo' => ['array', 'nullable'],
            'deviceToken' => ['string', 'nullable'],
            'moreJson' => ['array', 'nullable'],
        ];
    }
}
