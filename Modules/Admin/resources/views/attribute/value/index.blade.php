@extends('admin::layouts.app')
@section('title', 'Attribute Values')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('attributes.index') }}">
            Attributes
        </a>
    </li>
@endsection
@section('buttons')
    <button type="button" class="btn btn-primary btn-sm form-modal-btn"
        data-form-url="{{ route('attributes.values.create', base64_encode($attribute->id)) }}"
        data-redirect-url="{{ route('attributes.values.index', base64_encode($attribute->id)) }}"
        data-target=".attribute-value-modal" data-after-modal-show="initializeCropModal">
        <i class="fas fa-plus"></i> Create
    </button>
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
                                <th>Value</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade attribute-value-modal" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            </div>
        </div>
    </div>
    @include('admin::partials.crop-modal')
@endsection
@include('admin::partials.data-tables-setup')
@include('admin::partials.sweet-alert-setup')
@include('admin::partials.jquery-validate-setup')
@include('admin::partials.crop-filepond-setup')
@include('admin::partials.filepond-setup', ['mediaSource' => null])
@push('js')
    <script>
        function initializeCropModal() {
            croppedInput();
        }

        $(document).ready(function() {
            initializeDataTable({
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'value',
                        name: 'value',
                        render: data => formatData(data)

                    },
                    {
                        data: 'action',
                        name: 'action',
                        searchable: false,
                        orderable: false
                    }
                ],
                ajaxOptions: {
                    url: "{{ route('attributes.values.index', base64_encode($attribute->id)) }}",
                }
            });
        });
    </script>
@endpush
