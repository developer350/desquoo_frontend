@extends('admin::layouts.app')
@section('title', 'Blog Comments')
@section('buttons')
    <button id="export" class="btn btn-success btn-sm mt-4">
        <i class="fas fa-download"></i> Export
    </button>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <form class="row g-2 align-items-center">
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="date_range"
                                placeholder="Select date & time range">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">Apply Filter</button>
                            <button type="button" class="btn btn-danger" id="reset-btn">Reset</button>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <table id="dataTable" class="table table-bordered dt-responsive" style="width:100%">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Blog</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Comment</th>
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
@include('admin::partials.datepicker-setup')
@include('admin::partials.data-tables-setup')
@include('admin::partials.sweet-alert-setup')
@push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const dateRangeInput = document.getElementById("date_range");

            const dateRange = flatpickr("#date_range", {
                mode: "range",
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                altInput: true,
                altFormat: "d M Y h:i K",
                maxDate: "today"
            });

            let table = initializeDataTable({
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'blog',
                        name: 'blog.title',
                        render: data => formatData(data),
                        orderable: false
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
                        data: 'comment',
                        name: 'comment',
                        render: data => formatData(data),
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
                    url: "{{ route('blog-comments.index') }}",
                    data: () => ({
                        date_range: $('#date_range').val()
                    })
                }
            });

            $(document).on("submit", "form", (e) => {
                e.preventDefault();
                let [start, end] = dateRangeInput.value.split(" to ") || [];
                if (!start || !end) {
                    showToast("Please select a valid date range.", "warning");
                    return;
                }
                table.ajax.reload();
            });

            $("#reset-btn").click(() => {
                dateRange.clear();
                table.ajax.reload();
            });

            $("#export").click(() => {
                let [start, end] = dateRangeInput.value.split(" to ") || [];

                // Show toast if only one of the dates is present
                if (!!start !== !!end) {
                    return showToast("Please select a valid date range.", "warning");
                }

                window.location.href =
                    `{{ route('blog-comments.export') }}?start_date=${start}&end_date=${end}`;
            });
        });
    </script>
@endpush
