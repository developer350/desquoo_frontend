@extends('admin::layouts.app')
@section('title', 'Product Variants')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('products.index') }}">
            Products
        </a>
    </li>
@endsection
@section('buttons')
    <a href="{{ route('products.variants.create', base64_encode($product->id)) }}" class="btn btn-primary btn-sm">
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
                                <th>Combination</th>
                                <th>SKU</th>
                                <th>Price</th>
                                <th>Offer Price</th>
                                <th>Stock</th>
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
                        data: 'variant',
                        name: 'variant',
                        orderable: false,
                    },
                    {
                        data: 'sku',
                        name: 'sku',
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'offer_price',
                        name: 'offer_price'
                    },
                    {
                        data: 'stock',
                        name: 'stock'
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
                    url: "{{ route('products.variants.index', base64_encode($product->id)) }}",
                }
            });
        });
    </script>
@endpush
