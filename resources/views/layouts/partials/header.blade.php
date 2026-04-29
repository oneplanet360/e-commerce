<header class="admin-header">
    <div class="logo-area">
        <span class="ms-2">Admin</span>
    </div>
    
    <div class="header-right">
        <div class="dropdown">
            <button class="profile-btn d-flex align-items-center dropdown-toggle border-0 bg-transparent" type="button" data-bs-toggle="dropdown">
                <div class="profile-info me-2 text-end">
                    <span class="profile-role d-block">Hello</span>
                    <span class="profile-name">{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</span>
                </div>
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::guard('admin')->user()->name ?? 'Admin') }}&background=0f172a&color=fff" class="profile-img" alt="Profile">
            </button>
            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm mt-2">
                <li><a class="dropdown-item py-2" href="#"><i class="bi bi-person me-2"></i> My Profile</a></li>
                <li><a class="dropdown-item py-2" href="#"><i class="bi bi-gear me-2"></i> Settings</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item py-2 text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
