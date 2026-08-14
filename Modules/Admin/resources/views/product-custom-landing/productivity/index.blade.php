@extends('admin::layouts.app')
@section('title', 'PCL Productivity')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('product-custom-landings.index') }}">
            Product Custom Landings
        </a>
    </li>
@endsection
@section('buttons')
    <a href="{{ route('product-custom-landings.productivity.create', ['product_custom_landing' => base64_encode($productCustomLanding->id)]) }}"
        class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Create
    </a>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $productCustomLanding->title }} - Productivity</h3>
                </div>
                <div class="card-body">
                    <table id="dataTable" class="table table-bordered dt-responsive" style="width:100%">
                        <thead>
                            <tr>
                                <th>SN</th>
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
                        data: 'title',
                        name: 'title'
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
                    url: "{{ route('product-custom-landings.productivity.index', ['product_custom_landing' => base64_encode($productCustomLanding->id)]) }}",
                }
            });
        });
    </script>
@endpush
