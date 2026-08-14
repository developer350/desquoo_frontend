@push('js')
    <script>
        $(document).ready(function() {
            $('#city_id').select2({
                placeholder: $('#city_id').data('placeholder'),
            });

            $('#state_id').on('change', function() {
                const stateId = $(this).val();
                $('#city_id').empty().trigger('change'); // Clear the city dropdown

                if (stateId) {
                    $('#city_id').select2({
                        placeholder: $('#city_id').data('placeholder'),
                        ajax: {
                            url: "{{ route('get-cities') }}",
                            delay: 250,
                            data: function(params) {
                                return {
                                    search: params.term,
                                    page: params.page || 1,
                                    state_id: stateId,
                                };
                            },
                            processResults: function(data) {
                                return {
                                    results: data.data.map(item => ({
                                        id: item.id,
                                        text: item.name
                                    })),
                                    pagination: {
                                        more: data.next_page_url !== null
                                    }
                                };
                            },
                            error: function(xhr, status, error) {
                                console.error('AJAX Error:', error, xhr.status, xhr
                                    .responseText);
                            }
                        }
                    });
                } else {
                    $('#city_id').val(null).trigger('change'); // Clear selection if no state is selected
                }
            });
        });
    </script>
@endpush
