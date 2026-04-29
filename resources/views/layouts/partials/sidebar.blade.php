<div class="sidebar">
    <div class="sidebar-body mt-2">
        <ul class="nav flex-column">
            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('dashboard*') || request()->is('admin/dashboard*') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-grid-fi ll me-2"></i>
                    Dashboard
                </a>
            </li>

            <!-- Products -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/products*') ? 'active' : '' }}"
                    href="{{ route('admin.products.index') }}">
                    <i class="bi bi-box-fill me-2"></i>
                    Products    
                </a>
            </li>

            <!-- Categories -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/categories*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                    <i class="bi bi-tags-fill me-2"></i>
                    Categories
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/banners*') ? 'active' : '' }}" href="{{ route('admin.banners.index') }}">
                    <i class="bi bi-images me-2"></i>
                    Banners
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/brands*') ? 'active' : '' }}" href="{{ route('admin.brands.index') }}">
                    <i class="bi bi-award me-2"></i>
                    Brands
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/reviews*') ? 'active' : '' }}" href="{{ route('admin.reviews.index') }}">
                    <i class="bi bi-star me-2"></i>
                    Reviews
                </a>
            </li>

            <!-- Before & After removed -->

            <!-- Settings -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/settings*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                    <i class="bi bi-gear-fill me-2"></i>
                    Settings
                </a>
            </li>
        </ul>
    </div>
</div>
