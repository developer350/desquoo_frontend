@extends('admin::layouts.app')
@section('title', 'Show Blog Comment')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('blog-comments.index') }}">
            Blog Comments
        </a>
    </li>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="pb-3">
                        <div class="row">
                            <div class="col-xl-2">
                                <div>
                                    <h5 class="font-size-15">Blog :</h5>
                                </div>
                            </div>
                            <div class="col-xl">
                                <div class="text-muted">
                                    {{ $blogComment->blog?->title }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pb-3">
                        <div class="row">
                            <div class="col-xl-2">
                                <div>
                                    <h5 class="font-size-15">Name :</h5>
                                </div>
                            </div>
                            <div class="col-xl">
                                <div class="text-muted">
                                    {{ $blogComment->name }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pb-3">
                        <div class="row">
                            <div class="col-xl-2">
                                <div>
                                    <h5 class="font-size-15">Email :</h5>
                                </div>
                            </div>
                            <div class="col-xl">
                                <div class="text-muted">
                                    {{ $blogComment->email }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pb-3">
                        <div class="row">
                            <div class="col-xl-2">
                                <div>
                                    <h5 class="font-size-15">Comment :</h5>
                                </div>
                            </div>
                            <div class="col-xl">
                                <div class="text-muted">
                                    {{ $blogComment->comment }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pb-3">
                        <div class="row">
                            <div class="col-xl-2">
                                <div>
                                    <h5 class="font-size-15">Date :</h5>
                                </div>
                            </div>
                            <div class="col-xl">
                                <div class="text-muted">
                                    {{ $blogComment->date_formatted }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
