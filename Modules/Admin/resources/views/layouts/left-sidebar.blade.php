<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">

                <li class="{{ request()->routeIs('dashboard.index') ? 'mm-active' : '' }}">
                    <a href="{{ route('dashboard.index') }}"
                        class="{{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                        <i data-feather="grid"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                @if (auth('admin')->user()->can('orders'))
                    <li class="{{ request()->routeIs('orders.*') ? 'mm-active' : '' }}">
                        <a href="{{ route('orders.index') }}"
                            class="{{ request()->routeIs('orders.*') ? 'active' : '' }}">
                            <i data-feather="shopping-cart"></i>
                            <span>Orders</span>
                        </a>
                    </li>
                @endif

                @if (auth('admin')->user()->canAny(['products', 'categories', 'attributes']))
                    @php
                        $catalogActive = request()->routeIs(['products.*', 'categories.*', 'attributes.*']);
                    @endphp

                    <li class="{{ $catalogActive ? 'mm-active' : '' }}">
                        <a href="javascript:void(0);" class="has-arrow {{ $catalogActive ? 'mm-active' : '' }}">
                            <i data-feather="box"></i>
                            <span>Catalog</span>
                        </a>

                        <ul class="sub-menu {{ $catalogActive ? 'mm-show' : '' }}" aria-expanded="false">
                            @if (auth('admin')->user()->can('products'))
                                <li class="{{ request()->routeIs('products.*') ? 'mm-active' : '' }}">
                                    <a href="{{ route('products.index') }}"
                                        class="{{ request()->routeIs('products.*') ? 'active' : '' }}">
                                        Products
                                    </a>
                                </li>
                            @endif

                            @if (auth('admin')->user()->can('categories'))
                                <li class="{{ request()->routeIs('categories.*') ? 'mm-active' : '' }}">
                                    <a href="{{ route('categories.index') }}"
                                        class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">
                                        Categories
                                    </a>
                                </li>
                            @endif

                            @if (auth('admin')->user()->can('attributes'))
                                <li class="{{ request()->routeIs('attributes.*') ? 'mm-active' : '' }}">
                                    <a href="{{ route('attributes.index') }}"
                                        class="{{ request()->routeIs('attributes.*') ? 'active' : '' }}">
                                        Attributes
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (auth('admin')->user()->can('enquiries'))
                    @php
                        $enquiryActive = request()->routeIs([
                            'office-enquiries.*',
                            'bulk-order-enquiries.*',
                            'newsletter-subscriptions.*',
                            'blog-comments.*',
                            'visit-enquiries.*',
                            'got-a-question-enquiries.*',
                        ]);
                    @endphp

                    <li class="{{ $enquiryActive ? 'mm-active' : '' }}">
                        <a href="javascript:void(0);" class="has-arrow {{ $enquiryActive ? 'mm-active' : '' }}">
                            <i data-feather="help-circle"></i>
                            <span>
                                Enquiries
                                @if ($hasNewEnquiry)
                                    <span class="badge rounded-pill bg-danger">New</span>
                                @endif
                            </span>
                        </a>

                        <ul class="sub-menu {{ $enquiryActive ? 'mm-show' : '' }}" aria-expanded="false">
                            <li class="{{ request()->routeIs('got-a-question-enquiries.*') ? 'mm-active' : '' }}">
                                <a href="{{ route('got-a-question-enquiries.index') }}"
                                    class="{{ request()->routeIs('got-a-question-enquiries.*') ? 'active' : '' }}">
                                    <span>Got a Question Enquiries
                                        @if ($enquiryCounts['got_a_question'] > 0)
                                            <span class="badge rounded-pill bg-danger">New</span>
                                        @endif
                                    </span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('visit-enquiries.*') ? 'mm-active' : '' }}">
                                <a href="{{ route('visit-enquiries.index') }}"
                                    class="{{ request()->routeIs('visit-enquiries.*') ? 'active' : '' }}">
                                    <span>Visit Enquiries
                                        @if ($enquiryCounts['visit'] > 0)
                                            <span class="badge rounded-pill bg-danger">New</span>
                                        @endif
                                    </span>
                                </a>
                            </li>

                            <li class="{{ request()->routeIs('office-enquiries.*') ? 'mm-active' : '' }}">
                                <a href="{{ route('office-enquiries.index') }}"
                                    class="{{ request()->routeIs('office-enquiries.*') ? 'active' : '' }}">
                                    <span>Office
                                        @if ($enquiryCounts['office'] > 0)
                                            <span class="badge rounded-pill bg-danger">New</span>
                                        @endif
                                    </span>
                                </a>
                            </li>

                            <li class="{{ request()->routeIs('bulk-order-enquiries.*') ? 'mm-active' : '' }}">
                                <a href="{{ route('bulk-order-enquiries.index') }}"
                                    class="{{ request()->routeIs('bulk-order-enquiries.*') ? 'active' : '' }}">
                                    <span>Bulk Order
                                        @if ($enquiryCounts['bulk_order'] > 0)
                                            <span class="badge rounded-pill bg-danger">New</span>
                                        @endif
                                    </span>
                                </a>
                            </li>

                            <li class="{{ request()->routeIs('newsletter-subscriptions.*') ? 'mm-active' : '' }}">
                                <a href="{{ route('newsletter-subscriptions.index') }}"
                                    class="{{ request()->routeIs('newsletter-subscriptions.*') ? 'active' : '' }}">
                                    <span>Newsletter Subscriptions
                                        @if ($enquiryCounts['newsletter'] > 0)
                                            <span class="badge rounded-pill bg-danger">New</span>
                                        @endif
                                    </span>
                                </a>
                            </li>

                            <li class="{{ request()->routeIs('blog-comments.*') ? 'mm-active' : '' }}">
                                <a href="{{ route('blog-comments.index') }}"
                                    class="{{ request()->routeIs('blog-comments.*') ? 'active' : '' }}">
                                    <span>Blog Comment
                                        @if ($enquiryCounts['blog_comment'] > 0)
                                            <span class="badge rounded-pill bg-danger">New</span>
                                        @endif
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if (auth('admin')->user()->can('users'))
                    <li class="{{ request()->routeIs('users.*') ? 'mm-active' : '' }}">
                        <a href="{{ route('users.index') }}"
                            class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <i data-feather="users"></i>
                            <span>Users</span>
                        </a>
                    </li>
                @endif

                @if (auth('admin')->user()->can('product-custom-landing'))
                    <li class="{{ request()->routeIs('product-custom-landings.*') ? 'mm-active' : '' }}">
                        <a href="{{ route('product-custom-landings.index') }}"
                            class="{{ request()->routeIs('product-custom-landings.*') ? 'active' : '' }}">
                            <i data-feather="archive"></i>
                            <span>Product Custom Landings</span>
                        </a>
                    </li>
                @endif

                @if (auth('admin')->user()->can('product-reviews'))
                    <li class="{{ request()->routeIs('product-reviews.*') ? 'mm-active' : '' }}">
                        <a href="{{ route('product-reviews.index') }}"
                            class="{{ request()->routeIs('product-reviews.*') ? 'active' : '' }}">
                            <i data-feather="star"></i>
                            <span>Product Reviews</span>
                        </a>
                    </li>
                @endif

                @if (auth('admin')->user()->can('notify-mes'))
                    <li class="{{ request()->routeIs('notify-mes.*') ? 'mm-active' : '' }}">
                        <a href="{{ route('notify-mes.index') }}"
                            class="{{ request()->routeIs('notify-mes.*') ? 'active' : '' }}">
                            <i data-feather="bell"></i>
                            <span>Notify Me</span>
                        </a>
                    </li>
                @endif

                @if (auth('admin')->user()->can('masters'))
                    @php
                        $mastersActive = request()->routeIs(['features.*']);
                    @endphp

                    <li class="{{ $mastersActive ? 'mm-active' : '' }}">
                        <a href="javascript:void(0);" class="has-arrow {{ $mastersActive ? 'mm-active' : '' }}">
                            <i data-feather="codepen"></i>
                            <span>Master</span>
                        </a>

                        <ul class="sub-menu {{ $mastersActive ? 'mm-show' : '' }}" aria-expanded="false">
                            <li class="{{ request()->routeIs('features.*') ? 'mm-active' : '' }}">
                                <a href="{{ route('features.index') }}"
                                    class="{{ request()->routeIs('features.*') ? 'active' : '' }}">
                                    <span>Features</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if (auth('admin')->user()->canAny(['sliders', 'home-cms', 'home-features', 'trusted-brands', 'usps', 'clients']))
                    @php
                        $homeActive = request()->routeIs([
                            'sliders.*',
                            'home-cms.edit',
                            'home-features.*',
                            'trusted-brands.*',
                            'usps.*',
                            'clients.*',
                            'accredited.*',
                        ]);
                    @endphp

                    <li class="{{ $homeActive ? 'mm-active' : '' }}">
                        <a href="javascript:void(0);" class="has-arrow {{ $homeActive ? 'mm-active' : '' }}">
                            <i data-feather="home"></i>
                            <span>Home</span>
                        </a>
                        <ul class="sub-menu {{ $homeActive ? 'mm-show' : '' }}" aria-expanded="false">
                            @if (auth('admin')->user()->can('sliders'))
                                <li class="{{ request()->routeIs('sliders.*') ? 'mm-active' : '' }}">
                                    <a href="{{ route('sliders.index') }}"
                                        class="{{ request()->routeIs('sliders.*') ? 'active' : '' }}">
                                        Sliders
                                    </a>
                                </li>
                            @endif

                            @if (auth('admin')->user()->can('home-cms'))
                                <li class="{{ request()->routeIs('home-cms.edit') ? 'mm-active' : '' }}">
                                    <a href="{{ route('home-cms.edit') }}"
                                        class="{{ request()->routeIs('home-cms.edit') ? 'active' : '' }}">
                                        CMS
                                    </a>
                                </li>
                            @endif

                            @if (auth('admin')->user()->can('home-features'))
                                <li class="{{ request()->routeIs('home-features.*') ? 'mm-active' : '' }}">
                                    <a href="{{ route('home-features.index') }}"
                                        class="{{ request()->routeIs('home-features.*') ? 'active' : '' }}">
                                        Features
                                    </a>
                                </li>
                            @endif

                            @if (auth('admin')->user()->can('trusted-brands'))
                                <li class="{{ request()->routeIs('trusted-brands.*') ? 'mm-active' : '' }}">
                                    <a href="{{ route('trusted-brands.index') }}"
                                        class="{{ request()->routeIs('trusted-brands.*') ? 'active' : '' }}">
                                        Trusted Brands
                                    </a>
                                </li>
                            @endif

                            @if (auth('admin')->user()->can('usps'))
                                <li class="{{ request()->routeIs('usps.*') ? 'mm-active' : '' }}">
                                    <a href="{{ route('usps.index') }}"
                                        class="{{ request()->routeIs('usps.*') ? 'active' : '' }}">
                                        USPs
                                    </a>
                                </li>
                            @endif

                            @if (auth('admin')->user()->can('clients'))
                                <li class="{{ request()->routeIs('clients.*') ? 'mm-active' : '' }}">
                                    <a href="{{ route('clients.index') }}"
                                        class="{{ request()->routeIs('clients.*') ? 'active' : '' }}">
                                        Clients
                                    </a>
                                </li>
                            @endif

                            @if (auth('admin')->user()->can('accredited'))
                                <li class="{{ request()->routeIs('accredited.*') ? 'mm-active' : '' }}">
                                    <a href="{{ route('accredited.index') }}"
                                        class="{{ request()->routeIs('accredited.*') ? 'active' : '' }}">
                                        Accredited
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (auth('admin')->user()->canAny(['office-cms', 'why-choose-us', 'partners', 'solutions', 'spaces']))
                    @php
                        $officeActive = request()->routeIs([
                            'office-cms.edit',
                            'why-choose-us.*',
                            'partners.*',
                            'solutions.*',
                            'space-categories.*',
                        ]);
                    @endphp

                    <li class="{{ $officeActive ? 'mm-active' : '' }}">
                        <a href="javascript:void(0);" class="has-arrow {{ $officeActive ? 'mm-active' : '' }}">
                            <i data-feather="briefcase"></i>
                            <span>Office</span>
                        </a>
                        <ul class="sub-menu {{ $officeActive ? 'mm-show' : '' }}" aria-expanded="false">
                            @if (auth('admin')->user()->can('office-cms'))
                                <li class="{{ request()->routeIs('office-cms.edit') ? 'mm-active' : '' }}">
                                    <a href="{{ route('office-cms.edit') }}"
                                        class="{{ request()->routeIs('office-cms.edit') ? 'active' : '' }}">
                                        CMS
                                    </a>
                                </li>
                            @endif

                            @if (auth('admin')->user()->can('why-choose-us'))
                                <li class="{{ request()->routeIs('why-choose-us.*') ? 'mm-active' : '' }}">
                                    <a href="{{ route('why-choose-us.index') }}"
                                        class="{{ request()->routeIs('why-choose-us.*') ? 'active' : '' }}">
                                        Why Choose Us
                                    </a>
                                </li>
                            @endif

                            @if (auth('admin')->user()->can('partners'))
                                <li class="{{ request()->routeIs('partners.*') ? 'mm-active' : '' }}">
                                    <a href="{{ route('partners.index') }}"
                                        class="{{ request()->routeIs('partners.*') ? 'active' : '' }}">
                                        Partners
                                    </a>
                                </li>
                            @endif

                            @if (auth('admin')->user()->can('solutions'))
                                <li class="{{ request()->routeIs('solutions.*') ? 'mm-active' : '' }}">
                                    <a href="{{ route('solutions.index') }}"
                                        class="{{ request()->routeIs('solutions.*') ? 'active' : '' }}">
                                        Solutions
                                    </a>
                                </li>
                            @endif

                            @if (auth('admin')->user()->can('spaces'))
                                <li class="{{ request()->routeIs('space-categories.*') ? 'mm-active' : '' }}">
                                    <a href="{{ route('space-categories.index') }}"
                                        class="{{ request()->routeIs('space-categories.*') ? 'active' : '' }}">
                                        Space Categories
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (auth('admin')->user()->canAny(['bulk-order-cms', 'bulk-order-benefits', 'success-stories', 'innovators']))
                    @php
                        $bulkOrderActive = request()->routeIs([
                            'bulk-order-cms.edit',
                            'bulk-order-benefits.*',
                            'success-story-categories.*',
                            'innovators.*',
                        ]);
                    @endphp

                    <li class="{{ $bulkOrderActive ? 'mm-active' : '' }}">
                        <a href="javascript:void(0);" class="has-arrow {{ $bulkOrderActive ? 'mm-active' : '' }}">
                            <i data-feather="package"></i>
                            <span>Bulk Order</span>
                        </a>
                        <ul class="sub-menu {{ $bulkOrderActive ? 'mm-show' : '' }}" aria-expanded="false">
                            @if (auth('admin')->user()->can('bulk-order-cms'))
                                <li class="{{ request()->routeIs('bulk-order-cms.edit') ? 'mm-active' : '' }}">
                                    <a href="{{ route('bulk-order-cms.edit') }}"
                                        class="{{ request()->routeIs('bulk-order-cms.edit') ? 'active' : '' }}">
                                        CMS
                                    </a>
                                </li>
                            @endif

                            @if (auth('admin')->user()->can('bulk-order-benefits'))
                                <li class="{{ request()->routeIs('bulk-order-benefits.*') ? 'mm-active' : '' }}">
                                    <a href="{{ route('bulk-order-benefits.index') }}"
                                        class="{{ request()->routeIs('bulk-order-benefits.*') ? 'active' : '' }}">
                                        Benefits
                                    </a>
                                </li>
                            @endif

                            @if (auth('admin')->user()->can('success-stories'))
                                <li
                                    class="{{ request()->routeIs('success-story-categories.*') ? 'mm-active' : '' }}">
                                    <a href="{{ route('success-story-categories.index') }}"
                                        class="{{ request()->routeIs('success-story-categories.*') ? 'active' : '' }}">
                                        Success Story Categories
                                    </a>
                                </li>
                            @endif

                            @if (auth('admin')->user()->can('innovators'))
                                <li class="{{ request()->routeIs('innovators.*') ? 'mm-active' : '' }}">
                                    <a href="{{ route('innovators.index') }}"
                                        class="{{ request()->routeIs('innovators.*') ? 'active' : '' }}">
                                        Innovators
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                {{-- @if (auth('admin')->user()->can('faq'))
                    <li class="{{ request()->routeIs('faq.*') ? 'mm-active' : '' }}">
                        <a href="{{ route('faq.index') }}"
                            class="{{ request()->routeIs('faq.*') ? 'active' : '' }}">
                            <i data-feather="message-circle"></i>
                            <span>Faq</span>
                        </a>
                    </li>
                @endif --}}

                @if (auth('admin')->user()->can('google-reviews'))
                    <li class="{{ request()->routeIs('google-reviews.*') ? 'mm-active' : '' }}">
                        <a href="{{ route('google-reviews.index') }}"
                            class="{{ request()->routeIs('google-reviews.*') ? 'active' : '' }}">
                            <i data-feather="message-circle"></i>
                            <span>Google Reviews</span>
                        </a>
                    </li>
                @endif

                @if (auth('admin')->user()->can('blogs'))
                    <li class="{{ request()->routeIs('blogs.*') ? 'mm-active' : '' }}">
                        <a href="{{ route('blogs.index') }}"
                            class="{{ request()->routeIs('blogs.*') ? 'active' : '' }}">
                            <i data-feather="edit-3"></i>
                            <span>Blogs</span>
                        </a>
                    </li>
                @endif

                @if (auth('admin')->user()->can('support-section-cms'))
                    <li class="{{ request()->routeIs('support-section-cms.*') ? 'mm-active' : '' }}">
                        <a href="{{ route('support-section-cms.index') }}"
                            class="{{ request()->routeIs('support-section-cms.*') ? 'active' : '' }}">
                            <i data-feather="phone-call"></i>
                            <span>Support Section CMS</span>
                        </a>
                    </li>
                @endif

                @if (auth('admin')->user()->can('banner-and-meta-tags'))
                    <li class="{{ request()->routeIs('banner-and-meta-tags.*') ? 'mm-active' : '' }}">
                        <a href="{{ route('banner-and-meta-tags.index') }}"
                            class="{{ request()->routeIs('banner-and-meta-tags.*') ? 'active' : '' }}">
                            <i data-feather="code"></i>
                            <span>Banner And Meta Tags</span>
                        </a>
                    </li>
                @endif

                @if (auth('admin')->user()->can('site-settings'))
                    <li class="{{ request()->routeIs('site-settings.edit') ? 'mm-active' : '' }}">
                        <a href="{{ route('site-settings.edit') }}"
                            class="{{ request()->routeIs('site-settings.edit') ? 'active' : '' }}">
                            <i data-feather="layout"></i>
                            <span>Site Settings</span>
                        </a>
                    </li>
                @endif

                @if (auth('admin')->user()->can('social-links'))
                    <li class="{{ request()->routeIs('social-links.*') ? 'mm-active' : '' }}">
                        <a href="{{ route('social-links.index') }}"
                            class="{{ request()->routeIs('social-links.*') ? 'active' : '' }}">
                            <i data-feather="link-2"></i>
                            <span>Social Links</span>
                        </a>
                    </li>
                @endif

                @if (auth('admin')->user()->can('policies'))
                    <li class="{{ request()->routeIs('policies.*') ? 'mm-active' : '' }}">
                        <a href="{{ route('policies.index') }}"
                            class="{{ request()->routeIs('policies.*') ? 'active' : '' }}">
                            <i data-feather="file-text"></i>
                            <span>Policies</span>
                        </a>
                    </li>
                @endif

                @if (auth('admin')->user()->can('pincodes'))
                    <li class="{{ request()->routeIs('pincodes.*') ? 'mm-active' : '' }}">
                        <a href="{{ route('pincodes.index') }}"
                            class="{{ request()->routeIs('pincodes.*') ? 'active' : '' }}">
                            <i data-feather="map-pin"></i>
                            <span>Pincodes</span>
                        </a>
                    </li>
                @endif

                @if (auth('admin')->user()->can('locations'))
                    <li
                        class="{{ request()->routeIs('states.*') || request()->routeIs('cities.*') ? 'mm-active' : '' }}">
                        <a href="javascript:void(0);"
                            class="has-arrow {{ request()->routeIs('states.*') || request()->routeIs('cities.*') ? 'mm-active' : '' }}">
                            <i data-feather="map"></i>
                            <span>Locations</span>
                        </a>
                        <ul class="sub-menu {{ request()->routeIs('states.*') || request()->routeIs('cities.*') ? 'mm-show' : '' }}"
                            aria-expanded="false">
                            <li class="{{ request()->routeIs('states.*') ? 'mm-active' : '' }}">
                                <a href="{{ route('states.index') }}"
                                    class="{{ request()->routeIs('states.*') ? 'active' : '' }}">
                                    States
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('cities.*') ? 'mm-active' : '' }}">
                                <a href="{{ route('cities.index') }}"
                                    class="{{ request()->routeIs('cities.*') ? 'active' : '' }}">
                                    Cities
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if (auth('admin')->user()->canAny(['admins', 'admin-settings', 'roles']))
                    <li
                        class="{{ request()->routeIs('admin.*') || request()->routeIs(['admin-settings.index', 'admin-settings.edit']) || request()->routeIs('admin.roles.*') ? 'mm-active' : '' }}">
                        <a href="javascript:void(0);"
                            class="has-arrow {{ request()->routeIs('admin.*') || request()->routeIs(['admin-settings.index', 'admin-settings.edit']) || request()->routeIs('admin.roles.*') ? 'mm-active' : '' }}">
                            <i data-feather="shield"></i>
                            <span>Admin Control Panel</span>
                        </a>
                        <ul class="sub-menu {{ request()->routeIs('admin.*') || request()->routeIs(['admin-settings.index', 'admin-settings.edit']) || request()->routeIs('admin.roles.*') ? 'mm-show' : '' }}"
                            aria-expanded="false">
                            @if (auth('admin')->user()->can('admins'))
                                <li
                                    class="{{ request()->routeIs('admin.index') || request()->routeIs('admin.create') || request()->routeIs('admin.edit') || request()->routeIs('admin.change-password.edit') ? 'mm-active' : '' }}">
                                    <a href="{{ route('admin.index') }}"
                                        class="{{ request()->routeIs('admin.index') || request()->routeIs('admin.create') || request()->routeIs('admin.edit') || request()->routeIs('admin.change-password.edit') ? 'active' : '' }}">
                                        Admins
                                    </a>
                                </li>
                            @endif
                            @if (auth('admin')->user()->can('admin-settings'))
                                <li
                                    class="{{ request()->routeIs(['admin-settings.index', 'admin-settings.edit']) ? 'mm-active' : '' }}">
                                    <a href="{{ route('admin-settings.index') }}"
                                        class="{{ request()->routeIs(['admin-settings.index', 'admin-settings.edit']) }}">
                                        Settings
                                    </a>
                                </li>
                            @endif
                            @if (auth('admin')->user()->can('roles'))
                                <li
                                    class="{{ request()->routeIs('admin.roles.index') || request()->routeIs('admin.roles.create') || request()->routeIs('admin.roles.edit') ? 'mm-active' : '' }}">
                                    <a href="{{ route('admin.roles.index') }}"
                                        class="{{ request()->routeIs('admin.roles.index') || request()->routeIs('admin.roles.create') || request()->routeIs('admin.roles.edit') ? 'active' : '' }}">
                                        Roles
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
