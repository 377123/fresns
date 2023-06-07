@if (session('success'))
    <div aria-live="polite" aria-atomic="true" class="position-fixed   start-50 translate-middle" style="z-index:99;top: 35%!important;">
        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">提醒</strong>
                <small>200</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                {{ session('success') }}
            </div>
        </div>
    </div>
@elseif (session('failure'))
    <div aria-live="polite" aria-atomic="true" class="position-fixed   start-50 translate-middle" style="z-index:99; top: 35%!important;">
        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header text-white bg-warning">
                <strong class="me-auto">失败提醒</strong>
                <small>
                    @if (session('code'))
                        {{ session('code') }}
                    @endif
                </small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                {{ session('failure') }}
            </div>
        </div>
    </div>
@elseif($errors->any())
    <div aria-live="polite" aria-atomic="true" class="position-fixed   start-50 translate-middle" style="z-index:99; top: 35%!important;">
        @foreach ($errors->all() as $error)
            <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header text-white bg-danger">
                    <strong class="me-auto">错误提醒</strong>
                    <small>400</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    {!! $error !!}
                </div>
            </div>
        @endforeach
    </div>
@endif
