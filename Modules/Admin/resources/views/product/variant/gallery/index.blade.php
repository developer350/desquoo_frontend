@extends('admin::layouts.app')
@section('title', 'Product Variant Gallery')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('products.index') }}">
            Products
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('products.variants.index', base64_encode($productVariant->product->id)) }}">
            Product Variants
        </a>
    </li>
@endsection
@section('buttons')
    <a href="{{ route('product-variants.galleries.create', base64_encode($productVariant->id)) }}"
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
                                <th>Media Type</th>
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
                        data: 'media_type',
                        name: 'media_type'
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
                    url: "{{ route('product-variants.galleries.index', base64_encode($productVariant->id)) }}",
                }
            });
        });
    </script>
@endpush
