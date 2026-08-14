<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', config('app.name'))</title>
    <!-- Include meta tags for SEO and social media -->
    @include('layouts.meta')
    <!-- Include CSS stylesheets -->
    @include('layouts.css')
</head>

<body class="{{ request()->routeIs('home') ? 'isHome' : '' }} {{ request()->routeIs('custom-page') ? 'smartDesk' : '' }}">
    <!-- Include header -->
    <div id="preloader" style=" ">
        <div class="circle">
        </div>
        <div class="logo" style="text-align: center">
            <img loading="lazy" decoding="async" class="logo" src="{{ asset('frontend/images/fLogo.png') }}"
                style="width: 140px;height: auto; z-index: 1; text-align: center;" width="140" height="100" alt="logo">
        </div>
    </div>
    <div id="viewport">
        <!-- Include header -->
        @include('layouts.header')
        <!-- Main content area -->
        @yield('content')
    </div>
    <!-- Include footer -->
    @include('layouts.footer')

    <!-- Include JavaScript files -->
    @include('layouts.js')
</body>

</html>
