@extends('layouts.app')
@section('title', 'Maintenance')
@push('css')
@endpush
@section('content')
    <main id="pageWrapper" class="errorPage">
        <section id="ErrorSection">
            <div class="container">
                <div class="errorStatusBx">
                    <div class="imgWrap">
                        <img src="{{ asset('frontend/images/icon-404.svg') }}" width="376" height="250" loading="lazy"
                            alt="icon-404">
                    </div>
                    <div class="cntWrap">
                        <div class="tle">The page you're looking for can't be found</div>
                        <div class="txt">Maintenance</div>
                        <div class="btnWrap">
                            <div>
                                <a href="{{ route('home') }}" class="baseBtn_1 hoveranim" aria-label="navigate to home">
                                    <span>Go to Home</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
