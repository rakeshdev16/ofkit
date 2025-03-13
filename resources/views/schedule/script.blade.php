<script>
    let scrollingPosition = 0;
    $(document).ready(function () {
        $('.page-loader').fadeOut('slow');
        window.addEventListener('scroll', function() {
            scrollingPosition = this.scrollY;
        });
    });

    $(document).on('change', '#kindergartenFilter', function() {
        let url = new URL(window.location.href);
        url.searchParams.delete('user_id');
        url.searchParams.delete('children_id');
        if ($(this).val() == 'personal') {
            $('.create-edit').attr('disabled', true);
        } else {
            $('.create-edit').attr('disabled', false);
        }
        return history.replaceState(null, '', url.toString());
    });

    function appointmentSummary(kindergartenId) {
        const url = "{{ route('schedule.hour-summary') }}?kindergarten_id="+kindergartenId+"&status="+getQueryParam('status');
        fetch(url).then((response) => response.json()).then((data) => {
            $('#childrenSummary').html(data.childrenSummary);
            $('#staffHours').html(data.staffSummary);
            $('#scoreSummary').modal('toggle');
        });
    }

</script>