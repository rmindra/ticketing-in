<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">@yield('page_title', 'Dashboard')</h4>
    <div>
        <span class="me-3">Hi, {{ auth()->user()->name }}</span>
        <a class="btn btn-outline-secondary btn-sm" href="{{ route('profile.show') }}">Profile</a>
    </div>
</div>
