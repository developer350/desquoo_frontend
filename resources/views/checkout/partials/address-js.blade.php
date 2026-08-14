<script>
    $(document).on('click', '.edit-address', function() {
        var addressId = $(this).data('id');
        var url = "{{ route('addresses.update', ['address' => ':id']) }}".replace(':id', addressId);
        $('#addressEditForm').attr('action', url);
        $.ajax({
            url: '{{ route('addresses.edit', ['address' => ':id']) }}'.replace(':id', addressId),
            method: 'GET',
            success: function(response) {
                console.log(response);
                $('#edit_name').val(response.name);
                $('#edit_email').val(response.email);
                $('#edit_phone_number').val(response.phone_number);
                initializePhoneInputs('.edit_phone_number', '.edit_country_code', 'in', ['in']);

                $('#edit_address_line_1').val(response.address_line_1);
                $('#edit_address_line_2').val(response.address_line_2);
                $('#edit_postal_code').val(response.postal_code);
                $('#edit_landmark').val(response.landmark);
                $('#edit_gstnumber').val(response.gst_number);
                //set state and trigger change to load cities
                var stateOption = new Option(response.state, response.state, true, true);
                $('#edit_state').append(stateOption).trigger('change');

                //set city
                var cityOption = new Option(response.city, response.city, true, true);
                $('#edit_city').append(cityOption).trigger('change');
                $('#addressEditModal').modal('show');
            },
            error: function() {
                alert('Failed to fetch address details. Please try again.');
            }
        });
    });

    $(document).on('shown.bs.modal', '#addressEditModal', function() {
        selectInit();
    });

    function selectInit() {
        $('#edit_city').select2({
            dropdownParent: $('#addressEditModal'),
            closeOnSelect: true,
            theme: "select2-custom",
            ajax: {
                url: "{{ route('addresses.getCities') }}",
                data: function(params) {
                    return {
                        q: params.term, // search term
                        state_id: $('#edit_state').val(),
                    };
                },
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data.map(function(city) {
                            return {
                                id: city.name,
                                text: city.name
                            };
                        })
                    };
                },
                cache: true
            }
        }).on('change', function() {
            $(this).valid();
        });

        $("#edit_state").select2({
            dropdownParent: $('#addressEditModal'),
            closeOnSelect: true,
            theme: "select2-custom",
            ajax: {
                url: "{{ route('addresses.getStates') }}",
                data: function(params) {
                    return {
                        q: params.term, // search term
                    };
                },
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data.map(function(state) {
                            return {
                                id: state.name,
                                text: state.name
                            };
                        })
                    };
                },
                cache: true
            }
        }).on('change', function() {
            $('#edit_city').val('').trigger('change');
        })
    }

    setupValidation('#addressEditForm', {}, {}, afterSuccess);

    function afterSuccess() {
        $('#addressEditModal').modal('hide');
        reloadAddress();
    }

    var deleteUrl;
    $(document).on('click', '.delete-address', function() {
        var addressId = $(this).data('id');
        deleteUrl = "{{ route('addresses.destroy', ['address' => ':id']) }}".replace(':id', addressId);
        $('#removeModalAddress').modal('show');
        $('#removeModalAddress').css({
            'opacity': '1',
            'pointer-events': 'all'
        });
    });

    $(document).on('click', '.deleteAddress', function() {
        $.ajax({
            url: deleteUrl,
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                _method: 'DELETE'
            },
            beforeSend: function() {
                $('#removeModalAddress').css({
                    'opacity': '0.5',
                    'pointer-events': 'none'
                });
            },
            success: function(response) {
                if (response.status) {
                    showToast('success', response.message);
                    $('#removeModalAddress').modal('hide');
                    reloadAddress();
                } else {
                    showToast('error', response.message);
                }
            },
            complete: function() {
                $('#removeModalAddress').css({
                    'opacity': '1',
                    'pointer-events': 'all'
                });
            }
        });
    });
</script>
