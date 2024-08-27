<script>
    $(document).on('change', '.startTime', function() {
        var startTime = $(this).val();
        var endTimeInput = $('.endTime');
        endTimeInput.attr('min', startTime);
        if (endTimeInput.val() && endTimeInput.val() < startTime) {
            endTimeInput.val('');
        }
    });

    $(document).on('change', '.endTime', function() {
    var startTime = $('.startTime').val();
    var endTime = $(this).val();
    
    // Find the sibling `.invalid-feedback` span after the input
    var errorSpan = $(this).siblings('.invalid-feedback');

    if (endTime < startTime) {
        $(this).val(''); // Clear the input if the end time is earlier

        // If the .invalid-feedback element doesn't exist, create it
        if (errorSpan.length === 0) {
            $(this).after(`
                <span class="invalid-feedback" role="alert">
                    <strong>End Time cannot be earlier than Start Time.</strong>
                </span>
            `);
        } else {
            errorSpan.html('<strong>End Time cannot be earlier than Start Time.</strong>');
            errorSpan.show();
        }
    } else {
        // If there's an existing error message, clear it
        if (errorSpan.length > 0) {
            errorSpan.html('');
            errorSpan.hide();
        }
    }
});


    $(document).on('click', '.button', function() {
        $(this).attr('disabled', false);
    });

    $(document).on('change', '.occured', function() {
        var value = $(this).val();
        if (value == 0) {
            $('.occuredReason').find('input, select, textarea').attr('value', '');
            $('.occuredDescription').hide();
            $('.occuredReason').show();
        } else {
            $('.occuredDescription').find('input, select, textarea').attr('value', '');
            $('.occuredReason').hide();
            $('.occuredDescription').show();
        }
    });

    $('.file').change(function(event) {
        const file = event.target.files[0];
        var url = URL.createObjectURL(file);
        $('.choosenFile').html('<div class="document mt-1"><a href="'+url+'" target="_blank" rel="noopener noreferrer">'+ file.name +'</a><i class="bx bx-x childDocument" data-file-name="' + file.name + '"></i></div>');
    });

    $(document).on('click', '.childDocument', function() {
        $('.file').val('');
        $('.deleteFile').val('1');
        $(this).parent().remove();
    })
</script>