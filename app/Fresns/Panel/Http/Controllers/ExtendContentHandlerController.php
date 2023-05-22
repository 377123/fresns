<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace App\Fresns\Panel\Http\Controllers;

use App\Models\Config;
use App\Models\Plugin;
use Illuminate\Http\Request;

class ExtendContentHandlerController extends Controller
{
    public function index()
    {
        // config keys
        $configKeys = [
            'ip_service',
            'map_service',
            'notifications_service',
            'content_review_service',
            'post_list_service',
            'post_follow_service',
            'post_nearby_service',
            'post_detail_service',
            'comment_list_service',
            'comment_follow_service',
            'comment_nearby_service',
            'comment_detail_service',
            'search_users_service',
            'search_groups_service',
            'search_hashtags_service',
            'search_posts_service',
            'search_comments_service',
            'view_user_service',
            'view_group_service',
            'view_hashtag_service',
            'view_post_service',
            'view_comment_service',
        ];
        $configs = Config::whereIn('item_key', $configKeys)->get();

        foreach ($configs as $config) {
            $params[$config->item_key] = $config->item_value;
        }

        $pluginScenes = [
            'extendIp',
            'extendMap',
            'extendReview',
            'extendData',
            'extendNotification',
            'extendSearch',
            'extendView',
        ];
        $plugins = Plugin::all();
        $pluginParams = [];
        foreach ($pluginScenes as $scene) {
            $pluginParams[$scene] = $plugins->filter(function ($plugin) use ($scene) {
                return in_array($scene, $plugin->scene);
            });
        }

        return view('FsView::extends.content-handler', compact('pluginParams', 'params'));
    }

    public function update(Request $request)
    {
        // config keys
        $configKeys = [
            'ip_service',
            'map_service',
            'notifications_service',
            'content_review_service',
            'post_list_service',
            'post_follow_service',
            'post_nearby_service',
            'post_detail_service',
            'comment_list_service',
            'comment_follow_service',
            'comment_nearby_service',
            'comment_detail_service',
            'search_users_service',
            'search_groups_service',
            'search_hashtags_service',
            'search_posts_service',
            'search_comments_service',
            'view_user_service',
            'view_group_service',
            'view_hashtag_service',
            'view_post_service',
            'view_comment_service',
        ];

        $configs = Config::whereIn('item_key', $configKeys)->get();

        foreach ($configKeys as $configKey) {
            $config = $configs->where('item_key', $configKey)->first();
            if (! $config) {
                continue;
            }

            if (! $request->has($configKey)) {
                $config->setDefaultValue();
                $config->save();
                continue;
            }

            $config->item_value = $request->$configKey;
            $config->save();
        }

        return $this->updateSuccess();
    }
}
