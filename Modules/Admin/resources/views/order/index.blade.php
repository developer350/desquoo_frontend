@extends('admin::layouts.app')
@section('title', 'Orders')
@push('css')
    <style>
        .changeStatus {
            cursor: pointer;
        }
    </style>
@endpush
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
                                <label for="status">Status</label>
                                <select name="status" id="orderStatus" class="form-control">
                                    <option value="">All</option>
                                    <option value="pending">Pending</option>
                                    <option value="confirmed">Confirmed</option>
                                    <option value="processing">Processing</option>
                                    <option value="shipped">Shipped</option>
                                    <option value="delivered">Delivered</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">Payment Status</label>
                                <select name="payment_status" id="paymentStatus" class="form-control">
                                    <option value="">All</option>
                                    <option value="paid">Paid</option>
                                    <option value="pending">Unpaid</option>
                                    <option value="failed">Failed</option>
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
                                <th>UUID</th>
                                <th style="width: 80px;">Order Date</th>
                                <th>User</th>
                                <th>Item Count</th>
                                <th>Grand Total</th>
                                <th>Payment Method</th>
                                <th>Payment Status</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- modal for changing status --}}
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Change Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('orders.change-status') }}" method="post" id="statusForm">
                        @csrf
                        <input type="hidden" name="order_id" id="order_id">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="OrderStatus" class="form-control">
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="processing">Processing</option>
                                <option value="shipped">Shipped</option>
                                <option value="delivered">Delivered</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="statusForm" id="statusFormBtn">Update
                        Status</button>
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
            //header has status filter too
            var statusFromHeader = new URLSearchParams(window.location.search).get('status');
            if (statusFromHeader) {
                $('#orderStatus').val(statusFromHeader).trigger('change');
            }

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
                        data: 'uuid',
                        name: 'uuid',
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        className: 'text-nowrap',
                    }, {
                        data: 'user.name',
                        name: 'user.name',
                        render: function(data, type, row) {
                            if (data == null) {
                                return '<span class="badge bg-secondary">Guest</guest>';
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        data: 'item_count',
                        name: 'item_count',
                    },
                    {
                        data: 'grand_total',
                        name: 'grand_total'
                    },
                    {
                        data: 'payment_method',
                        name: 'payment_method'
                    },
                    {
                        data: 'payment_status',
                        name: 'payment_status',
                        render: function(data) {
                            if (data == 'paid') {
                                return '<span class="badge bg-success">Paid</span>';
                            } else if (data == 'failed') {
                                return '<span class="badge bg-danger">Failed</span>';
                            } else {
                                return '<span class="badge bg-warning">Unpaid</span>';
                            }
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data, type, row) {
                            var id = row.id;
                            if (data == 'confirmed') {
                                return '<span class="badge bg-primary changeStatus" data-id="' +
                                    id + '" data-current="' + data + '">Confirmed</span>';
                            } else if (data == 'cancelled') {
                                return '<span class="badge bg-danger">Cancelled</span>';
                            } else if (data == 'processing') {
                                return '<span class="badge bg-secondary changeStatus" data-id="' +
                                    id + '" data-current="' + data + '">Processing</span>';
                            } else if (data == 'shipped') {
                                return '<span class="badge bg-info changeStatus" data-id="' + id +
                                    '" data-current="' + data + '">Shipped</span>';
                            } else if (data == 'delivered') {
                                return '<span class="badge bg-success changeStatus" data-id="' +
                                    id + '" data-current="' + data + '">Delivered</span>';
                            } else {
                                return '<span class="badge bg-warning changeStatus" data-id="' +
                                    id + '" data-current="' + data + '">Pending</span>';
                            }
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        searchable: false,
                        orderable: false
                    },
                ],
                ajaxOptions: {
                    url: "{{ route('orders.index') }}",
                    data: () => ({
                        status: $('#orderStatus').val(),
                        payment_status: $('#paymentStatus').val(),
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

            $('#filter-btn').on('click', function() {
                $('#dataTable').DataTable().ajax.reload();
            });

            $('#reset-btn').on('click', function() {
                $('#orderStatus').val('');
                dateRange.clear();
                $('#paymentStatus').val('');
                $('#user').val(null).trigger('change');
                $('#dataTable').DataTable().ajax.reload();
            });

            $(document).on('click', '.changeStatus', function() {
                $('#order_id').val($(this).data('id'));
                let currentStatus = $(this).data('current');

                var options = $('#OrderStatus option').map(function() {
                    return $(this).val();
                }).get();

                if (options.indexOf(currentStatus) > -1) {
                    let currentIndex = options.indexOf(currentStatus);

                    // Disable all options up to and including current status
                    $('#OrderStatus option').each(function(index) {
                        if (index <= currentIndex) {
                            $(this).prop('disabled', true);
                        } else {
                            $(this).prop('disabled', false);
                        }
                    });

                    // Select the next status if available
                    if (currentIndex + 1 < options.length) {
                        $('#OrderStatus').val(options[currentIndex + 1]);
                    } else {
                        // If no next status exists, keep current status selected
                        $('#OrderStatus').val(currentStatus);
                    }
                }

                $('#OrderStatus').trigger('change');

                $('#statusModal').modal('show');
            });

            $('#statusForm').on('submit', function(e) {
                e.preventDefault();
                let form = $(this);
                let data = form.serialize();

                let submitBtn = $('#statusFormBtn');
                let originalText = submitBtn.html();
                submitBtn
                    .prop("disabled", true)
                    .html('Updating');

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: data,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            $('#statusModal').modal('hide');
                            $('#dataTable').DataTable().ajax.reload();
                            showToast(response.message, 'success');
                        } else {
                            showToast(response.message, 'error');
                        }

                        submitBtn
                            .prop("disabled", false)
                            .html(originalText);
                    },
                    error: function(response) {
                        showToast(response.responseJSON.message, 'error');

                        submitBtn
                            .prop("disabled", false)
                            .html(originalText);
                    }
                });
            });
        });
    </script>
@endpush
