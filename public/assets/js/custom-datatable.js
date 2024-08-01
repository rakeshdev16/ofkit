// $(document).on('keyup', '.search', function () {
//     var search = $(this).val();
//     var url = queryParam('search', search);
//     filter(url);
// });
$(document).on('click', '.search-button', function () {
    var search = $(this).siblings('.search').val();
    var url = queryParam('search', search);
    filter(url);
});

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

$(document).on('change', '.mainCheckbox', function() {
    if ($(this).is(':checked') == true) {
        $('.checkbox').each(function() {
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

$(document).on('change', '.checkbox', function() {
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

$(document).on('change', '.mainAccordionCheckbox', function() {
    if ($(this).is(':checked') == true) {
        $('.accordionCheckbox').each(function() {
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

$(document).on('change', '.accordionCheckbox', function() {
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

function moveToArchive(url, msg)
{
    var ids = [];
    $(".checkbox:checked").map(function(){
        ids.push($(this).val());
    });
    $.unique(ids.sort());
    if (ids.length == 0) {
        toastr.warning(msg);
        return false
    }
    url = url.replace(':ids', ids);
    Swal.fire({
        title: "Are you sure?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, archive it!"
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
                        data.ids.map(function(id) {
                            $('.tr-'+id).remove();
                        });
                        toastr.success(data.message);
                    }
                }
            });               
        }
    });
}