$(document).on('keyup', '.search', function () {
    var search = $(this).val();
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