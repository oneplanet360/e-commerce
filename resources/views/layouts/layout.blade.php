<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            /* 5-Color Centralized Palette */
            --color-1: #0f172a;
            /* Primary text (dark navy, softer than black) */
            --color-2: #64748b;
            /* Secondary text */
            --color-3: #f1f5f9;
            /* Hover / subtle backgrounds */
            --color-4: #f8fafc;
            /* Main background */
            --color-5: #ffffff;
            /* Cards / sidebar */

            --brand-color: #1e172aff;
            /* Vibrant Brand Violet */
            --brand-color-soft: rgba(124, 58, 237, 0.1);
            /* Soft variant for backgrounds */

            --sidebar-width: 260px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--color-4);
            color: var(--color-1);
            overflow-x: hidden;
        }

        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--color-5);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            border-right: 1px solid rgba(0, 0, 0, 0.05);
            z-index: 1000;
            padding-top: 80px;
        }

        .sidebar .nav-link {
            padding: 12px 25px;
            color: var(--color-1);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            border-radius: 0 50px 50px 0;
            margin-right: 15px;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover {
            background-color: var(--color-3);
            color: var(--color-1);
        }

        .sidebar .nav-link.active {
            background-color: var(--color-3);
            color: var(--color-1);
            font-weight: 700;
        }

        .sidebar .sidebar-category {
            padding: 20px 25px 10px;
            font-size: 11px;
            font-weight: 700;
            color: var(--color-2);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Header Styling */
        .admin-header {
            height: 70px;
            background: var(--color-5);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
            z-index: 1001;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .logo-area {
            width: var(--sidebar-width);
            font-weight: 800;
            font-size: 1.2rem;
            color: var(--color-1);
            display: flex;
            align-items: center;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .icon-btn {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: #f4f4f4;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #000;
            cursor: pointer;
            border: none;
        }

        .profile-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            background: none;
            border: none;
            padding: 0;
        }

        .profile-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            background: var(--color-1);
        }

        .profile-info {
            text-align: left;
        }

        .profile-name {
            font-weight: 600;
            font-size: 14px;
            display: block;
        }

        .profile-role {
            font-size: 12px;
            color: var(--color-2);
        }

        /* Content Area Positioning */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 69px 14px 102px;
            min-height: 100vh;
            width: calc(100% - var(--sidebar-width));
            box-sizing: border-box;
            overflow-x: hidden;
        }

        .breadcrumb-toolbar {
            margin-bottom: 25px;
        }

        .breadcrumb-item a {
            color: var(--primary-purple);
            text-decoration: none;
        }

        /* Custom Card / Table Style */
        .admin-card {
            background: white;
            border-radius: 12px;
            border: none;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.03);
        }

        .btn-purple {
            background-color: var(--primary-purple);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
        }

        .btn-purple:hover {
            background-color: #722077;
            color: white;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }
        }

        /* Toast Notifications Styling */
        .toast-container {
            position: fixed;
            top: 25px;
            right: 25px;
            z-index: 10000;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .aura-toast {
            min-width: 320px;
            background: var(--color-5);
            border-radius: 14px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            border-left: 4px solid var(--brand-color);
            transform: translateX(120%);
            transition: transform 0.4s cubic-bezier(1.75, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }

        .aura-toast.show {
            transform: translateX(0);
        }

        .aura-toast.success {
            border-left-color: #10b981;
        }

        .aura-toast.error {
            border-left-color: #ef4444;
        }

        .toast-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .success .toast-icon {
            background: #ecfdf5;
            color: #10b981;
        }

        .error .toast-icon {
            background: #fef2f2;
            color: #ef4444;
        }

        /* Custom Confirmation Modal Styling */
        .aura-confirm-modal .modal-content {
            border-radius: 20px;
            border: none;
            overflow: hidden;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
        }

        .aura-confirm-icon {
            width: 65px;
            height: 65px;
            background: #fff1f2;
            color: #e11d48;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin: 10px auto 20px;
        }

        /* Global Pagination Styling */
        .aura-pagination .pagination {
            margin-bottom: 0;
            gap: 6px;
        }

        .aura-pagination .page-link {
            border-radius: 10px !important;
            border: 1px solid var(--color-3);
            color: var(--color-2);
            font-size: 0.75rem;
            padding: 6px 14px;
            background: white;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
        }

        .aura-pagination .page-item.active .page-link {
            background-color: var(--brand-color) !important;
            border-color: var(--brand-color) !important;
            color: white !important;
            box-shadow: 0 4px 12px var(--brand-color-soft);
        }

        .aura-pagination .page-link:hover:not(.active) {
            background-color: var(--color-4);
            border-color: var(--brand-color);
            color: var(--brand-color);
            transform: translateY(-1px);
        }

        .aura-pagination .page-item.disabled .page-link {
            background-color: var(--color-4);
            border-color: var(--color-3);
            color: #cbd5e1;
            opacity: 0.6;
        }

        /* Global toggle style matching design sample */
        .form-switch .form-check-input {
            width: 46px;
            height: 26px;
            border-radius: 999px;
            border: none;
            background-color: #e5e7eb;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23ffffff'/%3e%3c/svg%3e");
            background-position: left center;
            transition: background-position 0.2s ease, background-color 0.2s ease;
            box-shadow: inset 0 0 0 1px rgba(15, 23, 42, 0.08);
            cursor: pointer;
        }

        .form-switch .form-check-input:checked {
            background-color: #1e172a;
            border: none;
            background-position: right center;
        }

        .form-switch .form-check-input:focus {
            box-shadow: 0 0 0 0.2rem rgba(30, 23, 42, 0.18);
            border: none;
        }
    </style>

    @stack('styles')
</head>

<body>

    <!-- Admin Header -->
    @include('layouts.partials.header')

    @include('layouts.partials.sidebar')

    <!-- Main Content Area -->
    <main class="main-content">
        <div class="breadcrumb-toolbar mb-3" style="margin-top: 10px;">
            <h4 class="fw-normal d-flex align-items-center">
                <span class="text-muted"></span>
                <span class="mx-2 text-muted"></span>
                <span class="text-dark fw-bold">@yield('page-title')</span>
            </h4>
        </div>

        @yield('content')
    </main>

    <!-- Footer -->
    @include('layouts.partials.footer')

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Scripts Stack -->
    @stack('scripts')

    <!-- Global UI Elements -->
    <div id="toastContainer" class="toast-container"></div>

    <div class="modal fade aura-confirm-modal" id="auraConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content shadow-lg">
                <div class="modal-body p-4 text-center">
                    <div class="aura-confirm-icon">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Are you sure?</h5>
                    <p class="text-muted small mb-4" id="auraConfirmMessage">Do you really want to delete this? This
                        action cannot be undone.</p>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light w-100 py-2" data-bs-dismiss="modal"
                            style="border-radius: 12px; font-weight: 600; font-size: 0.85rem;">Cancel</button>
                        <button type="button" id="auraConfirmAction" class="btn btn-danger w-100 py-2"
                            style="border-radius: 12px; font-weight: 600; font-size: 0.85rem;">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Global Toast Notification Helper
        window.showToast = function (message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            const icons = { success: 'bi-check-circle-fill', error: 'bi-exclamation-triangle-fill', info: 'bi-info-circle-fill' };

            toast.className = `aura-toast ${type}`;
            toast.innerHTML = `
                <div class="toast-icon">
                    <i class="bi ${icons[type]}"></i>
                </div>
                <div class="toast-content text-start">
                    <span class="toast-title fw-bold d-block" style="font-size: 0.8rem; letter-spacing: 0.5px;">${type.toUpperCase()}</span>
                    <span class="toast-message d-block text-muted" style="font-size: 0.7rem; line-height: 1.2;">${message}</span>
                </div>
                <i class="bi bi-x toast-close ms-2" onclick="this.parentElement.remove()"></i>
            `;

            container.appendChild(toast);
            setTimeout(() => toast.classList.add('show'), 100);
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 400);
            }, 4500);
        };

        // Global Confirmation Modal Helper
        window.auraConfirm = function (message, onConfirm) {
            const modalEl = document.getElementById('auraConfirmModal');
            document.getElementById('auraConfirmMessage').innerText = message;
            const actionBtn = document.getElementById('auraConfirmAction');
            const modal = new bootstrap.Modal(modalEl);

            // Important: Remove old listeners by cloning
            const newActionBtn = actionBtn.cloneNode(true);
            actionBtn.parentNode.replaceChild(newActionBtn, actionBtn);

            newActionBtn.addEventListener('click', () => {
                onConfirm();
                modal.hide();
            });
            modal.show();
        };

        // Auto-submit helper for status switches
        document.addEventListener('change', function (event) {
            if (!event.target.classList.contains('auto-submit-switch')) {
                return;
            }

            const form = event.target.closest('form');
            if (form) {
                form.submit();
            }
        });

        // Auto-show Laravel Notifications
        @if(session('success')) showToast("{{ session('success') }}", 'success'); @endif
        @if(session('error')) showToast("{{ session('error') }}", 'error'); @endif
        @if($errors->any())
            @foreach($errors->all() as $error)
                showToast("{{ $error }}", 'error');
            @endforeach
        @endif
    </script>
</body>

</html>