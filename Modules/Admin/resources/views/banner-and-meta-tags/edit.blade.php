@extends('admin::layouts.app')
@section('title', 'Edit Banner And Meta Tags: ' . $bannerAndMetaTag->page_value)
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('banner-and-meta-tags.index') }}">
            Banner And Meta Tags
        </a>
    </li>
@endsection
@section('content')
    <!-- form start -->
    <form method="POST" action="{{ route('banner-and-meta-tags.update', base64_encode($bannerAndMetaTag->id)) }}"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <x-admin::action-buttons :cancel-url="route('banner-and-meta-tags.index')" save-label="Update" />
            @if ($bannerAndMetaTag->has_banner)
                <x-admin::banner :banner-data="$bannerAndMetaTag" />
            @endif
            <x-admin::meta-tags :meta-data="$bannerAndMetaTag" />
        </div>
    </form>
    @if ($bannerAndMetaTag->has_banner)
        @include('admin::partials.crop-modal')
    @endif
@endsection
@if ($bannerAndMetaTag->has_banner)
    @include('admin::partials.crop-filepond-setup')
    @include('admin::partials.filepond-setup', ['mediaSource' => $bannerAndMetaTag ?? null])
@endif
@include('admin::partials.choices-setup')
@include('admin::partials.jquery-validate-setup')
@push('js')
    <script>
        $(document).ready(function() {
            $('form').customValidate({
                successRoute: "{{ route('banner-and-meta-tags.index') }}"
            });
        });
    </script>
@endpush
