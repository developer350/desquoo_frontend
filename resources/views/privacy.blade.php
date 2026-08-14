@extends('layouts.app')
<x-meta-tags :metaData="[
    'metaTitle' => $cms->meta_title ?? $cms->title,
    'metaKeywords' => $cms->meta_keywords ?? '',
    'metaDescription' => $cms->meta_description ?? '',
    'otherMetaTags' => $cms->other_meta_tags ?? '',
]" />
@push('css')
@endpush
@section('content')
    <main id="pageWrapper" class="legalPage">
        <section id="Privacy" class="legalSection">
            <div class="container">
                <div class="contenBx">
                    <h1 class="mainTile center">{{ $cms->title }}</h1>

                    {!! $cms->content !!}
                </div>
            </div>
        </section>
    </main>
@endsection
