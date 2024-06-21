<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="{{ $route ?? '#' }}" data-bs-toggle="{{ $route ?? 'dropdown' }}">
        <div class="parent-icon"><i class="bx bx-{{ $icon }}"></i>
        </div>
        <div class="menu-title d-flex align-items-center">{{ $name }}</div>
        @isset($options)
            <div class="ms-auto dropy-icon"><i class="bx bx-chevron-down"></i></div>
        @endisset
    </a>
    @isset($options)
        <ul class="dropdown-menu @isset($style) {{ $style }} @endisset">
            @foreach ($options as $option)
                <li><a class="dropdown-item" href="{{ $option['route'] }}"><i class="bx bx-{{ $option['icon'] }}"></i>{{ @$option['name'] }}</a></li>
            @endforeach
        </ul>
    @endisset
</li>