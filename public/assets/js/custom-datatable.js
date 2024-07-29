// $(document).on('keyup', '.search', function () {
//     var search = $(this).val();
//     var url = queryParam('search', search);
//     filter(url);
// });
$(document).on('click', '.search-button', function () {
    var search = $(this).siblings('.search').val();
    console.log(search);
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
        $('.checkbox').prop('checked', true);
    } else {
        $('.checkbox').prop('checked', false);
    }
});

$(document).on('change', '.checkbox', function() {
    var checkClass = $(this).data('class');
    if ($(this).is(':checked') == true) {
        $('.'+checkClass).prop('checked', true);
    } else {
        $('.'+checkClass).prop('checked', false);
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