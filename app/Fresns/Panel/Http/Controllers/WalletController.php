<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the Apache-2.0 License.
 */

namespace App\Fresns\Panel\Http\Controllers;

use App\Models\Config;
use App\Models\Language;
use App\Models\Plugin;
use App\Models\PluginUsage;
use Illuminate\Http\Request;
use App\Helpers\PrimaryHelper;

class WalletController extends Controller
{
    public function show()
    {
        // config keys
        $configKeys = [
            'wallet_status',
            'wallet_currency_code',
            'wallet_withdraw_status',
            'wallet_withdraw_review',
            'wallet_withdraw_verify',
            'wallet_withdraw_interval_time',
            'wallet_withdraw_rate',
            'wallet_withdraw_min_sum',
            'wallet_withdraw_max_sum',
            'wallet_withdraw_sum_limit',
            'currency_codes',
        ];

        $configs = Config::whereIn('item_key', $configKeys)->get();

        foreach ($configs as $config) {
            $params[$config->item_key] = $config->item_value;
        }

        return view('FsView::systems.wallet', compact('params'));
    }

    public function update(Request $request)
    {
        $configKeys = [
            'wallet_status',
            'wallet_currency_code',
            'wallet_withdraw_status',
            'wallet_withdraw_review',
            'wallet_withdraw_verify',
            'wallet_withdraw_interval_time',
            'wallet_withdraw_rate',
            'wallet_withdraw_min_sum',
            'wallet_withdraw_max_sum',
            'wallet_withdraw_sum_limit',
        ];

        $configs = Config::whereIn('item_key', $configKeys)->get();

        foreach ($configKeys as $configKey) {
            $config = $configs->where('item_key', $configKey)->first();
            if (! $config) {
            }

            if (! $request->has($configKey)) {
                $config->setDefaultValue();
            } else {
                $config->item_value = $request->$configKey;
            }

            $config->save();
        }

        return $this->updateSuccess();
    }

    public function rechargeIndex()
    {
        $plugins = Plugin::all();

        $plugins = $plugins->filter(function ($plugin) {
            return in_array('recharge', $plugin->scene);
        });

        $pluginUsages = PluginUsage::where('type', 1)
            ->orderBy('rank_num')
            ->with('plugin', 'names')
            ->get();

        return view('FsView::systems.wallet-recharge', compact('pluginUsages', 'plugins'));
    }

    public function rechargeStore(Request $request)
    {
        $pluginUsage = new PluginUsage;
        $pluginUsage->type = 1;
        $pluginUsage->name = $request->names[$this->defaultLanguage] ?? (current(array_filter($request->names)) ?: '');
        $pluginUsage->plugin_unikey = $request->plugin_unikey;
        $pluginUsage->parameter = $request->parameter;
        $pluginUsage->is_enable = $request->is_enable;
        $pluginUsage->rank_num = $request->rank_num;
        $pluginUsage->icon_file_url = $request->icon_file_url;
        $pluginUsage->save();

        if ($request->file('icon_file')) {
            $wordBody = [
                'platform' => 4,
                'type' => 1,
                'tableType' => 3,
                'tableName' => 'plugin_usages',
                'tableColumn' => 'icon_file_id',
                'tableId' => $pluginUsage->id,
                'file' => $request->file('icon_file'),
            ];
            $fresnsResp = \FresnsCmdWord::plugin('Fresns')->uploadFile($wordBody);
            if ($fresnsResp->isErrorResponse()) {
                return $fresnsResp->errorResponse();
            }
            $fileId = PrimaryHelper::fresnsFileIdByFid($fresnsResp->getData('fid'));

            $pluginUsage->icon_file_id = $fileId;
            $pluginUsage->icon_file_url = $fresnsResp->getData('imageConfigUrl');
            $pluginUsage->save();
        }

        if ($request->update_name) {
            foreach ($request->names as $langTag => $content) {
                $language = Language::tableName('plugin_usages')
                    ->where('table_id', $pluginUsage->id)
                    ->where('lang_tag', $langTag)
                    ->first();

                if (! $language) {
                    // create but no content
                    if (! $content) {
                        continue;
                    }
                    $language = new Language();
                    $language->fill([
                        'table_name' => 'plugin_usages',
                        'table_column' => 'name',
                        'table_id' => $pluginUsage->id,
                        'lang_tag' => $langTag,
                    ]);
                }

                $language->lang_content = $content;
                $language->save();
            }
        }

        return $this->createSuccess();
    }

    public function rechargeUpdate(PluginUsage $pluginUsage, Request $request)
    {
        $pluginUsage->name = $request->names[$this->defaultLanguage] ?? (current(array_filter($request->names)) ?: '');
        $pluginUsage->plugin_unikey = $request->plugin_unikey;
        $pluginUsage->parameter = $request->parameter;
        $pluginUsage->is_enable = $request->is_enable;
        $pluginUsage->rank_num = $request->rank_num;

        if ($request->file('icon_file')) {
            $wordBody = [
                'platform' => 4,
                'type' => 1,
                'tableType' => 3,
                'tableName' => 'plugin_usages',
                'tableColumn' => 'icon_file_id',
                'tableId' => $pluginUsage->id,
                'file' => $request->file('icon_file'),
            ];
            $fresnsResp = \FresnsCmdWord::plugin('Fresns')->uploadFile($wordBody);
            if ($fresnsResp->isErrorResponse()) {
                return $fresnsResp->errorResponse();
            }
            $fileId = PrimaryHelper::fresnsFileIdByFid($fresnsResp->getData('fid'));

            $pluginUsage->icon_file_id = $fileId;
            $pluginUsage->icon_file_url = $fresnsResp->getData('imageConfigUrl');
        } else if($pluginUsage->icon_file_url != $request->icon_file_url) {
            $pluginUsage->icon_file_id = null;
            $pluginUsage->icon_file_url = $request->icon_file_url;
        }

        $pluginUsage->save();

        if ($request->update_name) {
            foreach ($request->names as $langTag => $content) {
                $language = Language::tableName('plugin_usages')
                    ->where('table_id', $pluginUsage->id)
                    ->where('lang_tag', $langTag)
                    ->first();

                if (! $language) {
                    // create but no content
                    if (! $content) {
                        continue;
                    }
                    $language = new Language();
                    $language->fill([
                        'table_name' => 'plugin_usages',
                        'table_column' => 'name',
                        'table_id' => $pluginUsage->id,
                        'lang_tag' => $langTag,
                    ]);
                }

                $language->lang_content = $content;
                $language->save();
            }
        }

        return $this->updateSuccess();
    }

