@extends('admin::layouts.app')
@section('title', 'Success Stories')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('success-story-categories.index') }}">
            Success Story Categories
        </a>
    </li>
@endsection
@section('buttons')
    <a href="{{ route('success-story-categories.success-stories.create', base64_encode($successStoryCategory->id)) }}"
        class="btn btn-primary btn-sm">
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
                    url: "{{ route('success-story-categories.success-stories.index', base64_encode($successStoryCategory->id)) }}",
                }
            });
        });
    </script>
@endpush
