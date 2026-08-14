@extends('admin::layouts.app')
@section('title', 'Show Blog')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('blogs.index') }}">
            Blogs
        </a>
    </li>
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="mt-5">
                <div class="d-flex align-items-start">
                    <div class="flex-grow-1 overflow-hidden">
                        <h5 class="font-size-15 text-truncate">
                            {{ $blog->title }}
                        </h5>
                        <p class="font-size-13 text-muted mb-0">
                            {{ $blog->published_on_value }}
                        </p>
                    </div>
                </div>
                <div class="position-relative mt-3">
                    <img src="{{ $blog->image_value }}" alt="{{ $blog->image_alt_text_value }}" class="img-thumbnail">
                </div>
                <div class="pt-3">
                    {!! $blog->content !!}
                </div>
            </div>
        </div>
    </div>
@endsection
