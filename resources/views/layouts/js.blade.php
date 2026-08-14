<!-- JQUERY -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

<!-- WOW -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"
    integrity="sha512-Eak/29OTpb36LLo2r47IpVzPBLXnAMPAVypbSZiZ4Qkf8p/7S/XRG5xp7OKWPPYfJT6metI+IORkR5G8F900+g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- BOOTSTRAP -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous" defer>
</script>

<!-- LAZY LOAD -->
<script src="https://cdn.jsdelivr.net/npm/intersection-observer@0.7.0/intersection-observer.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/vanilla-lazyload@17.8.3/dist/lazyload.min.js" defer
    onload="initializeLazyLoad()"></script>

<!-- OWL -->
<noscript>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
</noscript>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const link = document.createElement('link');
        link.href = "https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css";
        link.rel = "stylesheet";
        link.integrity =
            'sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g==';
        link.referrerPolicy = 'no-referrer';
        link.crossOrigin = 'anonymous';
        var appStylesheet = document.getElementById('AppStyle');
        if (appStylesheet !== null && appStylesheet !== undefined && appStylesheet !== '' && appStylesheet
            .length > 0) {
            document.head.insertBefore(link, appStylesheet);
        } else {
            // Fallback: Append to the head if AppStyle doesn't exist
            document.head.appendChild(link);
        }
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"
    integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>


<script>
    function initializeLazyLoad() {
        var lazyLoadInstance = new LazyLoad({});
    }
</script>

<script>
    // WOW
    if ($(".wow").length) {
        var wow = new WOW({
            boxClass: "wow",
            animateClass: "animate__animated",
            mobile: true,
            live: true,
        });
        wow.init();
    }


    $(document).ready(function() {
        // Listen for page scroll
        $(window).on('scroll', function() {
            // Close any open Select2 dropdowns
            $('select').each(function() {
                const $select = $(this);
                if ($select.data('select2')) {
                    $select.select2('close');
                }
            });
        });
    });
