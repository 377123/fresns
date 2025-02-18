@extends('FsView::commons.sidebarLayout')

@section('sidebar')
    @include('FsView::extensions.sidebar')
@endsection

@section('content')
    <!--engine header-->
    <div class="row mb-4 border-bottom">
        <div class="col-lg-7">
            <h3>{{ __('FsLang::panel.sidebar_engines') }}</h3>
            <p class="text-secondary"><i class="bi bi-laptop"></i> {{ __('FsLang::panel.sidebar_engines_intro') }}</p>
        </div>
        <div class="col-lg-5">
            <div class="input-group mt-2 mb-4 justify-content-lg-end">
                <a class="btn btn-outline-primary" href="{{ route('panel.website.index') }}" role="button">{{ __('FsLang::panel.button_config').'('.__('FsLang::panel.sidebar_website').')' }}</a>
                <a class="btn btn-outline-secondary" href="#" role="button">{{ __('FsLang::panel.button_support') }}</a>
            </div>
        </div>
    </div>
    <!--engine list-->
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr class="table-info">
                    <th scope="col">{{ __('FsLang::panel.sidebar_engines') }} <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('FsLang::panel.engine_table_name_desc') }}"></i></th>
                    <th scope="col">{{ __('FsLang::panel.author') }}</th>
                    <th scope="col">{{ __('FsLang::panel.engine_theme_title') }}</th>
                    <th scope="col" class="text-center">{{ __('FsLang::panel.table_options') }} <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('FsLang::panel.engine_table_options_desc') }}"></i></th>
                </tr>
            </thead>
            <tbody>
                <!--engine list-->
                @foreach ($engines as $engine)
                    <tr>
                        <th scope="row" class="py-3">
                            <img src="/assets/plugins/{{ $engine->fskey }}/fresns.png" class="me-2" width="44" height="44">
                            <a href="{{ $marketplaceUrl.'/detail/'.$engine->fskey }}" target="_blank" class="link-dark fresns-link">{{ $engine->name }}</a>
                            <span class="badge bg-secondary fs-9">{{ $engine->version }}</span>
                            @if ($engine->is_upgrade)
                                <a href="{{ route('panel.upgrades') }}" class="badge rounded-pill bg-danger link-light fs-9 fresns-link">{{ __('FsLang::panel.new_version') }}</a>
                            @endif
                        </th>
                        <td><a href="{{ $engine->author_link }}" target="_blank" class="link-info fresns-link fs-7">{{ $engine->author }}</a></td>
                        <td>
                            <span class="badge bg-light text-dark"><i class="bi bi-laptop"></i>
                                @if ($desktopPlugin = optional($configs->where('item_key', $engine->fskey.'_Desktop')->first())->item_value )
                                    {{ $plugins[$desktopPlugin] ?? $desktopPlugin ?? '' }}
                                @else
                                    {{ __('FsLang::panel.option_not_set') }}
                                @endif
                            </span>
                            <span class="badge bg-light text-dark"><i class="bi bi-phone"></i>
                                @if ($mobilePlugin = optional($configs->where('item_key', $engine->fskey.'_Mobile')->first())->item_value)
                                    {{ $plugins[$mobilePlugin] ?? $mobilePlugin ?? '' }}
                                @else
                                    {{ __('FsLang::panel.option_not_set') }}
                                @endif
                            </span>
                        </td>
                        <td class="text-center">
                            @if ($engine->is_enabled)
                                <button type="button" class="btn btn-outline-secondary btn-sm plugin-manage" data-action="{{ route('panel.plugin.update', ['plugin' => $engine->fskey]) }}" data-enable="0">{{ __('FsLang::panel.button_deactivate') }}</button>
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-action="{{ route('panel.engine.theme.update', ['fskey' => $engine->fskey]) }}"
                                    data-fskey="{{ $engine->fskey }}"
                                    data-desktop_plugin="{{ optional($configs->where('item_key', $engine->fskey . '_Desktop')->first())->item_value }}"
                                    data-mobile_plugin="{{ optional($configs->where('item_key', $engine->fskey . '_Mobile')->first())->item_value }}"
                                    data-bs-target="#themeSetting">{{ __('FsLang::panel.engine_theme_title') }}</button>
                                @if ($engine->settings_path)
                                    <a href="{{ route('panel.iframe.setting', ['url' => $engine->settings_path]) }}" class="btn btn-primary btn-sm">{{ __('FsLang::panel.button_setting') }}</a>
                                @endif
                            @else
                                <button type="button" class="btn btn-outline-success btn-sm plugin-manage" data-action="{{ route('panel.plugin.update', ['plugin' => $engine->fskey]) }}" data-enable="1">{{ __('FsLang::panel.button_activate') }}</button>
                                <button type="button" class="btn btn-link btn-sm ms-2 text-danger fresns-link plugin-uninstall-button" data-action="{{ route('panel.plugin.uninstall', ['plugin' => $engine->fskey]) }}" data-name="{{ $engine->name }}" data-clear_data_desc="{{ __('FsLang::panel.option_uninstall_plugin_data') }}" data-bs-toggle="modal" data-bs-target="#uninstallConfirm">{{ __('FsLang::panel.button_uninstall') }}</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!--engine list end-->

    <!-- Modal -->
    <div class="modal fade" id="themeSetting" tabindex="-1" aria-labelledby="themeSetting" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('FsLang::panel.engine_theme_title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        @csrf
                        @method('put')
                        <div class="form-floating mb-3">
                            <select class="form-select" id="desktopTheme">
                                <option value="" >{{ __('FsLang::panel.option_no_use') }}</option>
                                @foreach ($themes as $theme)
                                    <option value="{{ $theme->fskey }}" @if($themeFskey['desktop'] == $theme->fskey) selected @endif>{{ $theme->name }}</option>
                                @endforeach
                            </select>
                            <label for="desktopTheme"><i class="bi bi-laptop"></i> {{ __('FsLang::panel.engine_theme_desktop') }}</label>
                        </div>
                        <div class="form-floating mb-4">
                            <select class="form-select" id="mobileTheme">
                                <option value="">{{ __('FsLang::panel.option_no_use') }}</option>
                                @foreach ($themes as $theme)
                                    <option value="{{ $theme->fskey }}" @if($themeFskey['mobile'] == $theme->fskey) selected @endif>{{ $theme->name }}</option>
                                @endforeach
                            </select>
                            <label for="mobileTheme"><i class="bi bi-phone"></i> {{ __('FsLang::panel.engine_theme_mobile') }}</label>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">{{ __('FsLang::panel.button_save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
