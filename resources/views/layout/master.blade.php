<!doctype html>
<html lang="en" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('assets/images/favicon-32x32.png') }}" type="image/png" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('assets/js/pace.min.js') }}"></script>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/dark-theme.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/semi-dark.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/header-colors.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>אופקית - טיפול ויעוץ</title>
    @stack('customLink')
</head>

<body class="pace-done" style="background-color: #FFFCF1">
    <div class="wrapper">

        @include('layout.header')

        @yield('section')

        @include('layout.footer')
    </div>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
    <script src="{{ asset('assets/plugins/chartjs/js/chart.js') }}"></script>
    <script src="{{ asset('assets/js/index.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
    @stack('customScript')

    <script>
        var activeInactiveBtnText = "{{ __('comon.active') }}";
        var inactiveInactiveBtnText = "{{ __('comon.inactive') }}";
        var activeInactive = "{{ route('activeInactive.records') }}";
        var confirmMsgTitle = "{{ __('comon.confirmTitle') }}";
        var activeButtonText = "{{__('comon.activeButtonText')}}";
        var inactiveButtonText = "{{__('comon.inactiveButtonText')}}";
        var confirmButtonText = null;
        var cancelButtonText = "{{ __('comon.cancel') }}";
        var selectDate = "{{ __('children.selectDate') }}";
        var selectDateRange = "{{ __('children.selectDateRange') }}";
        $(document).on('click', '.toggle-text', function() {
            var status = $(this).data('status');
            var truncatedText = $(this).siblings('.truncated-text');
            var fullText = $(this).siblings('.full-text');

            if (status === 'less') {
                // Show the full text
                truncatedText.hide();
                fullText.show();
                $(this).data('status', 'more').text("{{ __('comon.showLess') }}");
            } else {
                // Show the truncated text
                fullText.hide();
                truncatedText.show();
                $(this).data('status', 'less').text("{{ __('comon.showMore') }}");
            }
        });

        function setLocale(lang) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: "{{ route('set.locale') }}",
                data: {
                    lang: lang
                },
                success: function(data) {
                    if (data.status == true) {
                        window.location.reload();
                    }
                }
            });
        }

        $(document).on('click', '.previousRoute', function(e) {
            var url = $(this).data('url');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: "{{ route('setPreviousRoute') }}",
                success: function(data) {
                    if (data.status == true) {
                        window.location.href = url;
                    }
                }
            });
        });

        $(document).ready(function() {
            let formChanged = false;
            $('form :input').on('change input', function() {
                // formChanged = true;
                $('#formChanged').val(true);
            });
            $(document).on('click', '.removeMedicine', function() {
                formChanged = true;
            });
            $('.exit').on('click', function(e) {
                $(this).attr('disabled', false);
                if ($('#formChanged').val()) {
                    e.preventDefault();
                    Swal.fire({
                        title: "{{ __('comon.unsavedChanges') }}",
                        text: "{{ __('comon.unsavedChangesMsg') }}",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "{{ __('comon.yesLeaveIt') }}",
                        cancelButtonText: "{{ __('comon.cancel') }}"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = $(this).data('url');
                        }
                    });
                } else {
                    window.location.href = $(this).data('url');
                }
            });
            $('form').on('submit', function() {
                // formChanged = false;
            });


            function handleClass() {
                if ($(window).width() < 768) { // 768px is commonly used for tablet breakpoint
                    $('.page-content > .card').removeClass('small-table');
                }
            }
            handleClass();
            $(window).resize(function() {
                handleClass();
            });
        });

        // Increment the tab count when a new tab loads
        // window.addEventListener('load', function() {
        //     let tabCount = localStorage.getItem('tabCount');
        //     tabCount = tabCount ? parseInt(tabCount) + 1 : 1;
        //     localStorage.setItem('tabCount', tabCount);
        //     localStorage.setItem('isReloading', 'true');
        // });

        // // Decrement the tab count when a tab is closed or reloaded
        // window.addEventListener('beforeunload', function(e) {
        //     let tabCount = localStorage.getItem('tabCount');
        //     let isReloading = localStorage.getItem('isReloading');

        //     // Only decrement the tab count if it's not a reload
        //     if (!isReloading) {
        //         tabCount = tabCount ? parseInt(tabCount) - 1 : 0;
        //         localStorage.setItem('tabCount', tabCount);

        //         // If no more tabs are open, trigger the logout process
        //         if (tabCount <= 0) {
        //             // Use sendBeacon for logging out the user asynchronously
        //             navigator.sendBeacon('/expire-session');
        //         }
        //     }

        //     // Clear the reloading flag after use
        //     localStorage.removeItem('isReloading');
        // });

        setInterval(function() {
            $.ajax({
                url: '/check-session',
                type: 'GET',
                success: function(response) {
                    if (!response.isAuthenticated) {
                        window.location.href = "{{ route('page.expired') }}";
                    }
                },
                error: function() {
                    window.location.href = "{{ route('page.expired') }}";
                }
            });
        }, 60000);
    </script>
</body>

</html>
