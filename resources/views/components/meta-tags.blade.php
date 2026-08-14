@section('title', $metaData['metaTitle'])
@section('description', $metaData['metaDescription'])
@section('keywords', $metaData['metaKeywords'])
@section('other_meta_tags')
    {!! $metaData['otherMetaTags'] !!}
@endsection
