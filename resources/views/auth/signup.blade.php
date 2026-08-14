@extends('layouts.app')
<x-meta-tags :metaData="@$meta" />
@push('css')
@endpush
@section('content')
    <main id="pageWrapper" class="authPage">
        <section id="LoginSection">
            <img src="{{ app('siteSettings')->auth_image_value ?? asset('frontend/images/auth-bg-1.jpg') }}" width="1920"
                height="1080" loading="lazy" alt="{{ app('siteSettings')->auth_image_alt_text_value ?? 'auth' }}">
            <div class="container">
                <div class="authBox">
                    <a href="{{ route('home') }}" class="logoWrap">
                        <img src="{{ asset('frontend/images/auth-logo.svg') }}" width="126" height="27" loading="lazy"
                            alt="auth-logo">
                    </a>
                    <div class="mTleWrap">
                        <div class="tle">Sign Up</div>
                        <div class="txt">Already registered?<a href="{{ route('login') }}">Login</a></div>
                    </div>
                    <form method="post" action="{{ route('signup.post') }}" id="signup">
                        @csrf
                        @honeypot
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group sgnup">
                                    <div class="inputWrap hasIcon jqv-group">
                                        <div class="help-block danger"></div>
                                        <div class="div">
                                            <input type="email" name="email" class="form-control" placeholder="Email"
                                                required data-rule-maxlength="191" data-rule-emailOnly="true"
                                                data-msg-required="Enter your email">
                                            <div class="icon">
                                                <img src="{{ asset('frontend/images/l1.svg') }}" width="14"
                                                    height="14" loading="lazy" alt="icon-form-mail">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="inputWrap hasIcon jqv-group">
                                        <div class="div">

                                            <input type="text" name="name" class="form-control" placeholder="Name"
                                                required data-rule-maxlength="191" data-rule-validName="true"
                                                data-msg-required="Enter your name" data-rule-minlength="2">
                                            <div class="icon">
                                                <img src="{{ asset('frontend/images/l2.svg') }}" width="14"
                                                    height="14" loading="lazy" alt="icon-form-mail">
                                            </div>
                                        </div>
                                        <div class="help-block danger"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="btnWrap">
                                    <button type="submit" class="baseBtn_1 hoveranim" aria-label="submit">
                                        <span>Sign Up</span>
                                        <span class="icon">
                                            <img src="{{ asset('frontend/images/icon-arrow-right.svg') }}" width="16"
                                                height="16" loading="lazy" alt="icon-arrow-right">
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
@endsection
@include('js.jquery-validate')
@push('js')
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            // Hide the header
            $('#Header').hide();

            // Hide the footer
            $('#Footer').hide();
        });

        setupValidation('#signup', {}, {}, afterSuccess);

        function afterSuccess(response) {
            if (response.status) {
                window.location.href = response.url;
            }
        }
    </script>
@endpush
