@extends('layouts.app')
<x-meta-tags :metaData="@$meta" />
@push('css')
@endpush
@section('content')
    <main id="pageWrapper" class="blogPage">
        @if ($featuredArticle != null)
            <section id="pageBanner">
                <div class="container">
                    <div class="bannerBx">
                        <img src="{{ $featuredArticle->image_value }}" width="1350" height="480" loading="lazy"
                            alt="{{ $featuredArticle->image_alt_text_value }}">
                        <div class="contentBx">
                            <div class="tileTag">FEATURED ARTICLE</div>
                            <div class="mainTitle">{{ $featuredArticle->title }}</div>
                            <p>{{ $featuredArticle->short_content }}</p>
                            <a href="{{ route('blogArticle', ['slug' => $featuredArticle->slug]) }}" class="readMore"
                                aria-label="readmore">Read More</a>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        <section id="blogList">
            <div class="container">
                <div class="tleWrap center">
                    <div class="mTle text-black">Blogs</div>
                </div>
                <div class="flexWrap">
                    @include('partials.blog-list')
                </div>
                @if ($blogs->hasMorePages())
                    <button class="loadmoreBtn" id="loadMore">Load More</button>
                @endif
            </div>
        </section>
    </main>
@endsection
@push('js')
    <script>
        let page = 1;
        let loading = false;

        $('#loadMore').on('click', function() {
            if (!loading) {
                page++;
                loadMoreBlogs();
            }
        });

        function loadMoreBlogs() {
            loading = true;
            $.ajax({
                url: "{{ route('blog') }}",
                type: 'GET',
                data: {
                    page: page
                },
                success: function(response) {
                    $('.flexWrap').append(response.html);
                    loading = false;
                    if (response.isLastPage) {
                        $('#loadMore').hide();
                    }
                }
            });
        }
    </script>
@endpush