</script>
<script>
    // FOOTER
    // document.addEventListener("DOMContentLoaded", function() {
    //     if ($(window).width() <= 870) {
    //         $("#FtAcco .accordion-collapse").removeClass("show");
    //         $("#FtAcco .accordion-button").removeClass("collapsed");
    //         $("#FtAcco .accordion-button").attr("aria-expanded", "false");
    //     }
    // });

    $('.prdSlide').owlCarousel({
        loop: false,
        rewind: false,
        autoplay: false,
        nav: false,
        dots: false,
        items: 3,
        margin: 4,
        responsive: {
            468: {
                margin: 4,
                items: 3,
            },
            768: {
                margin: 4,
                items: 4,
            },
            992: {
                margin: 4,
                items: 5,
            },
            1200: {
                margin: 5,
                items: 5,
            },
            1441: {
                margin: 5,
                items: 6,
            },
            1771: {
                margin: 5,
                items: 7,
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const mainFlx = document.querySelector('.MainFlx');
        const qckMenus = document.querySelectorAll('.QckMenu');

        // Function to add open class
        function addOpenClass() {
            if (mainFlx) {
                mainFlx.classList.add('open');
            }
        }

        // Function to remove open class
        function removeOpenClass() {
            if (mainFlx) {
                mainFlx.classList.remove('open');
            }
        }

        // Loop through all QckMenu containers
        qckMenus.forEach(qckMenu => {
            const dropdowns = qckMenu.querySelectorAll('.dropdown');

            dropdowns.forEach(dropdown => {
                const dropDownMnu = dropdown.querySelector('.dropDownMnu');

                // Only add hover listeners if dropDownMnu exists
                if (dropDownMnu) {
                    dropdown.addEventListener('mouseenter', addOpenClass);
                    dropdown.addEventListener('mouseleave', removeOpenClass);
                }
            });
        });
    });
</script>
<!-- CUSTOM -->
<script src="{{ asset('frontend/js/custom.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function showToast(icon, message) {
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
        Toast.fire({
            icon: icon,
            title: message
        });
    }
</script>

<script>
    $(function($) {
        function handleAjaxSubmit(form) {
            var $form = $(form);
            var $submitBtn = $form.find(":submit");
            var originalText = $submitBtn.html();

            var $errorBlock = $form.find(".error-block");
            var $successBlock = $form.find(".success-block");

            $submitBtn.prop("disabled", true).html("<span>Sending...</span>");
            $errorBlock.addClass("d-none").text("");
            $successBlock.addClass("d-none").text("");

            $.ajax({
                type: "POST",
                url: $form.attr("action"),
                data: new FormData(form),
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status) {
                        $successBlock.removeClass("d-none").text(response.message);
                        form.reset();
                    } else {
                        $errorBlock.removeClass("d-none").text(response.message);
                    }
                    $submitBtn.prop("disabled", false).html(originalText);
                },
                error: function(xhr) {
                    let msg = "Something went wrong. Please try again.";
                    if (xhr.status === 422 && xhr.responseJSON?.message) {
                        msg = xhr.responseJSON.message; // Show "already subscribed"
                    }
                    $errorBlock.removeClass("d-none").text(msg);
                    $submitBtn.prop("disabled", false).html(originalText);
                }
            });
        }

        $('#newsletterEmail').on('change keydown', function() {
            $(this).parent().find(".error-block").addClass("d-none").text("");
            $(this).parent().find(".success-block").addClass("d-none").text("");
            var email = $(this).val().trim();
            var valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
            if (!email || !valid) {
                $(this).parent().find(".error-block").removeClass("d-none").text(
                    "Please enter a valid email.");
                return;
            } else {
                $(this).parent().find(".error-block").addClass("d-none").text("");
            }
        });

        $("#newsletter-form").on("submit", function(e) {
            e.preventDefault();
            var email = $("#newsletterEmail").val().trim();
            var valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
            if (!email || !valid) {
                $(this).parent().find(".error-block").removeClass("d-none").text(
                    "Please enter a valid email.");
                return;
            }
            handleAjaxSubmit(this);
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        const triggers = document.querySelectorAll('.search-trigger');
        const header = document.querySelector("#Header .MainFlx");

        // =====================
        // OPEN SEARCH (CLICK ANYWHERE INSIDE TRIGGER)
        // =====================
        triggers.forEach(trigger => {
            trigger.addEventListener('click', function(e) {

                // prevent closing immediately
                e.stopPropagation();

                triggers.forEach(t => t.classList.add('active'));
                header && header.classList.add('active');

                const input = this.querySelector('.search-input');
                if (input) input.focus();
            });
        });


        // =====================
        // CLOSE BUTTON
        // =====================
        document.querySelectorAll('.close').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                closeSearch();
            });
        });

        // =====================
        // CLICK OUTSIDE CLOSE
        // =====================
        document.addEventListener('click', function() {
            closeSearch();
        });

        // =====================
        // ENTER KEY SEARCH
        // =====================
        document.querySelectorAll('.search-input').forEach(input => {
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    handleSearch(this.value);
                }
            });
        });

        // =====================
        // SUBMIT BUTTON
        // =====================
        document.querySelectorAll('.search-icon.sbmt').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const input = this.closest('.input-holder').querySelector('.search-input');
                if (input) handleSearch(input.value);
            });
        });

        // =====================
        // FUNCTIONS
        // =====================
        function closeSearch() {
            triggers.forEach(t => t.classList.remove('active'));
            header && header.classList.remove('active');
        }

        function handleSearch(query) {
            return;
        }

    });

    //search js
    let searchRequest = null;
    $(document).on('input paste change', '.search-input', function(e) {
        var searchInput = $(this);
        var searchValue = searchInput.val();
        var searchResults = $('.search-results-wrapper');

        if (searchValue.length < 2) {
            searchResults.html('');
            return;
        }

        if (searchRequest) {
            searchRequest.abort();
            searchRequest = null;
        }

        searchRequest = $.ajax({
            type: "post",
            url: "{{ route('search-products') }}",
            data: {
                search: searchValue,
                _token: '{{ csrf_token() }}'
            },
            dataType: "html",
            success: function(response) {
                if (response) {
                    searchResults.html(response);
                }
            }
        });
    });
</script>

<!-- Include cutomize modal-->
@include('modals.cartModal')

@stack('js')
