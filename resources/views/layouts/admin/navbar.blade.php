<nav class="sidebar-nav scroll-sidebar" data-simplebar>
    <ul id="sidebarnav">
        @can('dashboard-view')
            <li class="sidebar-item mt-4">
                <a class="sidebar-link @if (Route::is('admin.dashboard.*')) active @endif"
                    href="{{ route('admin.dashboard.index') }}" aria-expanded="false">
                    <span>
                        <i class="ti ti-aperture"></i>
                    </span>
                    <span class="hide-menu">Dashboard</span>
                </a>
            </li>
        @endcan
        {{-- <hr> --}}
        {{-- <li class="sidebar-item">
            <a class="sidebar-link has-arrow 
                @if (Route::is('admin.roles.*') || Route::is('admin.employees.*')) active @endif"
                href="#" aria-expanded="false">
                <span>
                    <i class="ti ti-users"></i>
                </span>
                <span class="hide-menu">Roles & Employees</span>
            </a>
            <ul aria-expanded="false"
                class="collapse first-level 
                @if (Route::is('admin.roles.*') || Route::is('admin.employees.*')) in @endif">

               

                @can('employees-view')
                    <li class="sidebar-item">
                        <a class="sidebar-link @if (Route::is('admin.employees.*')) active @endif"
                            href="{{ route('admin.employees.index') }}" aria-expanded="false">
                            <span>
                                <i class="ti ti-circle"></i>
                            </span>
                            <span class="hide-menu">Employees</span>
                        </a>
                    </li>
                @endcan
            </ul>
        </li> --}}


        <li class="sidebar-item">
            <a class="sidebar-link has-arrow 
                @if (Route::is('admin.sliders.*') || Route::is('admin.videos.*')) active @endif"
                href="#" aria-expanded="false">
                <span>
                    <i class="fas fa-images"></i>
                </span>
                <span class="hide-menu">Gallery</span>
            </a>
            <ul aria-expanded="false"
                class="collapse first-level 
                @if (Route::is('admin.sliders.*') || Route::is('admin.videos.*')) in @endif">

                @can('sliders-view')
                    <li class="sidebar-item">
                        <a class="sidebar-link @if (Route::is('admin.sliders.*')) active @endif"
                            href="{{ route('admin.sliders.index') }}" aria-expanded="false">
                            <span>
                                <i class="ti ti-circle"></i>
                            </span>
                            <span class="hide-menu">Sliders</span>
                        </a>
                    </li>
                @endcan

                @can('videos-view')
                    <li class="sidebar-item">
                        <a class="sidebar-link @if (Route::is('admin.videos.*')) active @endif"
                            href="{{ route('admin.videos.index') }}" aria-expanded="false">
                            <span>
                                <i class="ti ti-circle"></i>
                            </span>
                            <span class="hide-menu">Videos</span>
                        </a>
                    </li>
                @endcan
            </ul>
        </li>

        <li class="sidebar-item">
            <a class="sidebar-link has-arrow 
                @if (Route::is('admin.properties.*') ||
                        Route::is('admin.categories.*') ||
                        Route::is('admin.products.*') ||
                        Route::is('admin.collections.*')) active @endif"
                href="#" aria-expanded="false">
                <span>
                    <i class="ti ti-category"></i>
                </span>
                <span class="hide-menu">Products List</span>
            </a>
            <ul aria-expanded="false"
                class="collapse first-level 
                @if (Route::is('admin.properties.*') ||
                        Route::is('admin.categories.*') ||
                        Route::is('admin.products.*') ||
                        Route::is('admin.collections.*')) in @endif">

                @can('properties-view')
                    <li class="sidebar-item">
                        <a class="sidebar-link @if (Route::is('admin.properties.*')) active @endif"
                            href="{{ route('admin.properties.index') }}" aria-expanded="false">
                            <span>
                                <i class="ti ti-circle"></i>
                            </span>
                            <span class="hide-menu">Properties</span>
                        </a>
                    </li>
                @endcan

                @can('categories-view')
                    <li class="sidebar-item">
                        <a class="sidebar-link @if (Route::is('admin.categories.*')) active @endif"
                            href="{{ route('admin.categories.index') }}" aria-expanded="false">
                            <span>
                                <i class="ti ti-circle"></i>
                            </span>
                            <span class="hide-menu">Categories</span>
                        </a>
                    </li>
                @endcan

                @can('products-view')
                    <li class="sidebar-item">
                        <a class="sidebar-link @if (Route::is('admin.products.*')) active @endif"
                            href="{{ route('admin.products.index') }}" aria-expanded="false">
                            <span>
                                <i class="ti ti-circle"></i>
                            </span>
                            <span class="hide-menu">Products</span>
                        </a>
                    </li>
                @endcan

                @can('collections-view')
                    <li class="sidebar-item">
                        <a class="sidebar-link @if (Route::is('admin.collections.*')) active @endif"
                            href="{{ route('admin.collections.index') }}" aria-expanded="false">
                            <span>
                                <i class="ti ti-circle"></i>
                            </span>
                            <span class="hide-menu">Collections</span>
                        </a>
                    </li>
                @endcan
            </ul>
        </li>

        @can('coupon-code-view')
            <li class="sidebar-item">
                <a class="sidebar-link @if (Route::is('admin.coupon-codes.*')) active @endif"
                    href="{{ route('admin.coupon-codes.index') }}" aria-expanded="false">
                    <span>
                        <i class="fas fa-tag"></i> </span>
                    <span class="hide-menu">Coupon Codes</span>
                </a>
            </li>
        @endcan

        @can('customers-view')
            <li class="sidebar-item">
                <a class="sidebar-link @if (Route::is('admin.customers.*')) active @endif"
                    href="{{ route('admin.customers.index') }}" aria-expanded="false">
                    <span>
                        <i class="ti ti-users"></i>
                    </span>
                    <span class="hide-menu">Customers</span>
                </a>
            </li>
        @endcan

        @can('carts-view')
            <li class="sidebar-item">
                <a class="sidebar-link @if (Route::is('admin.carts.*')) active @endif"
                    href="{{ route('admin.carts.index') }}" aria-expanded="false">
                    <span>
                        <i class="ti ti-shopping-cart"></i>
                    </span>
                    <span class="hide-menu">Cart</span>
                </a>
            </li>
        @endcan

        @can('wishlists-view')
            <li class="sidebar-item">
                <a class="sidebar-link @if (Route::is('admin.wishlists.*')) active @endif"
                    href="{{ route('admin.wishlists.index') }}" aria-expanded="false">
                    <span>
                        <i class="ti ti-heart"></i>
                    </span>
                    <span class="hide-menu">Wishlists</span>
                </a>
            </li>
        @endcan

        @can('orders-view')
            <li class="sidebar-item">
                <a class="sidebar-link @if (Route::is('admin.orders.*')) active @endif"
                    href="{{ route('admin.orders.index') }}" aria-expanded="false">
                    <span>
                        <i class="ti ti-shopping-cart"></i>
                    </span>
                    <span class="hide-menu">Orders</span>
                </a>
            </li>
        @endcan

        @can('return-product-view')
            <li class="sidebar-item">
                <a class="sidebar-link @if (Route::is('admin.return-products.*')) active @endif"
                    href="{{ route('admin.return-products.index') }}" aria-expanded="false">
                    <span>
                        <i class="ti ti-package-export"></i>
                    </span>
                    <span class="hide-menu">Returns</span>
                </a>
            </li>
        @endcan

        @can('review-view')
            <li class="sidebar-item">
                <a class="sidebar-link @if (Route::is('admin.reviews.*')) active @endif"
                    href="{{ route('admin.reviews.index') }}" aria-expanded="false">
                    <span>
                        <i class="fas fa-star"></i>
                    </span>
                    <span class="hide-menu">Reviews</span>
                </a>
            </li>
        @endcan

        @can('testimonial-view')
            <li class="sidebar-item">
                <a class="sidebar-link @if (Route::is('admin.testimonials.*')) active @endif"
                    href="{{ route('admin.testimonials.index') }}" aria-expanded="false">
                    <span>
                        <i class="fas fa-comment-alt"></i>
                    </span>
                    <span class="hide-menu">Testimonial</span>
                </a>
            </li>
        @endcan


        @can('blog-view')
            <li class="sidebar-item">
                <a class="sidebar-link @if (Route::is('admin.blogs.*')) active @endif"
                    href="{{ route('admin.blogs.index') }}" aria-expanded="false">
                    <span>
                        <i class="fas fa-file-alt"></i>
                    </span>
                    <span class="hide-menu">Blogs</span>
                </a>
            </li>
        @endcan

        @can('news-view')
            <li class="sidebar-item">
                <a class="sidebar-link @if (Route::is('admin.news.*')) active @endif"
                    href="{{ route('admin.news.index') }}" aria-expanded="false">
                    <span>
                        <i class="fas fa-file-alt"></i>
                    </span>
                    <span class="hide-menu">News</span>
                </a>
            </li>
        @endcan

        <!-- @can('credential-view')
            <li class="sidebar-item">
                <a class="sidebar-link @if (Route::is('admin.credentials.*')) active @endif"
                    href="{{ route('admin.credentials.index') }}" aria-expanded="false">
                    <span>
                        <i class="fas fa-shield-alt"></i>
                    </span>
                    <span class="hide-menu">Credentials</span>
                </a>
            </li>
        @endcan -->


        @can('enquiries-view')
            <li class="sidebar-item">
                <a class="sidebar-link @if (Route::is('admin.enquiries.*')) active @endif"
                    href="{{ route('admin.enquiries.index') }}" aria-expanded="false">
                    <span>
                        <i class="ti ti-mail"></i>
                    </span>
                    <span class="hide-menu">Enquiries</span>
                </a>
            </li>
        @endcan

        @can('subscribers-view')
            <li class="sidebar-item">
                <a class="sidebar-link @if (Route::is('admin.subscribers.*')) active @endif"
                    href="{{ route('admin.subscribers.index') }}" aria-expanded="false">
                    <span>
                        <i class="ti ti-user-heart"></i>
                    </span>
                    <span class="hide-menu">subscribers</span>
                </a>
            </li>
        @endcan

        @can('visitorlog-view')
            <li class="sidebar-item">
                <a class="sidebar-link @if (Route::is('admin.visitorlog.*')) active @endif"
                    href="{{ route('admin.visitorlog.index') }}" aria-expanded="false">
                    <span>
                        <i class="ti ti-user-heart"></i>
                    </span>
                    <span class="hide-menu">Visitor Logs</span>
                </a>
            </li>
        @endcan



        <!-- End of File -->
    </ul>
</nav>
