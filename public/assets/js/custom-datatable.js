var previousValue = '';
$(document).on('input', '.search', function () {
    var currentValue = $(this).val();
    if (previousValue !== '' && currentValue === '') {
        var search = $(this).val();
        var url = queryParam('search', search);
        filter(url);
    }
    previousValue = currentValue;
});
$(document).on('change', '.search', function () {
    var search = $(this).val();
    var url = queryParam('search', search);
    filter(url);
});
$(document).on('click', '.search-button', function () {
    var search = $(this).siblings('.search').val();
    var url = queryParam('search', search);
    filter(url);
});

$(document).on('change', '.select-filter', function () {
    var kindergartenId = $(this).val();
    var url = queryParam('kindergarten_id', kindergartenId);
    filter(url);
});

$(document).on('change', '.doc-filter', function () {
    var name = $(this).attr('name');
    var value = $(this).val();
    var url = queryParam(name, value);
    filter(url);
});

function dateFilter(date) {
    var url = queryParam('date', date);
    filter(url);
    if (date.length === 2) {
        var dateLabel = dateFormat(date[1]) + ' - ' + dateFormat(date[0]);
        $('.dropdown-filter-toggle').html(dateLabel);
    }
}

function clearFilter(param) {
    var url = queryParam(param, '');
    $('.dropdown-filter-toggle').html('Select Date');
    $('.dateRangePicker').val('Select Date Range');
    $('.dateRangePicker').hide();
    filter(url);
}

$(document).on('click', '.sortTable', function () {
    var key = $(this).data('key');
    var value = $(this).data('value') == 'desc' ? 'asc' : 'desc';
    queryParam('sort', key);
    var url = queryParam('sorting', value);
    filter(url);
    $(this).attr('data-value', value);
});

$(document).on('click', '.paginationBtn', function () {
    var page = $(this).data('page');
    page = page.split('page=');
    var url = queryParam('page', page[1]);
    filter(url);
});

function filter(url) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        type: 'GET',
        url: url,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (data) {
            $('.totalCount').html(data.count);
            $('#dataTable').html(data.table);
            $('#accordion').html(data.accordion);
        }
    });
}

function queryParam(name, value) {
    var currentUrl = new URL(window.location.href);
    var searchParams = currentUrl.searchParams;
    searchParams.set(name, value);
    var newUrl = currentUrl.origin + currentUrl.pathname + '?' + searchParams.toString();
    history.replaceState(null, '', newUrl);
    return newUrl;
}

$(document).on('change', '.mainCheckbox', function () {
    if ($(this).is(':checked') == true) {
        $('.checkbox').each(function () {
            var name = $(this).data('name');
            if (name && name.trim() != '') {
                $(this).prop('checked', false);
                toastr.warning(name, null, { timeOut: 5000, extendedTimeOut: 5000 });
            } else {
                $(this).prop('checked', true);
            }
        });
    } else {
        $('.checkbox').prop('checked', false);
    }
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
    } else {
        if ($(this).is(':checked') == true) {
            $(this).prop('checked', true);
        } else {
            $(this).prop('checked', false);
        }
    }
});

$(document).on('change', '.mainAccordionCheckbox', function () {
    if ($(this).is(':checked') == true) {
        $('.accordionCheckbox').each(function () {
            var name = $(this).data('name');
            if (name && name.trim() != '') {
                $(this).prop('checked', false);
                toastr.warning(name, null, { timeOut: 5000, extendedTimeOut: 5000 });
            } else {
                $(this).prop('checked', true);
            }
        });
    } else {
        $('.accordionCheckbox').prop('checked', false);
    }
});

$(document).on('change', '.accordionCheckbox', function () {
    if ($('.accordionCheckbox').length != $('.accordionCheckbox:checked').length) {
        $('.mainAccordionCheckbox').prop('checked', false);
    } else {
        $('.mainAccordionCheckbox').prop('checked', true);
    }
    var name = $(this).data('name');
    if (name && name.trim() != '') {
        $(this).prop('checked', false);
        toastr.warning(name, null, { timeOut: 5000, extendedTimeOut: 5000 });
    } else {
        if ($(this).is(':checked') == true) {
            $(this).prop('checked', true);
        } else {
            $(this).prop('checked', false);
        }
    }
});

function moveToArchive(url, msg) {
    var ids = [];
    $(".checkbox:checked").each(function () {
        var value = $(this).val();
        if (value) {  // Only push non-empty values
            ids.push(value);
        }
    });
    $.unique(ids.sort());

    if (ids.length == 0) {
        toastr.warning(msg);
        return false
    }
    url = url.replace(':ids', ids);
    Swal.fire({
        title: confirmMsgTitle,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: confirmButtonText,
        cancelButtonText: cancelButtonText
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                type: 'DELETE',
                url: url,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (data) {
                    if (data.status == true) {
                        data.ids.map(function (id) {
                            $('.tr-' + id).remove();
                        });
                        toastr.success(data.message);
                    }
                }
            });
        }
    });
}

function dateFormat(date) {
    var date = new Date(date);
    var day = String(date.getDate()).padStart(2, '0');
    var month = String(date.getMonth() + 1).padStart(2, '0');
    var year = date.getFullYear();
    if (day && month && year) {
        return `${day}/${month}/${year}`;
    } else {
        return 'Select Date';
    }
}
