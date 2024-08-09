<script>
    $(".diagnosis").select2();
    $(".spkoenLanguages").select2({
        tags: true,
        tokenSeparators: [',', ' ']
    });

    const kindergarten_id = $('.selectedKindergarten').val();
    getKindergartenManager(kindergarten_id);

    $(document).on('change', '.selectedKindergarten', function() {
        var kindergarten_id = $(this).val();
        getKindergartenManager(kindergarten_id);
    });

    function getKindergartenManager(kindergarten_id) {
        $.ajax({
            type : 'GET',
            url : "{{ route('kindergarten-manager.get') }}",
            data : { kindergarten_id: kindergarten_id },
            success : function(data){                        
                if (data.status == true) {
                    $('.kindergartenManager').val(data.name);
                } else {
                    $('.kindergartenManager').val('');
                }
            }
        });
    }

    $(document).on('change', '.foodAllergie', function() {
        if ($(this).val() == 'yes') {
            $('.allergieDetail').show();
        } else {
            $('.allergieDetail').hide();
        }
    });
    $(document).on('change', '.medicine', function() {
        if ($(this).val() == 'yes') {
            var index = parseInt($('.medicineRow').length);
            var no = index + 1;
            $('.medicineDetail').append(`@include('components.medicine-detail', ['no' => '${no}', 'index' => '${index}'])`);
            $('.medicineDetail').show();
        } else {
            $('.medicineDetail').hide();
            $('.medicineRow').remove();
        }
    });

    $(document).on('click', '.addMoreMedicine', function() {
        var index = $('.medicineRow').length;
        var no = index + 1;
        $('.medicineDetail').append(`@include('components.medicine-detail', ['no' => '${no}','index' => '${index}'])`);
    });

    function updateIndexes() {
        $('.medicine-detail').each(function(index, element) {
            $(this).find('input[name^="medicine_dosage"]').each(function() {
                var name = $(this).attr('name').replace(/\[\d+\]/, '[' + index + ']');
                $(this).attr('name', name);
            });
            $(this).find('select[name^="medicine_dosage"]').each(function() {
                var name = $(this).attr('name').replace(/\[\d+\]/, '[' + index + ']');
                $(this).attr('name', name);
            });
        });
    }
</script>