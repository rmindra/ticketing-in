@php
    $fallback = session('_previous.url') ?? request()->server('HTTP_REFERER') ?? url('/');
@endphp

<div class="mb-3">
    <button type="button" class="btn btn-light btn-sm" onclick="history.back();">&larr; Back</button>
    <noscript>
        <a class="btn btn-light btn-sm" href="{{ $fallback }}">&larr; Back</a>
    </noscript>
</div>
