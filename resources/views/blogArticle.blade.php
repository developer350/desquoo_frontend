@extends('layouts.app')
<x-meta-tags :metaData="[
    'metaTitle' => $blog->meta_title ?? $blog->title,
    'metaKeywords' => $blog->meta_keywords ?? '',
    'metaDescription' => $blog->meta_description ?? '',
    'otherMetaTags' => $blog->other_meta_tags ?? '',
]" />
@section('image', $blog->image_value)
@push('css')
@endpush
@section('content')
    <main id="pageWrapper" class="blogPage">
        <section id="blogDetails">
            <div class="container">
                <div class="wrapBx">
                    @if ($blog->category != null)
                        <div class="tileWrap">
                            <div class="subT">BLOG</div>
                            <div class="subT">{{ $blog->category }}</div>
                        </div>
                    @endif
                    <div class="mainTiltle">{{ $blog->title }}</div>
                    <div class="subTitle">{{ $blog->sub_title }}</div>
                    <p>{{ $blog->short_content }}</p>
                    <div class="imgBx">
                        <img src="{{ $blog->image_value }}" width="890" height="480" class="lazy" loading="lazy"
                            alt="{{ $blog->image_alt_text_value }}">
                    </div>
                    {!! $blog->content !!}

                    <div class="arrowSec">
                        @if ($blog->backBlog != null)
                            <a href="{{ route('blogArticle', ['slug' => $blog->backBlog->slug]) }}" class="BackBtn">
                                <div class="icon">
                                    <svg viewBox="0 0 24 24" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M22.5 12.0009C22.5 11.802 22.421 11.6113 22.2803 11.4706C22.1397 11.33 21.9489 11.2509 21.75 11.2509H4.0605L8.781 6.53195C8.92183 6.39112 9.00095 6.20011 9.00095 6.00095C9.00095 5.80178 8.92183 5.61078 8.781 5.46995C8.64017 5.32912 8.44916 5.25 8.25 5.25C8.05084 5.25 7.85983 5.32912 7.719 5.46995L1.719 11.4699C1.64915 11.5396 1.59374 11.6224 1.55593 11.7135C1.51812 11.8046 1.49866 11.9023 1.49866 12.0009C1.49866 12.0996 1.51812 12.1973 1.55593 12.2884C1.59374 12.3795 1.64915 12.4623 1.719 12.5319L7.719 18.5319C7.85983 18.6728 8.05084 18.7519 8.25 18.7519C8.44916 18.7519 8.64017 18.6728 8.781 18.5319C8.92183 18.3911 9.00095 18.2001 9.00095 18.0009C9.00095 17.8018 8.92183 17.6108 8.781 17.4699L4.0605 12.7509H21.75C21.9489 12.7509 22.1397 12.6719 22.2803 12.5313C22.421 12.3906 22.5 12.1999 22.5 12.0009Z"
                                            fill="#FF5940" />
                                    </svg>
                                </div>
                                <span>Back</span>
                            </a>
                        @endif

                        @if ($blog->nextBlog != null)
                            <a href="{{ route('blogArticle', ['slug' => $blog->nextBlog->slug]) }}" class="BackBtn">
                                <span>Next</span>
                                <div class="icon">
                                    <svg viewBox="0 0 24 24" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M1.5 12.0009C1.5 11.802 1.57902 11.6113 1.71967 11.4706C1.86032 11.33 2.05109 11.2509 2.25 11.2509H19.9395L15.219 6.53195C15.0782 6.39112 14.9991 6.20011 14.9991 6.00095C14.9991 5.80178 15.0782 5.61078 15.219 5.46995C15.3598 5.32912 15.5508 5.25 15.75 5.25C15.9492 5.25 16.1402 5.32912 16.281 5.46995L22.281 11.4699C22.3508 11.5396 22.4063 11.6224 22.4441 11.7135C22.4819 11.8046 22.5013 11.9023 22.5013 12.0009C22.5013 12.0996 22.4819 12.1973 22.4441 12.2884C22.4063 12.3795 22.3508 12.4623 22.281 12.5319L16.281 18.5319C16.1402 18.6728 15.9492 18.7519 15.75 18.7519C15.5508 18.7519 15.3598 18.6728 15.219 18.5319C15.0782 18.3911 14.9991 18.2001 14.9991 18.0009C14.9991 17.8018 15.0782 17.6108 15.219 17.4699L19.9395 12.7509H2.25C2.05109 12.7509 1.86032 12.6719 1.71967 12.5313C1.57902 12.3906 1.5 12.1999 1.5 12.0009Z"
                                            fill="#FF5940" />
                                    </svg>
                                </div>
                            </a>
                        @endif
                    </div>
                </div>

            </div>
        </section>

        <section id="commentSec">
            <div class="container">
                <div class="titleWrap">
                    <div class="mainTitle">Leave a Comment</div>
                    <div class="subT">You must be logged in to post a comment.</div>
                </div>
                <div class="enquiryFormWrap">
                    <form action="{{ route('blog-comment') }}" method="POST" id="blogCommentForm">
                        @csrf
                        @honeypot
                        <input type="hidden" name="blog_id" value="{{ $blog->id }}">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group jqv-group">
                                    <input type="text" name="comment" class="form-control" placeholder="Comment" required
                                        data-rule-minlength="3" data-rule-maxlength="1000"
                                        data-msg-required="Please enter a comment" data-rule-validMessage="true">
                                    <div class="help-block danger"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group jqv-group">
                                    <div class="inputWrap hasIcon">
                                        <input type="text" name="name" id="name" class="form-control"
                                            placeholder="Name" required data-rule-validName="true"
                                            data-msg-required="Please enter a valid name" data-rule-minlength="2"
                                            data-rule-maxlength="191">
                                    </div>
                                    <div class="help-block danger"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group jqv-group">
                                    <div class="inputWrap hasIcon">
                                        <input type="email" name="email" class="form-control" placeholder="Work Email"
                                            required data-rule-maxlength="191" data-rule-emailOnly="true"
                                            data-msg-required="Please enter a valid email">
                                        <div class="icon">
                                            <img src="{{ asset('frontend/images/icon-form-mail.svg') }}" width="14"
                                                height="14" loading="lazy" alt="icon-form-mail">
                                        </div>
                                    </div>
                                    <div class="help-block danger"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="btnWrap">
                                    <button type="submit" class="baseBtn_1 hoveranim" aria-label="submit">
                                        <span>Post a Comment</span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="txt">Talk to us at <a
                                        href="tel:{{ app('siteSettings')->formatted_phone_number }}">{{ app('siteSettings')->phone_number }}</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </section>

        @if ($relatedBlogs->isNotEmpty())
            <section id="relatedArticle">
                <div class="container">
                    <div class="titleWrap">
                        <div class="mainTitle">More related articles</div>
                    </div>
                    <div class="flxWrap">
                        @include('partials.blog-list', ['blogs' => $relatedBlogs])
                    </div>
                </div>
            </section>
        @endif
    </main>
@endsection
@include('js.jquery-validate')
@push('js')
    <script>
        setupValidation('#blogCommentForm');
    </script>
@endpush
