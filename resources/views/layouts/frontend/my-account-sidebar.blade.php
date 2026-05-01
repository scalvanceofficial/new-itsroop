<div class="wrap-sidebar-account d-block mb-4 pt-3">
    <ul class="my-account-nav">
        <li><a href="/orders" class="my-account-nav-item">Orders</a></li>
        <li><a href="/profile" class="my-account-nav-item">Account Details</a></li>
        <li><a href="/addresses" class="my-account-nav-item">Addresses</a></li>
        <li><a href="/wishlists" class="my-account-nav-item">Wishlist</a></li>
        <li>
            <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <a href="#" class="my-account-nav-item"
                onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                Logout
            </a>
        </li>
    </ul>
</div>
