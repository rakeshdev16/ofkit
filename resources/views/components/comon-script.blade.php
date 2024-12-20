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

    $(document).on('click', '.toggle-text', function () {
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
            success: function (data) {
                if (data.status == true) {
                    window.location.reload();
                }
            }
        });
    }

    $(document).on('click', '.previousRoute', function (e) {
        var url = $(this).data('url');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: "{{ route('setPreviousRoute') }}",
            success: function (data) {
                if (data.status == true) {
                    window.location.href = url;
                }
            }
        });
    });

    $(document).ready(function () {
        let formChanged = false;
        $('form :input').on('change input', function () {
            // formChanged = true;
            $('#formChanged').val(true);
        });
        $(document).on('click', '.removeMedicine', function () {
            formChanged = true;
        });
        $('.exit').on('click', function (e) {
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
        $('form').on('submit', function () {
            // formChanged = false;
        });


        function handleClass() {
            if ($(window).width() < 768) { // 768px is commonly used for tablet breakpoint
                $('.page-content > .card').removeClass('small-table');
            }
        }
        handleClass();
        $(window).resize(function () {
            handleClass();
        });
    });

    $(document).on('change', '.checkbox', function () {
        if ($('.checkbox').length != $('.checkbox:checked').length) {
            $('.mainCheckbox').prop('checked', false);
        } else {
            $('.mainCheckbox').prop('checked', true);
        }
        var name = $(this).data('name');
        if (name && name.trim() != '') {
            $(this).prop('checked', false);
            toastr.warning(name, null, { timeOut: 5000, extendedTimeOut: 5000 });
            $('.activeInactive').prop('checked', true);
        } else {
            if ($(this).is(':checked') == true) {
                $(this).prop('checked', true);
                $('.activeInactive').prop('active');
            } else {
                $(this).prop('checked', false);
                $('.activeInactive').val('inactive');
            }
        }
    });

</script>