    public function withdrawIndex()
    {
        $pluginUsages = PluginUsage::where('type', 2)
            ->orderBy('rank_num')
            ->with('plugin')
            ->get();

        $plugins = Plugin::all();
        $plugins = $plugins->filter(function ($plugin) {
            return in_array('withdraw', $plugin->scene);
        });

        return view('FsView::systems.wallet-withdraw', compact('pluginUsages', 'plugins'));
    }

    public function withdrawStore(Request $request)
    {
        $pluginUsage = new PluginUsage;
        $pluginUsage->type = 2;
        $pluginUsage->name = $request->names[$this->defaultLanguage] ?? (current(array_filter($request->names)) ?: '');
        $pluginUsage->plugin_unikey = $request->plugin_unikey;
        $pluginUsage->parameter = $request->parameter;
        $pluginUsage->is_enable = $request->is_enable;
        $pluginUsage->rank_num = $request->rank_num;
        $pluginUsage->icon_file_url = $request->icon_file_url;
        $pluginUsage->save();

        if ($request->file('icon_file')) {
            $wordBody = [
                'platform' => 4,
                'type' => 1,
                'tableType' => 3,
                'tableName' => 'plugin_usages',
                'tableColumn' => 'icon_file_id',
                'tableId' => $pluginUsage->id,
                'file' => $request->file('icon_file'),
            ];
            $fresnsResp = \FresnsCmdWord::plugin('Fresns')->uploadFile($wordBody);
            if ($fresnsResp->isErrorResponse()) {
                return $fresnsResp->errorResponse();
            }
            $fileId = PrimaryHelper::fresnsFileIdByFid($fresnsResp->getData('fid'));

            $pluginUsage->icon_file_id = $fileId;
            $pluginUsage->icon_file_url = $fresnsResp->getData('imageConfigUrl');
            $pluginUsage->save();
        }

        if ($request->update_name) {
            foreach ($request->names as $langTag => $content) {
                $language = Language::tableName('plugin_usages')
                    ->where('table_id', $pluginUsage->id)
                    ->where('lang_tag', $langTag)
                    ->first();

                if (! $language) {
                    // create but no content
                    if (! $content) {
                        continue;
                    }
                    $language = new Language();
                    $language->fill([
                        'table_name' => 'plugin_usages',
                        'table_column' => 'name',
                        'table_id' => $pluginUsage->id,
                        'lang_tag' => $langTag,
                    ]);
                }

                $language->lang_content = $content;
                $language->save();
            }
        }

        return $this->createSuccess();
    }

    public function withdrawUpdate(PluginUsage $pluginUsage, Request $request)
    {
        $pluginUsage->name = $request->names[$this->defaultLanguage] ?? (current(array_filter($request->names)) ?: '');
        $pluginUsage->plugin_unikey = $request->plugin_unikey;
        $pluginUsage->parameter = $request->parameter;
        $pluginUsage->is_enable = $request->is_enable;
        $pluginUsage->rank_num = $request->rank_num;

        if ($request->file('icon_file')) {
            $wordBody = [
                'platform' => 4,
                'type' => 1,
                'tableType' => 3,
                'tableName' => 'plugin_usages',
                'tableColumn' => 'icon_file_id',
                'tableId' => $pluginUsage->id,
                'file' => $request->file('icon_file'),
            ];
            $fresnsResp = \FresnsCmdWord::plugin('Fresns')->uploadFile($wordBody);
            if ($fresnsResp->isErrorResponse()) {
                return $fresnsResp->errorResponse();
            }
            $fileId = PrimaryHelper::fresnsFileIdByFid($fresnsResp->getData('fid'));

            $pluginUsage->icon_file_id = $fileId;
            $pluginUsage->icon_file_url = $fresnsResp->getData('imageConfigUrl');
        } else if($pluginUsage->icon_file_url != $request->icon_file_url) {
            $pluginUsage->icon_file_id = null;
            $pluginUsage->icon_file_url = $request->icon_file_url;
        }

        $pluginUsage->save();

        if ($request->update_name) {
            foreach ($request->names as $langTag => $content) {
                $language = Language::tableName('plugin_usages')
                    ->where('table_id', $pluginUsage->id)
                    ->where('lang_tag', $langTag)
                    ->first();

                if (! $language) {
                    // create but no content
                    if (! $content) {
                        continue;
                    }
                    $language = new Language();
                    $language->fill([
                        'table_name' => 'plugin_usages',
                        'table_column' => 'name',
                        'table_id' => $pluginUsage->id,
                        'lang_tag' => $langTag,
                    ]);
                }

                $language->lang_content = $content;
                $language->save();
            }
        }

        return $this->updateSuccess();
    }
}
