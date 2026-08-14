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
    <main id="pageWrapper" class="refundPage">
        <section id="refund">
            <div class="container">
                <div class="contenBx">
                    <div class="mainTile center">{{ $cms->title }}</div>
                    {!! $cms->content !!}
                </div>
            </div>
        </section>
    </main>
@endsection
