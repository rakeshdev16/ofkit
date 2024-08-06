<script>
    $(".spkoenLanguages").select2({
        tags: true,
        tokenSeparators: [',', ' ']
    })

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
</script>