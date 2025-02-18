<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace App\Fresns\Words\Send\DTO;

use Fresns\DTO\DTO;

class SendNotificationDTO extends DTO
{
    public function rules(): array
    {
        return [
            'uid' => ['integer', 'required'],
            'type' => ['integer', 'required', 'between:1,9'],
            'content' => ['string', 'nullable'],
            'isMarkdown' => ['Boolean', 'nullable'],
            'isMultilingual' => ['Boolean', 'nullable', 'required_with:content'],
            'isAccessPlugin' => ['Boolean', 'nullable'],
            'pluginFskey' => ['integer', 'nullable', 'exists:App\Models\Plugin,fskey'],
            'actionUid' => ['integer', 'nullable'],
            'actionType' => ['integer', 'nullable', 'between:1,10', 'required_with:actionObject', 'required_with:actionFsid'],
            'actionObject' => ['integer', 'nullable', 'between:1,8', 'required_with:actionFsid'],
            'actionFsid' => ['string', 'nullable', 'required_with:actionObject'],
            'contentFsid' => ['string', 'nullable'],
        ];
    }
}
