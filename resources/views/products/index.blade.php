@extends('layouts.app')
<x-meta-tags :metaData="@$pageDetails" />
@push('css')
    <!-- SWIPER -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
@endpush
@section('content')
    <main id="pageWrapper" class="productPage">
        @if ($pageDetails)
            <section id="ProductHero">
                <img src="{{ $pageDetails['image'] }}" width="1920" height="1080" loading="lazy"
                    alt="{{ $pageDetails['alt'] }}" class="dsk">
                <img src="{{ $pageDetails['mobile_image'] }}" alt="{{ $pageDetails['alt'] }}" class="mob">
            </section>
        @endif

        <section id="ProductListSection">
            <div class="filterWrap" id="FilterWrap">
                <div class="filterWrapBx">
                    <div class="container">
                        <ul class="filterFlx">
                            <li>
                                <label class="filterBxWrap">
                                    <input name="resetFilter" type="checkbox">
                                    <div class="filterBx reset">
                                        <div class="cntWrap">
                                            <span>All Products</span>
                                        </div>
                                    </div>
                                </label>
                            </li>
                            @foreach ($categories as $category)
                                <li>
                                    <label class="filterBxWrap">
                                        <input name="category[]" class="categoryFilter" type="checkbox"
                                            value="{{ $category->id }}">
                                        <div class="filterBx">
                                            <div class="cntWrap">
                                                <span>{{ $category->name }}</span>
                                                <span class="close">
                                                    <img src="{{ asset('frontend/images/icon-close-filter.svg') }}"
                                                        alt="clode filter" width="10" height="10">
                                                </span>
                                            </div>
                                        </div>
                                    </label>
                                </li>
                            @endforeach
                            <li>
                                <div class="filterBx">
                                    <div class="cntWrap">
                                        <span>
                                            <label class="toggleSwitch" for="bestsellerToggle">
                                                <input name="bestsellerToggle" class="categoryFilter" type="checkbox"
                                                    id="bestsellerToggle">
                                                <div></div>
                                            </label>
                                        </span>
                                        <span>Bestsellers</span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="dFlx" id="ProductList">
                    @include('products.partials.listing')
                </div>
            </div>
        </section>

    </main>
@endsection
@push('js')
    <!-- SWIPER -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // FILTER_SCROLL_SLIDER
            let isDown = false;
            let startX;
            let scrollLeft;
            const slider = document.querySelector('.filterFlx');

            const end = () => {
                isDown = false;
                slider.classList.remove('active');
            }

            const start = (e) => {
                isDown = true;
                slider.classList.add('active');
                startX = e.pageX || e.touches[0].pageX - slider.offsetLeft;
                scrollLeft = slider.scrollLeft;
            }

            const move = (e) => {
                if (!isDown) return;

                e.preventDefault();
                const x = e.pageX || e.touches[0].pageX - slider.offsetLeft;
                const dist = (x - startX);
                slider.scrollLeft = scrollLeft - dist;
            }

            (() => {
                slider.addEventListener('mousedown', start);
                slider.addEventListener('touchstart', start);

                slider.addEventListener('mousemove', move);
                slider.addEventListener('touchmove', move);

                slider.addEventListener('mouseleave', end);
                slider.addEventListener('mouseup', end);
                slider.addEventListener('touchend', end);
            })();

            // RESET CHECKBOX FILTER
            $(document).ready(function() {
                $("input[name='resetFilter']").on("change", function() {
                    if ($(this).is(":checked")) {
                        $(this)
                            .closest("li") // go up to current <li>
                            .siblings() // get other <li>
                            .find("input[type='checkbox']")
                            .prop("checked", false);
                    }

                    $("input[name='resetFilter']").prop("checked", false);

                    resetAndLoadProducts();
                });
            });


            const target_1 = document.getElementById("FilterWrap");
            const productSection = document.getElementById("ProductListSection");
            const threshold = 500;
            let prevScroll = window.scrollY || document.documentElement.scrollTop;

            // Track whether we're inside #ProductListSection
            let inProductSection = false;

            // IntersectionObserver to detect when #ProductListSection is in viewport
            const observer = new IntersectionObserver(
                (entries) => {
                    entries.forEach((entry) => {
                        inProductSection = entry.isIntersecting; // true if visible
                        if (!inProductSection) {
                            // remove sticky & hide when leaving section
                            target_1.classList.remove("sticky", "hide");
                        }
                    });
                }, {
                    threshold: 0.1
                } // 10% visible counts as "inside"
            );

            observer.observe(productSection);

            window.addEventListener("scroll", () => {
                if (!inProductSection) return; // only work inside section

                const curScroll = window.scrollY || document.documentElement.scrollTop;

                if (curScroll > threshold) {
                    target_1.classList.add("sticky");

                    if (curScroll > prevScroll) {
                        target_1.classList.add("hide");
                    } else {
                        target_1.classList.remove("hide");
                    }
                } else {
                    target_1.classList.remove("sticky", "hide");
                }

                prevScroll = curScroll;
            });
        });
    </script>
    <script>
        let page = 1;
        let isLoading = false; // prevent duplicate AJAX calls
        let hasMoreProducts = true; // track end of list
        let scrollTimeout;

        $(document).ready(function() {
            $(window).on('scroll', debounce(handleScroll, 200)); // smoother scrolling
        });

        $(document).on('click change', '.categoryFilter, #bestsellerToggle', function() {
            resetAndLoadProducts();
        });

        var LoadingSpinner =
            '<div id="loadingSpinner" class="col-12 text-center py-3">Loading...</div>';

        function loadProducts() {
            if (isLoading || !hasMoreProducts) return;

            isLoading = true;
            $.ajax({
                type: "get",
                url: "{{ route('product-listing') }}",
                data: {
                    page: page,
                    categories: getSelectedCategories(),
                    bestseller: isBestsellerChecked()
                },
                beforeSend: function() {
                    if (page === 1) {
                        $('#ProductList').html(LoadingSpinner);
                    } else {
                        $('#ProductList').append(LoadingSpinner);
                    }
                },
                success: function(response) {
                    $('#loadingSpinner').remove();
                    const trimmed = response.trim();

                    if (trimmed.length) {
                        $('#ProductList').append(trimmed);
                    } else {
                        hasMoreProducts = false;
                        $(window).off('scroll', handleScroll);
                    }
                },
                complete: function() {
                    isLoading = false;
                },
                error: function() {
                    $('#loadingSpinner').remove();
                    isLoading = false;
                }
            });
        }

        function getSelectedCategories() {
            return $("input[name='category[]']:checked").map(function() {
                return $(this).val();
            }).get();
        }

        function isBestsellerChecked() {
            return $("#bestsellerToggle").is(":checked") ? 1 : 0;
        }

        function resetAndLoadProducts() {
            page = 1;
            hasMoreProducts = true;
            $(window).off('scroll', handleScroll);
            loadProducts();
            $(window).on('scroll', debounce(handleScroll, 200));
        }

        function handleScroll() {
            if ($(window).scrollTop() + $(window).height() >= $(document).height() - $('#Footer').height()) {
                page++;
                loadProducts();
            }
        }

        // Simple debounce to limit scroll triggers
        function debounce(func, delay) {
            return function() {
                clearTimeout(scrollTimeout);
                scrollTimeout = setTimeout(func, delay);
            };
        }
    </script>
@endpush
