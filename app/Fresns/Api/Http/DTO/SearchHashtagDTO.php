<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace App\Fresns\Api\Http\DTO;

use Fresns\DTO\DTO;

class SearchHashtagDTO extends DTO
{
    public function rules(): array
    {
        return [
            'searchKey' => ['string', 'required'],
            'createDate' => ['string', 'nullable', 'in:today,yesterday,week,lastWeek,month,lastMonth,year,lastYear'],
            'createDateGt' => ['date_format:Y-m-d', 'nullable', 'before:createDateLt'], // hashtags->created_at
            'createDateLt' => ['date_format:Y-m-d', 'nullable', 'after:createDateGt'],
            'viewCountGt' => ['integer', 'nullable', 'lt:viewCountLt'], // hashtags->view_count
            'viewCountLt' => ['integer', 'nullable', 'gt:viewCountGt'],
            'likeCountGt' => ['integer', 'nullable', 'lt:likeCountLt'], // hashtags->like_count
            'likeCountLt' => ['integer', 'nullable', 'gt:likeCountGt'],
            'dislikeCountGt' => ['integer', 'nullable', 'lt:dislikeCountLt'], // hashtags->dislike_count
            'dislikeCountLt' => ['integer', 'nullable', 'gt:dislikeCountGt'],
            'followCountGt' => ['integer', 'nullable', 'lt:followCountLt'], // hashtags->follow_count
            'followCountLt' => ['integer', 'nullable', 'gt:followCountGt'],
            'blockCountGt' => ['integer', 'nullable', 'lt:blockCountLt'], // hashtags->block_count
            'blockCountLt' => ['integer', 'nullable', 'gt:blockCountGt'],
            'postCountGt' => ['integer', 'nullable', 'lt:postCountLt'], // hashtags->post_count
            'postCountLt' => ['integer', 'nullable', 'gt:postCountGt'],
            'postDigestCountGt' => ['integer', 'nullable', 'lt:postDigestCountLt'], // hashtags->post_digest_count
            'postDigestCountLt' => ['integer', 'nullable', 'gt:postDigestCountGt'],
            'orderType' => ['string', 'nullable', 'in:createDate,random,view,like,follow,block,post,postDigest'],
            'orderDirection' => ['string', 'nullable', 'in:asc,desc'],
            'whitelistKeys' => ['string', 'nullable'],
            'blacklistKeys' => ['string', 'nullable'],
            'pageSize' => ['integer', 'nullable', 'between:1,30'],
            'page' => ['integer', 'nullable'],
        ];
    }
}
