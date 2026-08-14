@extends('admin::layouts.app')
@section('title', 'Attributes')
@section('buttons')
    <a href="{{ route('attributes.create') }}" class="btn btn-primary btn-sm">
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
                                <th>Name</th>
                                <th>Values</th>
                                <th>Default Listing <br> Attribute</th>
                                <th>Main Attribute</th>
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
                        data: 'name',
                        name: 'name',
                        render: data => formatData(data)

                    },
                    {
                        data: 'values',
                        name: 'values.value',
                        render: data => formatData(data),
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'default_listing_attribute',
                        name: 'default_listing_attribute',
                        render: function(data, type, row) {
                            if (data == 1) {
                                return '<span class="badge bg-success">Yes</span>';
                            } else {
                                return '<span class="badge bg-secondary">No</span>';
                            }
                        },
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'is_main_attribute',
                        name: 'is_main_attribute',
                        render: function(data, type, row) {
                            if (data == 1) {
                                return '<span class="badge bg-success">Yes</span>';
                            } else {
                                return '<span class="badge bg-secondary">No</span>';
                            }
                        },
                        searchable: false,
                        orderable: false
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
                    url: "{{ route('attributes.index') }}",
                }
            });
        });
    </script>
@endpush
