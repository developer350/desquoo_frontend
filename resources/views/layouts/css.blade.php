<!-- Allow additional CSS to be pushed from specific views -->
<link rel="shortcut icon" href="{{ asset('frontend/images/favicon.ico') }}" type="image/x-icon">
<link rel="icon" href="{{ asset('frontend/images/favicon.ico') }}" type="image/x-icon">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('frontend/images/android-chrome-192x192.png') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('frontend/images/android-chrome-512x512.png') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('frontend/images/apple-touch-icon.png') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('frontend/images/favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('frontend/images/favicon-16x16.png') }}">

{{-- <link rel="manifest" href="{{ asset('site.webmanifest') }}"> --}}

<style>
    /* Inline critical CSS here */
    .owl-carousel:not(.owl-loaded) {
        opacity: 0;
    }

    figure {
        margin: 0 !Important;
    }

    #preloader {
        width: 100%;
        height: 100%;
        position: fixed;
        z-index: 999999;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.6s ease;
        pointer-events: none;
    }

    #preloader.hiding {
        opacity: 0;
        visibility: hidden;
    }

    #preloader .circle {
        position: absolute;
        width: 200px;
        height: 200px;
        background: #fff;
        border-radius: 50%;
        box-shadow: 0 0 0 100vw #010100;
        transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        will-change: transform;
    }

    #preloader .circle.scale {
        transform: scale(3);
    }

    #preloader .logo {
        width: 175px;
        height: auto;
        z-index: 1;
        position: relative;
    }

    /* .logo.fade {
        opacity: 0;
    } */
</style>

<script>
    // VIEWPORT HEIGHT
    const appHeight = () => document.documentElement.style.setProperty('--app-height', `${window.innerHeight}px`)
    window.addEventListener('resize', function() {
        appHeight();
    });
    document.addEventListener("DOMContentLoaded", function() {
        appHeight();
    });

    (function() {
        document.querySelectorAll('.no-js').forEach(function(element) {
            element.classList.remove('no-js');
        });
    })();

    //SITE LOADER
    function hidePreloader() {
        const preloader = document.getElementById("preloader");
        const circle = document.querySelector(".circle");
        const logo = document.querySelector(".logo");

        // Then start the circle scaling
        setTimeout(() => {
            circle.classList.add("scale");
        }, 200);

        // Start preloader fade out
        setTimeout(() => {
            preloader.classList.add("hiding");
        }, 400);

        // Remove preloader and re-enable interactions
        setTimeout(() => {
            preloader.style.display = "none";
            document.body.style.overflow = "auto";
        }, 1000);
    }

    if (document.readyState === 'loading') {
        document.addEventListener("DOMContentLoaded", hidePreloader);
    } else {
        hidePreloader();
    }
</script>


<!-- PRECONNECTING MOST USED DOMAIN -->
<link rel="preconnect" href="https://cdnjs.cloudflare.com">
<link rel="preconnect" href="https://ux.intersmarthosting.in">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
    href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
    rel="stylesheet">
<!-- FONTS -->


<script>
    document.addEventListener("DOMContentLoaded", function() {
        var link = document.createElement('link');
        link.href = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css';
        link.rel = 'stylesheet';
        link.integrity =
            'sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN';
        link.crossOrigin = 'anonymous';
        link.referrerPolicy = 'no-referrer';
        var appStylesheet = document.getElementById('AppStyle');
        document.head.insertBefore(link, appStylesheet);
    });
</script>
<!-- ANIMATE CSS -->
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" media="print"
    onload="this.media='all'">
<noscript>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
</noscript>


<link id="AppStyle" rel="stylesheet" href="{{ asset('frontend/css/app.min.css') }}">
<link id="AppStyle1" rel="stylesheet" href="{{ asset('frontend/css/stepper.min.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/pages.min.css') }}">
{{-- payment gateway css --}}
<style>
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 999999;
    }

    .loading-content {
        background: white;
        padding: 40px;
        border-radius: 16px;
        text-align: center;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
    }

    .spinner {
        width: 50px;
        height: 50px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #0070ba;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 20px;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .swal2-container {
        z-index: 1000000 !important;
    }
</style>
@stack('css')
