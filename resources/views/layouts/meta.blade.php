@env('local')
    <meta name="robots" content="noindex, nofollow, max-image-preview:large">
@endenv
<meta name="author" content="INTER SMART | Web Design &amp; Development Company | Kerala">
<meta name="title" content="@yield('title')">
<meta name="keywords" content="@yield('keywords')">
<meta name="description" content="@yield('description')">
<meta property="og:locale" content="en_US">
<meta property="og:type" content="website">
<meta property="og:image" content="@yield('image', asset('frontend/images/Logo.png'))" />
<meta property="og:image:width" content="734">
<meta property="og:image:height" content="491">
<meta property="og:image:type" content="image/jpg">
<meta property="og:title" content="@yield('title')">
<meta property="og:description" content="@yield('description')">
<meta property="og:url" content="{{ url()->current() }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="canonical" href="{{ url()->current() }}">

<meta property="twitter:card" content="summary_large_image" />
<meta property="twitter:url" content="{{ url()->current() }}" />
<meta property="twitter:title" content="@yield('title')" />
<meta property="twitter:description" content="@yield('description')" />
<meta property="twitter:image" content="@yield('image', asset('frontend/images/Logo.png'))" />

<meta name="theme-color" media="(prefers-color-scheme: light)" content="#010100">
<meta name="theme-color" media="(prefers-color-scheme: dark)" content="#010100">
<meta name="msapplication-TileColor" content="#010100">
<meta name="msapplication-navbutton-color" content="#010100">
<meta name="apple-mobile-web-app-status-bar-style" content="#010100">

@yield('other_meta_tags')
