<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace App\Models;

class Plugin extends Model
{
    const TYPE_PLUGIN = 1;
    const TYPE_PANEL = 2;
    const TYPE_ENGINE = 3;
    const TYPE_THEME = 4;

    use Traits\IsEnabledTrait;

    protected $casts = [
        'scene' => 'array',
    ];

    public function getSceneAttribute($value)
    {
        if (is_string($value)) {
            $value = json_decode($value, true);
        }

        return $value ?? [];
    }

    public function scopeType($query, $value)
    {
        return $query->where('type', $value);
    }
}
