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
                        <div class="tle">Enter OTP</div>
                        <div class="txt">Enter the OTP that was sent to {{ $maskedEmail }}</div>
                    </div>
                    <form method="POST" action="{{ route('verify.otp') }}" id="OtpForm">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group jqv-group">
                                    <div class="inputWrap hasIcon">
                                        <input type="number" class="form-control" placeholder="OTP" required minlength="4"
                                            maxlength="4" name="otp" inputmode="numeric" data-msg-required="Enter OTP">
                                        <div class="icon">
                                            <img src="{{ asset('frontend/images/l3.svg') }}" width="14" height="14"
                                                loading="lazy" alt="icon-form-mail">
                                        </div>
                                    </div>
                                    <div class="help-block danger"></div>
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
                                <div class="txt" id="countdownOtpSection">Resend OTP In <a href="javascript:void(0);"
                                        id="timer">00:30</a></div>
                                <div class="txt d-none" id="resendSection">Didn't receive OTP?<a href="javascript:void(0);"
                                        onclick="resendOtp()">Resend</a></div>
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

        let timeout;
        $(document).ready(function() {
            startCountdown(30, '#timer');
        });

        function startCountdown(seconds, counterSelector) {
            function tick() {
                $(counterSelector).text(`0:${seconds < 10 ? '0' : ''}${seconds}`);
                if (--seconds < 0) {
                    $('#resendSection').removeClass('d-none');
                    $('#countdownOtpSection').addClass('d-none');
                } else {
                    setTimeout(tick, 1000);
                }
            }
            tick();
        }

        function resendOtp() {
            $.ajax({
                type: "post",
                url: "{{ route('resend.otp') }}",
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status) {
                        startCountdown(30, '#timer');
                        $('#resendSection').addClass('d-none');
                        $('#countdownOtpSection').removeClass('d-none');
                    } else {
                        showToast('error', response.message);
                    }
                }
            });
        }

        setupValidation('#OtpForm', {}, {}, afterSuccess);

        function afterSuccess(response) {
            if (response.status) {
                window.location.href = response.url;
            }
        }

        $('[name="otp"]').on('input', function() {
            if ($(this).val().length == 4) {
                $('#OtpForm').submit();
            }
        });
    </script>
@endpush
