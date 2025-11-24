@php
    $menu = config('sidebar.menu', []);
    $canCheck = function ($can) {
        if (!$can) return true;
        if (str_starts_with($can, 'role:')) {
            $role = substr($can, 5);
            return auth()->check() && optional(auth()->user()->role)->role === $role;
        }
        return auth()->check() ? auth()->user()->can($can) : false;
    };
@endphp

<nav class="pt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" role="menu">
        @foreach($menu as $item)
            @if(isset($item['header']))
                <li class="nav-header px-3 mt-2 text-muted">{{ $item['header'] }}</li>
                @continue
            @endif

            @php $show = $canCheck($item['can'] ?? null); @endphp
            @if(!$show) @continue @endif

            <li class="nav-item">
                <a class="nav-link" href="{{ $item['route'] ? route($item['route']) : '#' }}">
                    @if(!empty($item['icon']))
                        <i class="nav-icon {{ $item['icon'] }}"></i>
                    @endif
                    <p>{{ $item['text'] }}</p>
                </a>
            </li>
        @endforeach
    </ul>
</nav>
