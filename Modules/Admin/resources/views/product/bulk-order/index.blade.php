@extends('admin::layouts.app')
@section('title', 'Product Bulk Orders')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('products.index') }}">
            Products
        </a>
    </li>
@endsection
@section('buttons')
    <a href="{{ route('products.bulk-orders.create', base64_encode($product->id)) }}" class="btn btn-primary btn-sm">
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
                                <th>Title</th>
                                <th>Min Qty</th>
                                <th>Max Qty</th>
                                <th>Discount %</th>
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
                        name: 'title',
                    },
                    {
                        data: 'min_quantity',
                        name: 'min_quantity',
                    },
                    {
                        data: 'max_quantity',
                        name: 'max_quantity',
                    },
                    {
                        data: 'discount_percentage',
                        name: 'discount_percentage'
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
                    url: "{{ route('products.bulk-orders.index', base64_encode($product->id)) }}",
                }
            });
        });
    </script>
@endpush
