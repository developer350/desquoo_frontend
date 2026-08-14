@extends('admin::layouts.app')
@section('title', 'Product Notify')
@section('buttons')
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="date_range">Date Range</label>
                            <input type="text" class="form-control" id="date_range" placeholder="Select date & time range">
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">Product</label>
                                <select name="product" id="product" class="form-control" data-placeholder="All">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-sm btn-danger me-1" id="reset-btn"><i
                                class="fas fa-sync"></i></button>
                        <button type="button" class="btn btn-sm btn-primary" id="filter-btn"><i
                                class="fas fa-filter"></i></button>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <table id="dataTable" class="table table-bordered dt-responsive" style="width:100%">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Product</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@include('admin::partials.data-tables-setup')
@include('admin::partials.sweet-alert-setup')
@include('admin::partials.select2-setup')
@include('admin::partials.datepicker-setup')
@push('js')
    <script>
        $(document).ready(function() {
            const dateRangeInput = document.getElementById("date_range");

            const dateRange = flatpickr("#date_range", {
                mode: "range",
                enableTime: false,
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d M Y",
                maxDate: "today"
            });

            initializeDataTable({
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'product.name',
                        name: 'product.name',
                        render: function(data, type, row) {
                            //user card with professtion,name,email
                            let html = `<div class="d-flex align-items-center">
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">${data}</h6>`;
                            let lastIndex = row.product_variant.attribute_values.length - 1;
                            $.each(row.product_variant.attribute_values, function(indexInArray,
                                valueOfElement) {
                                html +=
                                    `<span class="text-muted">${valueOfElement.attribute.name} : ${valueOfElement.value}${indexInArray !== lastIndex ? ',' : ''}</span>`
                            });
                            html += `</div>
                            </div>`;

                            return html;
                        }
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone_number',
                        name: 'phone_number'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        searchable: false,
                        orderable: false
                    }
                ],
                ajaxOptions: {
                    url: "{{ route('notify-mes.index') }}",
                    data: () => ({
                        product: $('#product').val(),
                        date_range: $('#date_range').val()
                    }),
                }
            });

            $('#product').select2({
                ajax: {
                    url: "{{ route('get-products') }}",
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page || 1,
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
                        console.error('AJAX Error:', error, xhr.status, xhr.responseText);
                    }
                }
            });

            $('#filter-btn').on('click', function() {
                $('#dataTable').DataTable().ajax.reload();
            });

            $('#reset-btn').on('click', function() {
                dateRange.clear();
                $('#product').val(null).trigger('change');
                $('#dataTable').DataTable().ajax.reload();
            });
        });
    </script>
@endpush
