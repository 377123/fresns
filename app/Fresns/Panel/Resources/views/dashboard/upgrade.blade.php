@extends('FsView::commons.sidebarLayout')

@section('sidebar')
    @include('FsView::dashboard.sidebar')
@endsection

@section('content')
    <div class="row mb-4 border-bottom">
        <div class="col-lg-5">
            <h3>{{ __('FsLang::panel.sidebar_upgrades') }}</h3>
            <p class="text-secondary">{{ __('FsLang::panel.sidebar_upgrades_intro') }} {{ $versionCheckTime }}</p>
        </div>
        <div class="col-lg-7">
            <div class="input-group mt-2 mb-4 justify-content-lg-end">
                <form method="post" action="{{ route('panel.upgrade.check') }}">
                    @csrf
                    @method('patch')
                    <button class="btn btn-primary rounded-0 rounded-start" type="submit"><i class="bi bi-arrow-clockwise"></i> {{ __('FsLang::panel.button_check_upgrade') }}</button>
                </form>
                <button class="btn btn-outline-success" type="button" data-bs-toggle="modal" data-bs-target="#resetUpgradeCodeModal">{{ __('FsLang::panel.button_reset'). ' Code' }}</button>
                <a class="btn btn-outline-secondary" href="#" role="button">{{ __('FsLang::panel.button_support') }}</a>
            </div>
        </div>
    </div>

    <!--Fresns Core-->
    <div class="card mb-4">
        <div class="card-header">{{ __('FsLang::panel.fresns_core') }}</div>
        <div class="card-body">
            @if ($checkVersion)
                <h5 class="card-title">{{ __('FsLang::tips.upgrade_fresns') }}</h5>
                <div class="card-text mt-3">
                    <p>{{ __('FsLang::tips.upgrade_fresns_tip') }} <a href="{{ $newVersion['changeIntro'] }}" target="_blank">v{{ $newVersion['version'] ?? ''}}</a></p>
                    <p class="text-danger">{{ __('FsLang::tips.upgrade_fresns_warning') }}</p>
                    @if(! $newVersion['upgradeAuto'])
                        <p>
                            {{ __('FsLang::tips.manual_upgrade_tip') }}
                            <a href="{{ $newVersion['upgradeIntro'] }}" target="_blank">{{ __('FsLang::tips.manual_upgrade_version_guide') }}</a>
                        </p>
                    @endif
                </div>

                @if ($autoUpgradeStepInt || $manualUpgradeStepInt)
                    @if ($autoUpgradeStepInt)
                        <button type="button" class="btn btn-info" id="autoUpgradeButton" data-action="{{ route('panel.upgrade.info') }}" data-upgrading="{{ $autoUpgradeStepInt }}">
                            {{ __('FsLang::tips.upgrade_in_progress') }}
                        </button>
                    @else
                        <button type="button" class="btn btn-info" id="manualUpgradeButton" data-action="{{ route('panel.upgrade.info') }}" data-upgrading="{{ $manualUpgradeStepInt }}">
                            {{ __('FsLang::tips.upgrade_in_progress') }}
                        </button>
                    @endif
                @else
                    @if($newVersion['upgradeAuto'])
                        <button type="button" class="btn btn-primary me-3" id="autoUpgradeButton" data-action="{{ route('panel.upgrade.info') }}">
                            {{ __('FsLang::panel.button_automatic_upgrade') }}
                        </button>
                    @endif
                    <button type="button" class="btn btn-outline-primary" id="manualUpgradeButton" data-action="{{ route('panel.upgrade.info') }}">
                        {{ __('FsLang::panel.button_manual_upgrade') }}
                    </button>
                    <a class="link-success ms-2" href="{{ $manualUpgradeGuide }}" target="_blank" id="manualUpgradeGuide">{{ __('FsLang::tips.manual_upgrade_guide') }}</a>
                @endif
            @else
                <div class="p-5 text-center">
                    <i class="bi bi-view-list"></i> {{ __('FsLang::tips.upgrade_none') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Upgrade Alert -->
    @if ($pluginsData->isNotEmpty() || $appsData->isNotEmpty() || $enginesData->isNotEmpty())
        <div class="alert alert-danger" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i> {{ __('FsLang::panel.tip_plugin_install_or_upgrade') }}
        </div>
    @endif

    <!--Extensions-->
    <div class="row">
        <!--Plugins-->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">{{ __('FsLang::panel.sidebar_plugins') }}</div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @if($pluginsData->isNotEmpty())
                            @foreach ($pluginsData as $plugin)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <img src="/assets/plugins/{{ $plugin->fskey }}/fresns.png" class="me-2" width="22" height="22">
                                        <a href="{{ $marketplaceUrl.'/detail/'.$plugin->fskey }}" target="_blank" class="link-dark fresns-link">{{ $plugin->name }}</a>
                                        <span class="badge bg-secondary">{{ $plugin->version }}</span> to <span class="badge bg-primary">{{ $plugin->upgrade_version }}</span>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-warning btn-sm upgrade-extensions"
                                            data-bs-toggle="modal"
                                            data-bs-target="#upgradeExtensions"
                                            data-fskey="{{ $plugin->fskey }}"
                                            data-type="{{ $plugin->type }}"
                                            data-name="{{ $plugin->name }}"
                                            data-version="{{ $plugin->version }}"
                                            data-new-version="{{ $plugin->upgrade_version }}">
                                            {{ __('FsLang::panel.button_upgrade') }}
                                        </button>
                                    </div>
                                </li>
                            @endforeach
                        @else
                            <div class="p-5 text-center">
                                <i class="bi bi-view-list"></i> {{ __('FsLang::tips.upgrade_none') }}
                            </div>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <!--Panels-->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">{{ __('FsLang::panel.sidebar_panels') }}</div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @if($appsData->isNotEmpty())
                            @foreach ($appsData as $app)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <img src="/assets/plugins/{{ $app->fskey }}/fresns.png" class="me-2" width="22" height="22">
                                        <a href="{{ $marketplaceUrl.'/detail/'.$app->fskey }}" target="_blank" class="link-dark fresns-link">{{ $app->name }}</a>
                                        <span class="badge bg-secondary">{{ $app->version }}</span> to <span class="badge bg-primary">{{ $app->upgrade_version }}</span>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-warning btn-sm upgrade-extensions"
                                            data-bs-toggle="modal"
                                            data-bs-target="#upgradeExtensions"
                                            data-fskey="{{ $app->fskey }}"
                                            data-type="{{ $app->type }}"
                                            data-name="{{ $app->name }}"
                                            data-version="{{ $app->version }}"
                                            data-new-version="{{ $app->upgrade_version }}">
                                            {{ __('FsLang::panel.button_upgrade') }}
                                        </button>
                                    </div>
                                </li>
                            @endforeach
                        @else
                            <div class="p-5 text-center">
                                <i class="bi bi-view-list"></i> {{ __('FsLang::tips.upgrade_none') }}
                            </div>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <!--Engines-->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">{{ __('FsLang::panel.sidebar_engines') }}</div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @if($enginesData->isNotEmpty())
                            @foreach ($enginesData as $engine)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <img src="/assets/plugins/{{ $engine->fskey }}/fresns.png" class="me-2" width="22" height="22">
                                        <a href="{{ $marketplaceUrl.'/detail/'.$engine->fskey }}" target="_blank" class="link-dark fresns-link">{{ $engine->name }}</a>
                                        <span class="badge bg-secondary">{{ $engine->version }}</span> to <span class="badge bg-primary">{{ $engine->upgrade_version }}</span>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-warning btn-sm upgrade-extensions"
                                            data-bs-toggle="modal"
                                            data-bs-target="#upgradeExtensions"
                                            data-fskey="{{ $engine->fskey }}"
                                            data-type="{{ $engine->type }}"
                                            data-name="{{ $engine->name }}"
                                            data-version="{{ $engine->version }}"
                                            data-new-version="{{ $engine->upgrade_version }}">
                                            {{ __('FsLang::panel.button_upgrade') }}
                                        </button>
                                    </div>
                                </li>
                            @endforeach
                        @else
                            <div class="p-5 text-center">
                                <i class="bi bi-view-list"></i> {{ __('FsLang::tips.upgrade_none') }}
                            </div>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <!--Themes-->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">{{ __('FsLang::panel.sidebar_themes') }}</div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @if($themesData->isNotEmpty())
                            @foreach ($themesData as $theme)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <img src="/assets/themes/{{ $theme->fskey }}/fresns.png" class="me-2" width="22" height="22">
                                        <a href="{{ $marketplaceUrl.'/detail/'.$theme->fskey }}" target="_blank" class="link-dark fresns-link">{{ $theme->name }}</a>
                                        <span class="badge bg-secondary">{{ $theme->version }}</span> to <span class="badge bg-primary">{{ $theme->upgrade_version }}</span>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-warning btn-sm upgrade-extensions"
                                            data-bs-toggle="modal"
                                            data-bs-target="#upgradeExtensions"
                                            data-fskey="{{ $theme->fskey }}"
                                            data-type="{{ $theme->type }}"
                                            data-name="{{ $theme->name }}"
                                            data-version="{{ $theme->version }}"
                                            data-new-version="{{ $theme->upgrade_version }}">
                                            {{ __('FsLang::panel.button_upgrade') }}
                                        </button>
                                    </div>
                                </li>
                            @endforeach
                        @else
                            <div class="p-5 text-center">
                                <i class="bi bi-view-list"></i> {{ __('FsLang::tips.upgrade_none') }}
                            </div>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- JS --}}
    <script src="/static/js/ansi_up.js"></script>

    <!-- Fresns Upgrade Modal: auto upgrade confirm -->
    <div class="modal fade" id="autoUpgradeModal" tabindex="-1" aria-labelledby="upgrade" aria-hidden="true">
        <div class="modal-dialog">
            <form method="post" action="{{ route('panel.upgrade.auto') }}" id="autoUpgradeForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('FsLang::panel.button_upgrade') }} Fresns</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>{{ __('FsLang::tips.upgrade_fresns_tip') }} <a href="{{ $newVersion['changeIntro'] }}" target="_blank">v{{ $newVersion['version'] ?? ''}}</a></p>
                        <p class="text-danger">{{ __('FsLang::tips.upgrade_fresns_warning') }}</p>
                        <p>{{ __('FsLang::tips.upgrade_confirm_tip') }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">{{ __('FsLang::panel.button_confirm_upgrade') }}</button>
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('FsLang::panel.button_cancel') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Fresns Upgrade Modal: auto upgrade step -->
    <div class="modal fade" id="autoUpgradeStepModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="upgrade" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-laptop"></i> {{ __('FsLang::panel.fresns_core') }}
                        <span class="badge bg-secondary">{{ $currentVersion['version'] ?? '' }}</span> to <span class="badge bg-primary">{{ $newVersion['version'] ?? '' }}</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="reloadPage()"></button>
                </div>
                <div class="modal-body ps-5">
                    @foreach($autoUpgradeSteps as $step => $description)
                        <p id="auto-upgrade-{{$step}}">
                            @if ($autoUpgradeStepInt < $step)
                                <i class="bi bi-hourglass text-secondary me-2"></i>
                            @elseif($autoUpgradeStepInt == $step)
                                <i class="spinner-border spinner-border-sm me-2" role="status"></i>
                            @elseif ($autoUpgradeStepInt == 0)
                                <i class="bi bi-x-lg text-danger me-2"></i>
                            @else
                                <i class="bi bi-check-lg text-success me-2"></i>
                            @endif
                            {{$description}}
                        </p>
                    @endforeach

                    {{-- autoUpgradeTip --}}
                    <div class="alert alert-danger d-none" role="alert" id="autoUpgradeTip"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fresns Upgrade Modal: manual upgrade confirm -->
    <div class="modal fade" id="manualUpgradeModal" tabindex="-1" aria-labelledby="manualUpgradeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="post" action="{{ route('panel.upgrade.manual') }}" id="manualUpgradeForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="manualUpgradeModalLabel">{{ __('FsLang::panel.button_manual_upgrade') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>
                            {{ __('FsLang::tips.upgrade_fresns_tip') }} <a href="{{ $newVersion['changeIntro'] }}" target="_blank">v{{ $appVersion ?? ''}}</a>
                            @if($appVersion != $newVersion['version'])
                                <span class="spinner-grow text-warning spinner-grow-sm" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </span>
                                <span class="badge bg-warning text-dark">{{ __('FsLang::tips.manual_upgrade_file_error') }}</span>
                            @endif
                        </p>
                        <p class="text-danger">{{ __('FsLang::tips.upgrade_fresns_warning') }}</p>
                        <p>{{ __('FsLang::tips.manual_upgrade_confirm_tip') }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger" @if($appVersion != $newVersion['version']) disabled @endif>{{ __('FsLang::panel.button_confirm_upgrade') }}</button>
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('FsLang::panel.button_cancel') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Fresns Upgrade Modal: manual upgrade step -->
    <div class="modal fade" id="manualUpgradeStepModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="upgrade" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-laptop"></i> {{ __('FsLang::panel.fresns_core') }}
                        <span class="badge bg-secondary">{{ $currentVersion['version'] ?? '' }}</span> to <span class="badge bg-primary">{{ $newVersion['version'] ?? '' }}</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="reloadPage()"></button>
                </div>
                <div class="modal-body ps-5">
                    @foreach($manualUpgradeSteps as $step => $description)
                        <p id="manual-upgrade-{{$step}}">
                            @if ($manualUpgradeStepInt < $step)
                                <i class="bi bi-hourglass text-secondary me-2"></i>
                            @elseif($manualUpgradeStepInt == $step)
                                <i class="spinner-border spinner-border-sm me-2" role="status"></i>
                            @elseif ($manualUpgradeStepInt == 0)
                                <i class="bi bi-x-lg text-danger me-2"></i>
                            @else
                                <i class="bi bi-check-lg text-success me-2"></i>
                            @endif
                            {{$description}}
                        </p>
                    @endforeach

                    {{-- manualUpgradeTip --}}
                    <div class="alert alert-danger d-none" role="alert" id="manualUpgradeTip"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Modal: plugin upgrade code -->
    <div class="modal fade" id="resetUpgradeCodeModal" tabindex="-1" aria-labelledby="upgradeCodeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="upgradeCodeModalLabel">{{ __('FsLang::panel.button_reset'). ' Code' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('panel.plugin.update.code') }}" method="post">
                    @csrf
                    @method('patch')
                    <div class="modal-body">
                        <div class="input-group mb-3">
                            <span class="input-group-text">{{ __('FsLang::panel.table_plugin') }}</span>
                            <span class="input-group-text">Key</span>
                            <input type="text" class="form-control" name="pluginFskey" maxlength="64" required>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text">{{ __('FsLang::panel.button_upgrade') }}</span>
                            <span class="input-group-text">Code</span>
                            <input type="text" class="form-control" name="upgradeCode" maxlength="32" minlength="32" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{ __('FsLang::panel.button_confirm') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Upgrade Extensions Modal: confirm upgrade -->
    <div class="modal fade" id="upgradeExtensions" tabindex="-1" aria-labelledby="install" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-journal-code"></i>
                        <span class="extension-name"></span>
                        <span class="badge bg-secondary extension-version"></span> to <span class="badge bg-primary extension-new-version"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-danger">{{ __('FsLang::tips.upgrade_fresns_warning') }}</p>
                    <p>{{ __('FsLang::tips.upgrade_confirm_tip') }}</p>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('panel.plugin.upgrade') }}" method="post">
                        @csrf
                        @method('put')
                        <input type="hidden" name="fskey">
                        <input type="hidden" name="type">
                        <button type="submit" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#upgradeStepModal" id="extensionsUpgradeSubmit">{{ __('FsLang::panel.button_confirm_upgrade') }}</button>
                    </form>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('FsLang::panel.button_cancel') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Upgrade Extensions Modal: artisan output info -->
    <div class="modal fade" id="upgradeStepModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="upgradeStepModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-journal-code"></i>
                        <span class="extension-name"></span>
                        <span class="badge bg-secondary extension-version"></span> to <span class="badge bg-primary extension-new-version"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <pre class="form-control" id="upgrade_artisan_output">{{ __('FsLang::tips.upgrade_in_progress') }}</pre>

                    <!--progress bar-->
                    <div class="mt-2">
                        <div class="ajax-progress progress d-none" id="upgrade-extension-progress"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" onclick="reloadPage()">{{ __('FsLang::panel.button_close') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection
