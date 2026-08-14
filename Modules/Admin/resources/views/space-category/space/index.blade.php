@extends('admin::layouts.app')
@section('title', 'Spaces')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('space-categories.index') }}">
            Space Categories
        </a>
    </li>
@endsection
@section('buttons')
    <a href="{{ route('space-categories.spaces.create', base64_encode($spaceCategory->id)) }}" class="btn btn-primary btn-sm">
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
                                <th>Image</th>
                                <th>Title</th>
                                <th>Sort Order</th>
                                <th>Show on Home Page</th>
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
                        data: 'image',
                        name: 'image',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'title',
                        name: 'title',
                        render: data => formatData(data)

                    },
                    {
                        data: 'sort_order',
                        name: 'sort_order'
                    },
                    {
                        data: 'is_home',
                        name: 'is_home'
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
                    url: "{{ route('space-categories.spaces.index', base64_encode($spaceCategory->id)) }}",
                }
            });
        });
    </script>
@endpush
