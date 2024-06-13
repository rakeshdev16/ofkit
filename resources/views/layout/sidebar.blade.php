<div class="vertical-menu">
    @php
        $route = Route::currentRouteName();
        $user = Auth::user();
    @endphp
    <div class="navbar-brand-box">
        <a href="{{ route('dashboard') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="assets/images/logo-sm.svg" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="assets/images/logo-sm.svg" alt="" height="22"> <span
                    class="logo-txt">Symox</span>
            </span>
        </a>
        <a href="{{ route('dashboard') }}" class="logo logo-light">
            <span class="logo-lg">
                <img src="assets/images/4.png" alt="" height="70" width="95%">
            </span>
            <span class="logo-sm">
                <img src="assets/images/logo-sm.svg" alt="" height="22">
            </span>
        </a>
    </div>
    <button type="button" class="btn btn-sm px-3 font-size-16 header-item vertical-menu-btn">
        <i class="fa fa-fw fa-bars"></i>
    </button>
    <div data-simplebar class="sidebar-menu-scroll">
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" data-key="t-menu">Menu</li>
                @if ($user->hasPermissionTo('dashboard'))
                    <li>
                        <a href="{{ route('dashboard') }}">
                            <i class="bx bx-tachometer icon nav-icon"></i>
                            <span class="menu-item" data-key="t-dashboards">Dashboard</span>
                        </a>
                    </li>
                @endif
                <li class="menu-title" data-key="t-applications">Applications</li>
                <li>
                    <a href="#">
                        <i class="bx bxs-file-doc icon nav-icon"></i>
                        <span class="menu-item" data-key="t-calendar">Documentation</span>
                    </a>
                </li>
                @if ($user->hasPermissionTo('children.index'))
                    <li>
                        <a href="{{ route('children.index') }}">
                            <i class="fa fa-child icon nav-icon"></i>
                            <span class="menu-item" data-key="t-hot">Children</span>
                        </a>
                    </li>
                @endif
                @if ($user->hasPermissionTo('staff.index'))
                    <li>
                        <a href="{{ route('staff.index') }}">
                            <i class="fa fa-users icon nav-icon"></i>
                            <span class="menu-item" data-key="t-hot">Staff</span>
                        </a>
                    </li>
                @endif
                <li>
                    <a href="#">
                        <i class="bx bx-chat icon nav-icon"></i>
                        <span class="menu-item" data-key="t-hot">Therapy Schedule</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="bx bx-chat icon nav-icon"></i>
                        <span class="menu-item" data-key="t-hot">Clusters</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="bx bx-chat icon nav-icon"></i>
                        <span class="menu-item" data-key="t-hot">Kindergarten</span>
                    </a>
                </li>
                <li class="">
                    <a href="javascript: void(0);" class="has-arrow mm-collapsed" aria-expanded="false">
                        <i class="bx bx-envelope icon nav-icon"></i>
                        <span class="menu-item" data-key="t-hot">Tables</span>
                    </a>
                    <ul class="sub-menu mm-collapse" aria-expanded="false" style="">
                        <li><a href="#" data-key="t-inbox">Intervention</a></li>
                        <li><a href="#" data-key="t-hot">Children</a></li>
                        <li><a href="#" data-key="t-hot">Staff</a></li>
                        <li><a href="#" data-key="t-hot">Framework</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- Left Sidebar End -->
