<script>
    $(".select2").select2({
        placeholder: "Enter name of book",
        //minimumInputLength: 2,
        cache: true,
        ajax: {
            delay: 250,
            dataType: 'json',
            data: function (params) {
                return {
                    q: params.term,
                    page: params.page
                };
            },

            url: '{{route($model_ajax_route ?? 'home')}}',
            processResults: function (data, params) {
                return {
                    results: data
                };
            }
        }
    });
</script>