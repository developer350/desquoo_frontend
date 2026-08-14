@extends('admin::layouts.app')
@section('title', 'Google Reviews')
@section('buttons')
    <button class="btn btn-sm btn-warning" id="resync-reviews">
        <i class="fas fa-sync"></i>
        Resync Reviews (Required Login)
    </button>
    <a href="{{ route('google-reviews.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Create
    </a>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="dataTable" class="table table-bordered dt-responsive" style="width:100%">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Avatar</th>
                                <th>Name</th>
                                <th>Rating</th>
                                <th>Sort Order</th>
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
@push('js')
    <script>
        $(document).ready(function() {
            initializeDataTable({
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'avatar',
                        name: 'avatar',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'name',
                        name: 'name',
                        render: data => formatData(data)
                    },
                    {
                        data: 'rating',
                        name: 'rating'
                    },
                    {
                        data: 'sort_order',
                        name: 'sort_order'
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
                    url: "{{ route('google-reviews.index') }}",
                }
            });
        });

        $('#resync-reviews').click(function (e) {
            e.preventDefault();
            swal.fire({
                title: "Are you sure you want to resync reviews?",
                text: "Please login with company mail and it will take 5-10 minutes to resync.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, Resync!",
                cancelButtonText: "No, Cancel",
            }).then(function (result) {
                if (result.value) {
                    window.location.href = "{{ route('google-review.login') }}";
                }
            });
        });
    </script>
@endpush
