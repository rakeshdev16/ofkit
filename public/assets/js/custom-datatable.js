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
    queryParam('page', '');
    var url = queryParam('search', search);
    filter(url);
});
$(document).on('click', '.search-button', function () {
    var search = $(this).siblings('.search').val();
    queryParam('page', '');
    var url = queryParam('search', search);
    filter(url);
});

$(document).on('click', '.print-button', function () {

    var userAgent = navigator.userAgent;

    $('.table-search button').hide();
    $('.table-search input').hide();
    $('.page-info .mt-3').hide();
    $('.status').css('width', '300px');

    const originalContent = document.body.innerHTML;
    const printContent = document.querySelector('.page-content').innerHTML;
    document.body.innerHTML = printContent;
    window.print();
    document.body.innerHTML = originalContent;

    if (userAgent.indexOf("Firefox") > -1) {
        const fullUrl = window.location.href;
        console.log("Full URL:", fullUrl);
        window.location.href = fullUrl;
    } else {
        window.location.reload();
    }
});

$(document).on('change', '.select-filter', function () {
    var kindergartenId = $(this).val();

    queryParam('page', '');
    var url = queryParam('kindergarten_id', kindergartenId);
    filter(url);
});

// $(document).on('change', '.status', function () {
//     var status = $(this).val();
//     if (status == 'active') {
//         $('.moveToArchive').text(inactiveInactiveBtnText);
//         confirmButtonText = inactiveButtonText;
//     } else {
//         $('.moveToArchive').text(activeInactiveBtnText);
//         confirmButtonText = activeButtonText;
//     }
//     queryParam('page', '');
//     var url = queryParam('status', status);
//     filter(url);
// });

$(document).on('change', '.status', function () {
    var status = $(this).prop('checked') ? 'inactive' : 'active'; // Check if checked, then set status

    if (status == 'inactive') {
        $(this).prop('checked', true); // Make sure the checkbox is checked if inactive
        $(this).val('inactive');
        $('.moveToArchive').text(activeInactiveBtnText);
        confirmButtonText = activeButtonText;
    } else {
        $(this).prop('checked', false); // Make sure the checkbox is unchecked if active
        $(this).val('active');
        $('.moveToArchive').text(inactiveInactiveBtnText);
        confirmButtonText = inactiveButtonText;
    }

    // Use queryParam to update the URL with the status value
    queryParam('page', '');
    var url = queryParam('status', status);
    filter(url);
});


$(document).on('change', '.doc-filter', function () {
    var name = $(this).attr('name');
    var value = $(this).val();
    $(this).find(`option[value="${value}"]`).attr('selected', 'selected');

    var dateType = $(this).data('type');
    if (name == 'date') {
        if (value.includes(' - ')) {
            var dateRange = value.split(' - ');
            value = [dateRange[0], dateRange[1]];
        } else {
            value = formatDate(value, 'd/m/Y');
        }
        queryParam('dateType', dateType);
    }
    queryParam('page', '');
    var url = queryParam(name, value);
    filter(url);
    $('.dropdown-item').removeClass('active-filter');
    $(this).siblings(".dropdown-item").addClass('active-filter');
});

function dateFilter(date, dateType) {
    queryParam('page', '');
    queryParam('dateType', dateType);
    var url = queryParam('date', date);
    filter(url);
    if (date.length === 2) {
        var dateLabel = date[1] + ' - ' + date[0];
        $('.dropdown-filter-toggle').html(dateLabel);
    }
}

$(document).on('click', '.this-filter', function () {
    $('.dropdown-item').removeClass('active-filter');
    $(this).addClass('active-filter');
});

function formatDate(date, format) {
    var parsedDate = new Date(date);
    if (!isNaN(parsedDate)) {
        var day = ('0' + parsedDate.getDate()).slice(-2);
        var month = ('0' + (parsedDate.getMonth() + 1)).slice(-2);
        var year = parsedDate.getFullYear();
        return day + '/' + month + '/' + year;
    }
    return date;
}

function clearFilter(param) {
    var url = queryParam(param, '');
    $('.dropdown-filter-toggle').html(selectDate);
    $('.dateRangePicker').val(selectDateRange);
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
            let searchParams = new URLSearchParams(window.location.search);
            let param = searchParams.get('status');
            if (param != 'inactive') {
                var name = $(this).data('name');

                if (name && name.trim() != '') {

                    $(this).prop('checked', false);
                    toastr.warning(name, null, { timeOut: 5000, extendedTimeOut: 5000 });
                } else {
                    $(this).prop('checked', true);
                }
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
            let searchParams = new URLSearchParams(window.location.search);
            let param = searchParams.get('status');
            if (param != 'inactive') {
                var name = $(this).data('name');
                if (name && name.trim() != '') {
                    $(this).prop('checked', false);
                    toastr.warning(name, null, { timeOut: 5000, extendedTimeOut: 5000 });
                } else {
                    $(this).prop('checked', true);
                }
            } else {
                $(this).prop('checked', true);
            }
        });
    } else {
        $('.accordionCheckbox').prop('checked', false);
    }
});

$(document).on('change', '.accordionCheckbox', function () {
    let searchParams = new URLSearchParams(window.location.search);
    let param = searchParams.get('status');

    if (param != 'inactive') {
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
    }
});

function moveToArchive(msg, status, model) {
    var ids = [];
    $(".checkbox:checked").each(function () {
        var value = $(this).val();
        if (value) {
            ids.push(value);
        }
    });

    $.unique(ids.sort());

    if (ids.length == 0) {
        toastr.warning(msg);
        return false
    }
    let searchParams = new URLSearchParams(window.location.search);
    status = searchParams.get('status') ?? 'active';

    var url = activeInactive + "?ids=" + ids.join(',') + "&status=" + status + "&model=" + model;

    Swal.fire({
        title: confirmMsgTitle,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: confirmButtonText ? confirmButtonText : inactiveButtonText,
        cancelButtonText: cancelButtonText
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                type: 'POST',
                url: url,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (data) {
                    if (data.status == true) {

                        if (status == 'active') {
                            data.ids.map(function (id) {
                                $('.tr-' + id).remove();
                            });
                        } else {
                            data.ids.map(function (id) {
                                $('.tr-' + id).removeClass('inactive');
                                $('.check-' + id).prop('checked', false);
                            });
                        }
                        // console.log(data.count);
                        console.log(data);


                        $('.totalCount').text(data.count);
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
