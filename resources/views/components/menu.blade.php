<li class="nav-item dropdown {{ @$active }}">
    <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="{{ @$route ?? '#' }}" data-bs-toggle="{{ $route ?? 'dropdown' }}">
        <div class="parent-icon"><img src="{{ asset('assets/icons') }}/{{ $icon }}" alt=""></div>
        <div class="menu-title d-flex align-items-center">{{ $name }}</div>
        @isset($options)
            <div class="ms-auto dropy-icon"><i class="bx bx-chevron-down"></i></div>
        @endisset
    </a>
    @isset($options)
        <ul class="dropdown-menu" style="width: @isset($width) {{ $width }} @endisset">
            @foreach ($options as $option)
                <li>
                    <a class="dropdown-item" href="{{ $option['route'] }}">
                        <img class="p-1" src="{{ asset('assets/icons') }}/{{ $option['icon'] }}"/> {{ @$option['name'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endisset
</li>