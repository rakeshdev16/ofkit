<div class="header-wrapper">
    @php
        $user = Auth::user();
        $currentRoute = Route::currentRouteName();
    @endphp
    <header>
        <div class="topbar d-flex align-items-center">
            <nav class="navbar navbar-expand gap-3">
                <div class="topbar-logo-header d-none d-lg-flex">
                    <div class="">
                        <img src="{{ asset('assets/images/3.png') }}" class="" width="100px" alt="logo icon">
                    </div>
                </div>
                <div class="mobile-toggle-menu d-block d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar">
                    <i class='bx bx-menu'></i>
                </div>
                <div class="top-menu ms-auto">
                    <ul class="navbar-nav align-items-center gap-1">
                        <li class="nav-item dropdown dropdown-large">
                            <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#"
                                data-bs-toggle="dropdown"><span class="alert-count">7</span>
                                <i class='bx bx-bell'></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="#">
                                    <div class="msg-header">
                                        <p class="msg-header-title">Notifications</p>
                                        <p class="msg-header-badge">8 New</p>
                                    </div>
                                </a>
                                <div class="header-notifications-list ps">
                                    <a class="dropdown-item" href="#">
                                        <div class="d-flex align-items-center">
                                            <div class="user-online">
                                                <img src="assets/images/avatars/avatar-4.png" class="msg-avatar"
                                                    alt="user avatar">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="msg-name">Katherine Pechon <span class="msg-time float-end">15 min ago</span></h6>
                                                <p class="msg-info">Making this the first true generator</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <div class="d-flex align-items-center">
                                            <div class="notify bg-light-success text-success"><i class='bx bx-check-square'></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="msg-name">Your item is shipped <span class="msg-time float-end">5 hrs ago</span></h6>
                                                <p class="msg-info">Successfully shipped your item</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <div class="d-flex align-items-center">
                                            <div class="notify bg-light-primary">
                                                <img src="assets/images/app/github.png" width="25" alt="user avatar">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="msg-name">New 24 authors<span class="msg-time float-end">1 day ago</span></h6>
                                                <p class="msg-info">24 new authors joined last week</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <div class="d-flex align-items-center">
                                            <div class="user-online">
                                                <img src="assets/images/avatars/avatar-8.png" class="msg-avatar" alt="user avatar">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="msg-name">Peter Costanzo <span class="msg-time float-end">6 hrs ago</span></h6>
                                                <p class="msg-info">It was popularised in the 1960s</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <a href="#">
                                    <div class="text-center msg-footer">
                                        <button class="btn button w-100">View All Notifications</button>
                                    </div>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="user-box dropdown px-3">
                    <a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret"
                        href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ $user->photo }}"
                            class="user-img" alt="user avatar">
                        <div class="user-info">
                            <p class="user-name mb-0">{{ $user->name }}</p>
                            <p class="designattion mb-0">{{ $user->getRoleNames()->first() }}</p>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item d-flex align-items-center" href="{{ route('profile.index') }}"><i class="bx bx-user fs-5"></i><span>Profile</span></a></li>
                        {{-- <li><a class="dropdown-item d-flex align-items-center" href="#"><i class="bx bx-cog fs-5"></i><span>Settings</span></a></li>
                        <li><a class="dropdown-item d-flex align-items-center" href="#"><i class="bx bx-home-circle fs-5"></i><span>Dashboard</span></a></li>
                        <li><a class="dropdown-item d-flex align-items-center" href="#"><i class="bx bx-dollar-circle fs-5"></i><span>Earnings</span></a></li>
                        <li><a class="dropdown-item d-flex align-items-center" href="#"><i class="bx bx-download fs-5"></i><span>Downloads</span></a></li> --}}
                        <li>
                            <div class="dropdown-divider mb-0"></div>
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item d-flex align-items-center">
                                    <i class="bx bx-log-out-circle"></i><span>Logout</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>
    <div class="primary-menu">
        <nav class="navbar navbar-expand-lg align-items-center">
            <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="">
                            <img src="{{ asset('assets/images/3.png') }}" width="100px" class="" alt="logo icon">
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav align-items-center flex-grow-1">
                        @if ($user->hasAnyPermission(['staff-table.index']))
                            @include('components.menu', [
                                'name' => __('menu.tables'),
                                'icon' => 'fi_3602109.png',
                                'active' => in_array($currentRoute, ['framework-table.index', 'staff-table.index']) ? 'active-menu' : '',
                                'options' => [
                                    ['icon' => 'fi_4549612.png', 'name' => 'Framework', 'route' => route('framework-table.index', ['type' => 'kindergarten-type'])],
                                    ['icon' => 'fi_2887367.png', 'name' => 'Staff', 'route' => route('staff-table.index', ['type' => 'profession'])],
                                    ['icon' => 'fi_9184264.png', 'name' => 'Children', 'route' => route('children-table.index', ['type' => 'parents-status'])],
                                    ['icon' => 'fi_3602109.png', 'name' => 'Intervention', 'route' => route('intervention.index', ['type' => 'intervention-type'])]
                                ],
                            ])
                        @endif
                        @if ($user->hasAnyPermission(['kindergarten.index', 'kindergarten.store']))
                            @include('components.menu', [
                                'name' => __('menu.kindergarten'),
                                'icon' => 'fi_4794795.png',
                                'active' => in_array($currentRoute, ['kindergarten.index', 'kindergarten.create']) ? 'active-menu' : '',
                                'route' => route('kindergarten.index'),
                                // 'options' => [
                                //     ['icon' => 'fi_4794795.png', 'name' => __('menu.allKindergarten'), 'route' => route('kindergarten.index')],
                                //     ['icon' => 'fi_4794795.png', 'name' => __('menu.addKindergarten'), 'route' => route('kindergarten.create')]
                                // ],
                            ])
                        @endif
                        @if ($user->hasAnyPermission(['cluster.index', 'cluster.store']))
                            @include('components.menu', [
                                'name' => __('menu.clusters'),
                                'width' => '180px',
                                'icon' => 'fi_4549612.png',
                                'active' => in_array($currentRoute, ['cluster.index', 'cluster.create']) ? 'active-menu' : '',
                                'route' => route('cluster.index'),
                                // 'options' => [
                                //     ['icon' => 'fi_4549612.png', 'name' => __('menu.clusterList'), 'route' => route('cluster.index')],
                                //     ['icon' => 'fi_4549612.png', 'name' => __('menu.addCluster'), 'route' => route('cluster.create')]
                                // ],
                            ])
                        @endif
                        @if ($user->hasAnyPermission(['therapy-schedule.index']))
                            @include('components.menu', [
                                'name' => __('menu.therapySchedule'),
                                'width' => '250px',
                                'icon' => 'fi_16650601.png',
                                'active' => in_array($currentRoute, ['']) ? 'active-menu' : '',
                                'route' => '',
                                // 'options' => [
                                //     ['icon' => 'fi_2887367.png', 'name' => __('menu.allTherapySchedule'), 'route' => ''],
                                //     ['icon' => 'fi_2887367.png', 'name' => __('menu.addTherapySchedule'), 'route' => '']
                                // ],
                            ])
                        @endif
                        @if ($user->hasAnyPermission(['staff.index', 'staff.store']))
                            @include('components.menu', [
                                'name' => __('menu.staff'),
                                'width' => '250px',
                                'icon' => 'fi_2887367.png',
                                'active' => in_array($currentRoute, ['staff.index', 'staff.create']) ? 'active-menu' : '',
                                'route' => route('staff.index'),
                                // 'options' => [
                                //     ['icon' => 'fi_1478254.png', 'name' => __('menu.staffList'), 'route' => route('staff.index')],
                                //     ['icon' => 'fi_6212658.png', 'name' => __('menu.addStaff'), 'route' => route('staff.create')],
                                //     // ['icon' => 'fi_9959428.png', 'name' => __('menu.childPsychiatrist'), 'route' => route('staff.index')]
                                // ],
                            ])
                        @endif
                        @if ($user->hasAnyPermission(['children.index', 'children.store']))
                            @include('components.menu', [
                                'name' => __('menu.childrens'),
                                'width' => '180px',
                                'icon' => 'fi_9184264.png',
                                'active' => in_array($currentRoute, ['children.index', 'children.create']) ? 'active-menu' : '',
                                'route' => route('children.index'),
                                // 'options' => [
                                //     ['icon' => 'fi_2887367.png', 'name' => __('menu.allChildrens'), 'route' => route('children.index')],
                                //     ['icon' => 'fi_2887367.png', 'name' => __('menu.addChildren'), 'route' => route('children.create')],
                                // ],
                            ])
                        @endif
                        @if ($user->hasAnyPermission(['dashboard']))
                            @include('components.menu', [
                                'name' => __('menu.documentation'),
                                'route' => route('dashboard'),
                                'icon' => 'fi_2991112.png',
                                'active' => $currentRoute == 'dashboard' ? 'active-menu' : '',
                            ])
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</div>
