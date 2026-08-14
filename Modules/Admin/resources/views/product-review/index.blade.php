@extends('admin::layouts.app')
@section('title', 'Product Reviews')
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
                                <label for="status">User</label>
                                <select name="user" id="user" class="form-control" data-placeholder="All">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">Product</label>
                                <select name="product" id="product" class="form-control" data-placeholder="All">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="orderStatus" class="form-control">
                                    <option value="">All</option>
                                    <option value="1">Enabled</option>
                                    <option value="0">Disabled</option>
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
                                <th>User</th>
                                <th>Product</th>
                                <th>Rating</th>
                                <th>Comment</th>
                                <th>Image</th>
                                <th>Created At</th>
                                <th>Highlight</th>
                                <th>Status</th>
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
                        data: 'user.name',
                        name: 'user.name',
                        render: function(data, type, row) {
                            //user card with professtion,name,email
                            return `<div class="d-flex align-items-center">
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">${data}</h6>
                                    <span class="text-muted">Display Name : ${row.display_name},</span>
                                    <span class="text-muted">Profession : ${row.profession}</span>
                                </div>
                            </div>`;
                        }
                    },
                    {
                        data: 'product.name',
                        name: 'product.name',
                    },
                    {
                        data: 'rating',
                        name: 'rating'
                    },
                    {
                        data: 'comment',
                        name: 'comment',
                        render: data => formatData(data),
                    },
                    {
                        data: 'image',
                        name: 'image',
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'highlight',
                        name: 'highlight'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        searchable: false,
                        orderable: false
                    }
                ],
                ajaxOptions: {
                    url: "{{ route('product-reviews.index') }}",
                    data: () => ({
                        status: $('#orderStatus').val(),
                        product: $('#product').val(),
                        user: $('#user').val(),
                        date_range: $('#date_range').val()
                    }),
                }
            });

            $('#user').select2({
                ajax: {
                    url: "{{ route('search.users') }}",
                    data: function(params) {
                        return {
                            q: params.term,
                            page: params.page
                        };
                    },
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data, params) {
                        params.page = params.page || 1;

                        return {
                            results: data.data.map(function(item) {
                                return {
                                    id: item.id,
                                    text: item.name
                                }
                            }),
                            pagination: {
                                more: (params.page * 20) < data.total
                            }
                        };
                    },
                    cache: true
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
                $('#orderStatus').val('');
                dateRange.clear();
                $('#user').val(null).trigger('change');
                $('#product').val(null).trigger('change');
                $('#dataTable').DataTable().ajax.reload();
            });
        });
    </script>
@endpush
