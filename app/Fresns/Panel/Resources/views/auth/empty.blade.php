@extends('FsView::commons.layout')

@section('body')
    <header class="form-signin text-center">
        <img class="mt-3 mb-2" src="{{ @asset('/static/images/icon.png') }}" alt="Fresns" width="72" height="72">
        <h2 class="mb-5">HaoLiHen</h2>
     
    </header>

    <main class="container">
        <div class="card mx-auto my-5" style="max-width:800px;">
            <div class="card-body p-5">
                <h3 class="card-title">{{ __('FsLang::tips.auth_empty_title') }}</h3>
                <p class="mt-3">{{ __('FsLang::tips.auth_empty_description') }}</p>
                <p class="mt-4 mb-0"><a class="btn btn-outline-primary btn-sm" href="{{ $siteUrl }}" role="button"><i class="bi bi-house-door"></i> {{ __('FsLang::panel.site_home') }}</a></p>
            </div>
        </div>

        <div class="text-center">
            <p class="my-5 text-muted">&copy; <span class="copyright-year"></span> HaoLiHen</p>
        </div>
    </main>
@endsection

@push('script')
    <script>
        $('.change-lang').change(function() {
            var lang = $(this).val();
            let url = new URL(window.location.href);
            url.searchParams.set('lang', lang);
            window.location.href = url.href;
        });
    </script>
@endpush