<header id="page-topbar" class="ishorizontal-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{ route('dashboard') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="assets/images/logo-sm.svg" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="assets/images/logo-sm.svg" alt="" height="22"> <span
                            class="logo-txt">Symox</span>
                    </span>
                </a>

                <a href="{{ route('dashboard') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="assets/images/logo-sm.svg" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="assets/images/logo-sm.svg" alt="" height="22"> <span
                            class="logo-txt">Symox</span>
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 d-lg-none header-item"
                data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                <i class="fa fa-fw fa-bars"></i>
            </button>

            <div class="topnav">
                <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

                    <div class="collapse navbar-collapse" id="topnav-menu-content">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link dropdown-toggle arrow-none" href="{{ route('dashboard') }}"
                                    id="topnav-dashboard" role="button" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class='bx bx-tachometer'></i>
                                    <span data-key="t-dashboards">Dashboard</span>
                                </a>
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle arrow-none" href="#"
                                    id="topnav-pages" role="button">
                                    <i class='bx bx-grid-alt'></i>
                                    <span data-key="t-apps">Apps</span>
                                    <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-pages">

                                    <a href="apps-calendar.html" class="dropdown-item"
                                        data-key="t-calendar">Calendar</a>
                                    <a href="apps-chat.html" class="dropdown-item" data-key="t-chat">Chat</a>
                                    <div class="dropdown">
                                        <a class="dropdown-item dropdown-toggle arrow-none" href="#"
                                            id="topnav-email" role="button">
                                            <span data-key="t-email">Email</span>
                                            <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="topnav-email">
                                            <a href="email-inbox.html" class="dropdown-item"
                                                data-key="t-inbox">Inbox</a>
                                            <a href="email-read.html" class="dropdown-item"
                                                data-key="t-read-email">Read Email</a>
                                        </div>
                                    </div>

                                    <div class="dropdown">
                                        <a class="dropdown-item dropdown-toggle arrow-none" href="#"
                                            id="topnav-ecommerce" role="button">
                                            Ecommerce <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="topnav-ecommerce">
                                            <a href="ecommerce-products.html" class="dropdown-item"
                                                data-key="t-products">Products</a>
                                            <a href="ecommerce-product-detail.html" class="dropdown-item"
                                                data-key="t-product-detail">Product Detail</a>
                                            <a href="ecommerce-orders.html" class="dropdown-item"
                                                data-key="t-orders">Orders</a>
                                            <a href="ecommerce-customers.html" class="dropdown-item"
                                                data-key="t-customers">Customers</a>
                                            <a href="ecommerce-cart.html" class="dropdown-item"
                                                data-key="t-cart">Cart</a>
                                            <a href="ecommerce-checkout.html" class="dropdown-item"
                                                data-key="t-checkout">Checkout</a>
                                            <a href="ecommerce-shops.html" class="dropdown-item"
                                                data-key="t-shops">Shops</a>
                                            <a href="ecommerce-add-product.html" class="dropdown-item"
                                                data-key="t-add-product">Add Product</a>
                                        </div>
                                    </div>

                                    <div class="dropdown">
                                        <a class="dropdown-item dropdown-toggle arrow-none" href="#"
                                            id="topnav-invoice" role="button"><span
                                                data-key="t-invoices">Invoices</span>
                                            <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="topnav-invoice">
                                            <a href="invoices-list.html" class="dropdown-item"
                                                data-key="t-invoice-list">Invoice List</a>
                                            <a href="invoices-detail.html" class="dropdown-item"
                                                data-key="t-invoice-detail">Invoice Detail</a>
                                        </div>
                                    </div>

                                    <div class="dropdown">
                                        <a class="dropdown-item dropdown-toggle arrow-none" href="#"
                                            id="topnav-contact" role="button">
                                            <span data-key="t-contacts">Contacts</span>
                                            <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="topnav-contact">
                                            <a href="contacts-grid.html" class="dropdown-item"
                                                data-key="t-user-grid">User Grid</a>
                                            <a href="contacts-list.html" class="dropdown-item"
                                                data-key="t-user-list">User List</a>
                                            <a href="contacts-profile.html" class="dropdown-item"
                                                data-key="t-user-settings">Profile</a>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle arrow-none" href="#"
                                    id="topnav-uielement" role="button" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class='bx bxl-bootstrap'></i>
                                    <span data-key="t-bootstrap">Bootstrap</span>
                                    <div class="arrow-down"></div>
                                </a>

                                <div class="dropdown-menu mega-dropdown-menu px-2 dropdown-mega-menu-xl"
                                    aria-labelledby="topnav-uielement">
                                    <div class="ps-2 p-lg-0">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div>
                                                    <div class="menu-title">Components</div>
                                                    <div class="row g-0">
                                                        <div class="col-lg-4">
                                                            <div>
                                                                <a href="ui-alerts.html" class="dropdown-item"
                                                                    data-key="t-alerts">Alerts</a>
                                                                <a href="ui-buttons.html"
                                                                    class="dropdown-item"
                                                                    data-key="t-buttons">Buttons</a>
                                                                <a href="ui-cards.html" class="dropdown-item"
                                                                    data-key="t-cards">Cards</a>
                                                                <a href="ui-carousel.html"
                                                                    class="dropdown-item"
                                                                    data-key="t-carousel">Carousel</a>
                                                                <a href="ui-dropdowns.html"
                                                                    class="dropdown-item"
                                                                    data-key="t-dropdowns">Dropdowns</a>
                                                                <a href="ui-grid.html" class="dropdown-item"
                                                                    data-key="t-grid">Grid</a>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div>
                                                                <a href="ui-images.html" class="dropdown-item"
                                                                    data-key="t-images">Images</a>
                                                                <a href="ui-modals.html" class="dropdown-item"
                                                                    data-key="t-modals">Modals</a>
                                                                <a href="ui-offcanvas.html"
                                                                    class="dropdown-item"
                                                                    data-key="t-offcanvas">Offcanvas</a>
                                                                <a href="ui-placeholders.html"
                                                                    class="dropdown-item"
                                                                    data-key="t-placeholders">Placeholders</a>
                                                                <a href="ui-progressbars.html"
                                                                    class="dropdown-item"
                                                                    data-key="t-progress-bars">Progress
                                                                    Bars</a>
                                                                <a href="ui-tabs-accordions.html"
                                                                    class="dropdown-item"
                                                                    data-key="t-tabs-accordions">Tabs &
                                                                    Accordions</a>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <a href="ui-typography.html" class="dropdown-item"
                                                                data-key="t-typography">Typography</a>
                                                            <a href="ui-video.html" class="dropdown-item"
                                                                data-key="t-video">Video</a>
                                                            <a href="ui-general.html" class="dropdown-item"
                                                                data-key="t-general">General</a>
                                                            <a href="ui-colors.html" class="dropdown-item"
                                                                data-key="t-colors">Colors</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle arrow-none" href="#"
                                    id="topnav-components" role="button">
                                    <i class='bx bx-layer'></i>
                                    <span data-key="t-components">Components</span>
                                    <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-components">
                                    <div class="dropdown">
                                        <a class="dropdown-item dropdown-toggle arrow-none" href="#"
                                            id="topnav-extended" role="button">
                                            <span data-key="t-extendeds">Extended</span>
                                            <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="topnav-form">
                                            <a href="extended-lightbox.html" class="dropdown-item"
                                                data-key="t-lightbox">Lightbox</a>
                                            <a href="extended-rangeslider.html" class="dropdown-item"
                                                data-key="t-range-slider">Range Slider</a>
                                            <a href="extended-sweet-alert.html" class="dropdown-item"
                                                data-key="t-sweet-alert">SweetAlert 2</a>
                                            <a href="extended-rating.html" class="dropdown-item"
                                                data-key="t-rating">Rating</a>
                                            <a href="extended-notifications.html" class="dropdown-item"
                                                data-key="t-notifications">Notifications</a>
                                        </div>
                                    </div>
                                    <div class="dropdown">
                                        <a class="dropdown-item dropdown-toggle arrow-none" href="#"
                                            id="topnav-form" role="button">
                                            <span data-key="t-forms">Forms</span>
                                            <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="topnav-form">
                                            <a href="form-elements.html" class="dropdown-item"
                                                data-key="t-basic-elements">Basic Elements</a>
                                            <a href="form-validation.html" class="dropdown-item"
                                                data-key="t-validation">Validation</a>
                                            <a href="form-advanced.html" class="dropdown-item"
                                                data-key="t-advanced-plugins">Advanced Plugins</a>
                                            <a href="form-editors.html" class="dropdown-item"
                                                data-key="t-editors">Editors</a>
                                            <a href="form-uploads.html" class="dropdown-item"
                                                data-key="t-file-upload">File Upload</a>
                                            <a href="form-wizard.html" class="dropdown-item"
                                                data-key="t-wizard">Wizard</a>
                                            <a href="form-mask.html" class="dropdown-item"
                                                data-key="t-mask">Mask</a>
                                        </div>
                                    </div>
                                    <div class="dropdown">
                                        <a class="dropdown-item dropdown-toggle arrow-none" href="#"
                                            id="topnav-table" role="button">
                                            <span data-key="t-tables">Tables</span>
                                            <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="topnav-table">
                                            <a href="tables-basic.html" class="dropdown-item"
                                                data-key="t-bootstrap-basic">Bootstrap Basic</a>
                                            <a href="tables-advanced.html" class="dropdown-item"
                                                data-key="t-advanced-tables">Advance Tables</a>
                                        </div>
                                    </div>
                                    <div class="dropdown">
                                        <a class="dropdown-item dropdown-toggle arrow-none" href="#"
                                            id="topnav-charts" role="button">
                                            <span data-key="t-charts">Charts</span>
                                            <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="topnav-charts">
                                            <a href="charts-apex.html" class="dropdown-item"
                                                data-key="t-apex-charts">Apex Charts</a>
                                            <a href="charts-chartjs.html" class="dropdown-item"
                                                data-key="t-chartjs-charts">Chartjs</a>
                                        </div>
                                    </div>
                                    <div class="dropdown">
                                        <a class="dropdown-item dropdown-toggle arrow-none" href="#"
                                            id="topnav-icons" role="button">
                                            <span data-key="t-icons">Icons</span>
                                            <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="topnav-icons">
                                            <a href="icons-feather.html" class="dropdown-item"
                                                data-key="t-feather">Feather</a>
                                            <a href="icons-boxicons.html" class="dropdown-item"
                                                data-key="t-boxicons">Boxicons</a>
                                            <a href="icons-materialdesign.html" class="dropdown-item"
                                                data-key="t-material-design">Material Design</a>
                                            <a href="icons-dripicons.html" class="dropdown-item"
                                                data-key="t-dripicons">Dripicons</a>
                                            <a href="icons-fontawesome.html" class="dropdown-item"
                                                data-key="t-font-awesome">Font awesome</a>
                                        </div>
                                    </div>
                                    <div class="dropdown">
                                        <a class="dropdown-item dropdown-toggle arrow-none" href="#"
                                            id="topnav-map" role="button">
                                            <span data-key="t-maps">Maps</span>
                                            <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="topnav-map">
                                            <a href="maps-google.html" class="dropdown-item"
                                                data-key="t-google">Google</a>
                                            <a href="maps-vector.html" class="dropdown-item"
                                                data-key="t-vector">Vector</a>
                                            <a href="maps-leaflet.html" class="dropdown-item"
                                                data-key="t-leaflet">Leaflet</a>
                                        </div>
                                    </div>
                                </div>

                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle arrow-none" href="#"
                                    id="topnav-more" role="button">
                                    <i class='bx bx-file'></i>
                                    <span data-key="t-pages">Pages</span>
                                    <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-more">
                                    <div class="dropdown">
                                        <a class="dropdown-item dropdown-toggle arrow-none" href="#"
                                            id="topnav-auth" role="button">
                                            <span data-key="t-authentication">Authentication</span>
                                            <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                            <a href="auth-login.html" class="dropdown-item"
                                                data-key="t-login">Login</a>
                                            <a href="auth-register.html" class="dropdown-item"
                                                data-key="t-register">Register</a>
                                            <a href="auth-recoverpw.html" class="dropdown-item"
                                                data-key="t-recover-password">Recover Password</a>
                                            <a href="auth-lock-screen.html" class="dropdown-item"
                                                data-key="t-lock-screen">Lock Screen</a>
                                            <a href="auth-logout.html" class="dropdown-item"
                                                data-key="t-logout">Log Out</a>
                                            <a href="auth-confirm-mail.html" class="dropdown-item"
                                                data-key="t-confirm-mail">Confirm Mail</a>
                                            <a href="auth-email-verification.html" class="dropdown-item"
                                                data-key="t-email-verification">Email Verification</a>
                                            <a href="auth-two-step-verification.html" class="dropdown-item"
                                                data-key="t-two-step-verification">Two Step Verification</a>
                                        </div>
                                    </div>

                                    <div class="dropdown">
                                        <a class="dropdown-item dropdown-toggle arrow-none" href="#"
                                            id="topnav-utility" role="button">
                                            <span data-key="t-utility">Utility</span>
                                            <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="topnav-utility">
                                            <a href="pages-starter.html" class="dropdown-item"
                                                data-key="t-starter-page">Starter Page</a>
                                            <a href="pages-maintenance.html" class="dropdown-item"
                                                data-key="t-maintenance">Maintenance</a>
                                            <a href="pages-comingsoon.html" class="dropdown-item"
                                                data-key="t-coming-soon">Coming Soon</a>
                                            <a href="pages-timeline.html" class="dropdown-item"
                                                data-key="t-timeline">Timeline</a>
                                            <a href="pages-faqs.html" class="dropdown-item"
                                                data-key="t-faqs">FAQs</a>
                                            <a href="pages-pricing.html" class="dropdown-item"
                                                data-key="t-pricing">Pricing</a>
                                            <a href="pages-404.html" class="dropdown-item"
                                                data-key="t-error-404">Error 404</a>
                                            <a href="pages-500.html" class="dropdown-item"
                                                data-key="t-error-500">Error 500</a>
                                        </div>
                                    </div>

                                    <a href="layouts-vertical.html" class="dropdown-item"
                                        data-key="t-vertical">Vertical</a>
                                </div>
                            </li>

                        </ul>
                    </div>
                </nav>
            </div>
        </div>

        <div class="d-flex">
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item" data-bs-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="icon-sm" data-feather="search"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0">
                    <form class="p-3">
                        <div class="search-box">
                            <div class="position-relative">
                                <input type="text" class="form-control rounded"
                                    placeholder="Search here...">
                                <i class="mdi mdi-magnify search-icon"></i>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="dropdown d-inline-block language-switch">
                <button type="button" class="btn header-item" data-bs-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <img class="header-lang-img" src="assets/images/flags/us.jpg" alt="Header Language"
                        height="16">
                </button>
                <div class="dropdown-menu dropdown-menu-end">

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item language"
                        data-lang="eng">
                        <img src="assets/images/flags/us.jpg" alt="user-image" class="me-1"
                            height="12"> <span class="align-middle">English</span>
                    </a>
                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item language"
                        data-lang="sp">
                        <img src="assets/images/flags/spain.jpg" alt="user-image" class="me-1"
                            height="12"> <span class="align-middle">Spanish</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item language"
                        data-lang="gr">
                        <img src="assets/images/flags/germany.jpg" alt="user-image" class="me-1"
                            height="12"> <span class="align-middle">German</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item language"
                        data-lang="it">
                        <img src="assets/images/flags/italy.jpg" alt="user-image" class="me-1"
                            height="12"> <span class="align-middle">Italian</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item language"
                        data-lang="ru">
                        <img src="assets/images/flags/russia.jpg" alt="user-image" class="me-1"
                            height="12"> <span class="align-middle">Russian</span>
                    </a>
                </div>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon"
                    id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <i class="icon-sm" data-feather="bell"></i>
                    <span class="noti-dot bg-danger rounded-pill">3</span>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-notifications-dropdown">
                    <div class="p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="m-0 font-size-15"> Notifications </h5>
                            </div>
                            <div class="col-auto">
                                <a href="javascript:void(0);" class="small"> Mark all as read</a>
                            </div>
                        </div>
                    </div>
                    <div data-simplebar style="max-height: 250px;">
                        <a href="" class="text-reset notification-item">
                            <div class="d-flex border-bottom align-items-start bg-light">
                                <div class="flex-shrink-0">
                                    <img src="assets/images/users/avatar-3.jpg"
                                        class="me-3 rounded-circle avatar-sm" alt="user-pic">
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Justin Verduzco</h6>
                                    <div class="text-muted">
                                        <p class="mb-1 font-size-13">Your task changed an issue from "In
                                            Progress" to <span class="badge badge-soft-success">Review</span>
                                        </p>
                                        <p class="mb-0 font-size-10 text-uppercase fw-bold"><i
                                                class="mdi mdi-clock-outline"></i> 1 hours ago</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="" class="text-reset notification-item">
                            <div class="d-flex border-bottom align-items-start">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm me-3">
                                        <span class="avatar-title bg-primary rounded-circle font-size-16">
                                            <i class="bx bx-shopping-bag"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">New order has been placed</h6>
                                    <div class="text-muted">
                                        <p class="mb-1 font-size-13">Open the order confirmation or shipment
                                            confirmation.</p>
                                        <p class="mb-0 font-size-10 text-uppercase fw-bold"><i
                                                class="mdi mdi-clock-outline"></i> 5 hours ago</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="" class="text-reset notification-item">
                            <div class="d-flex border-bottom align-items-start">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm me-3">
                                        <span
                                            class="avatar-title bg-soft-success text-success rounded-circle font-size-16">
                                            <i class="bx bx-cart"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Your item is shipped</h6>
                                    <div class="text-muted">
                                        <p class="mb-1 font-size-13">Here is somthing that you might light
                                            like to know.</p>
                                        <p class="mb-0 font-size-10 text-uppercase fw-bold"><i
                                                class="mdi mdi-clock-outline"></i> 1 day ago</p>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <a href="" class="text-reset notification-item">
                            <div class="d-flex border-bottom align-items-start">
                                <div class="flex-shrink-0">
                                    <img src="assets/images/users/avatar-4.jpg"
                                        class="me-3 rounded-circle avatar-sm" alt="user-pic">
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Salena Layfield</h6>
                                    <div class="text-muted">
                                        <p class="mb-1 font-size-13">Yay ! Everything worked!</p>
                                        <p class="mb-0 font-size-10 text-uppercase fw-bold"><i
                                                class="mdi mdi-clock-outline"></i> 3 days ago</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="p-2 border-top d-grid">
                        <a class="btn btn-sm btn-link font-size-14 btn-block text-decoration-underline fw-bold text-center"
                            href="javascript:void(0)">
                            <span>View All <i class='bx bx-right-arrow-alt'></i></span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="dropdown d-none d-sm-inline-block">
                <button type="button" class="btn header-item light-dark" id="mode-setting-btn">
                    <i data-feather="moon" class="icon-sm layout-mode-dark "></i>
                    <i data-feather="sun" class="icon-sm layout-mode-light"></i>
                </button>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item user text-start d-flex align-items-center"
                    id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <img class="rounded-circle header-profile-user" src="assets/images/users/avatar-1.jpg"
                        alt="Header Avatar">
                </button>
                <div class="dropdown-menu dropdown-menu-end pt-0">
                    <a class="dropdown-item" href="contacts-profile.html"><i
                            class='bx bx-user-circle text-muted font-size-18 align-middle me-1'></i> <span
                            class="align-middle">My Account</span></a>
                    <a class="dropdown-item" href="apps-chat.html"><i
                            class='bx bx-chat text-muted font-size-18 align-middle me-1'></i> <span
                            class="align-middle">Chat</span></a>
                    <a class="dropdown-item" href="pages-faqs.html"><i
                            class='bx bx-buoy text-muted font-size-18 align-middle me-1'></i> <span
                            class="align-middle">Support</span></a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item d-flex align-items-center" href="#"><i
                            class='bx bx-cog text-muted font-size-18 align-middle me-1'></i> <span
                            class="align-middle me-3">Settings</span><span
                            class="badge badge-soft-success ms-auto">New</span></a>
                    <a class="dropdown-item" href="auth-lock-screen.html"><i
                            class='bx bx-lock text-muted font-size-18 align-middle me-1'></i> <span
                            class="align-middle">Lock screen</span></a>
                    <a class="dropdown-item" href="auth-logout.html"><i
                            class='bx bx-log-out text-muted font-size-18 align-middle me-1'></i> <span
                            class="align-middle">Logout</span></a>
                </div>
            </div>
        </div>
    </div>
</header>