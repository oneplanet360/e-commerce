@extends('layouts.layout')

@section('title', 'Brands')
@section('page-title', 'Brand Management')

@section('content')
<div class="card admin-card border-0 shadow-sm" style="background: var(--color-5); border-radius: 16px; overflow: hidden;">
    <div class="card-header border-0 py-3 px-4 bg-transparent d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h4 class="mb-0 fw-bold" style="color: var(--color-1); font-size: 1.1rem;">Brands</h4>
        <button type="button" class="btn d-flex align-items-center gap-2 px-3 py-1" style="background: var(--brand-color); color: white; border-radius: 10px; font-weight: 600; font-size: 0.75rem; border: none; box-shadow: 0 4px 12px var(--brand-color-soft);" data-bs-toggle="modal" data-bs-target="#addBrandModal">
            <i class="bi bi-plus-lg"></i> New Brand
        </button>
    </div>
    <div class="card-body p-4" style="background: var(--color-4);">
        <form action="{{ route('admin.brands.index') }}" method="GET" class="row g-2 mb-4">
            <div class="col-md-4">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search name/slug" style="border-radius: 10px; font-size: 0.85rem; padding: 8px 12px; border-color: var(--color-3); background: white;">
            </div>
            <div class="col-md-3">
                <select name="is_active" class="form-select" style="border-radius: 10px; font-size: 0.85rem; padding: 8px 12px; border-color: var(--color-3); background: white;">
                    <option value="">All Status</option>
                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-2"><button class="btn btn-outline-dark w-100" style="border-radius: 10px; font-size: 0.85rem;">Filter</button></div>
            <div class="col-md-2"><a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary w-100" style="border-radius: 10px; font-size: 0.85rem;">Reset</a></div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background: var(--color-4); border-top: 1px solid var(--color-3); border-bottom: 1px solid var(--color-3);">
                    <tr class="text-uppercase" style="letter-spacing: 0.08em; font-size: 0.65rem; color: var(--color-2); font-weight: 700;">
                        <th class="px-3 py-2 border-0">ID</th>
                        <th class="py-2 border-0">Logo</th>
                        <th class="py-2 border-0">Name</th>
                        <th class="py-2 border-0">Slug</th>
                        <th class="py-2 border-0">Active</th>
                        <th class="text-end px-3 py-2 border-0">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($brands as $brand)
                        <tr style="border-bottom: 1px solid var(--color-4); transition: background 0.2s;">
                            <td class="px-3 py-3">{{ $brand->id }}</td>
                            <td class="py-3">
                                @if($brand->logo)
                                    <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}" style="width: 48px; height: 48px; object-fit: contain; border-radius: 10px; border: 1px solid var(--color-3); background: white; padding: 4px;">
                                @else
                                    <span class="text-muted" style="font-size: 0.8rem;">No logo</span>
                                @endif
                            </td>
                            <td class="py-3 fw-semibold" style="color: var(--color-1);">{{ $brand->name }}</td>
                            <td class="py-3" style="font-size: 0.8rem; color: var(--color-2);">{{ $brand->slug }}</td>
                            <td class="py-3">
                                <span class="badge rounded-pill {{ $brand->is_active ? 'text-bg-success' : 'text-bg-secondary' }}" style="font-size: 0.72rem; padding: 0.45rem 0.7rem;">{{ $brand->is_active ? 'Active' : 'Inactive' }}</span>
                            </td>
                            <td class="text-end px-3 py-3">
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button"
                                        class="btn p-0 d-flex align-items-center justify-content-center edit-brand-btn"
                                        data-id="{{ $brand->id }}"
                                        data-name="{{ $brand->name }}"
                                        data-is_active="{{ $brand->is_active ? 1 : 0 }}"
                                        data-logo="{{ $brand->logo ? asset($brand->logo) : '' }}"
                                        style="width: 30px; height: 30px; background: var(--color-4); color: var(--color-2); border-radius: 8px; border: 1px solid var(--color-3);">
                                        <i class="bi bi-pencil" style="font-size: 0.75rem;"></i>
                                    </button>
                                    <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" class="delete-brand-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn p-0 d-flex align-items-center justify-content-center delete-brand-btn" style="width: 30px; height: 30px; background: #fff1f2; color: #f43f5e; border-radius: 8px; border: 1px solid #ffe4e6;">
                                            <i class="bi bi-trash3" style="font-size: 0.75rem;"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                    <tr><td colspan="6" class="text-center">No brands found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $brands->links('vendor.pagination.aura') }}
    </div>
</div>

<div class="modal fade" id="addBrandModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="--bs-modal-margin: 0.75rem;">
        <div class="modal-content border-0" style="border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold" style="color: var(--color-1); font-size: 1.1rem;">Add New Brand</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body px-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold" style="color: var(--color-2);">Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Brand name" required style="border-radius: 10px; font-size: 0.85rem; padding: 8px 12px; border-color: var(--color-3); background: var(--color-4);">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold" style="color: var(--color-2);">Logo</label>
                        <input type="file" name="logo" class="form-control" accept="image/*" style="border-radius: 10px; font-size: 0.85rem; padding: 8px 12px; border-color: var(--color-3); background: var(--color-4);">
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="add_brand_is_active" checked>
                        <label class="form-check-label" for="add_brand_is_active">Active</label>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 10px; font-size: 0.85rem; font-weight: 500;">Cancel</button>
                    <button type="submit" class="btn" style="background: var(--brand-color); color: white; border-radius: 10px; font-size: 0.85rem; font-weight: 600; padding: 10px 20px; border: none;">Save Brand</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editBrandModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="--bs-modal-margin: 0.75rem;">
        <div class="modal-content border-0" style="border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold" style="color: var(--color-1); font-size: 1.1rem;">Edit Brand</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editBrandForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body px-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold" style="color: var(--color-2);">Name</label>
                        <input type="text" name="name" id="edit_brand_name" class="form-control" required style="border-radius: 10px; font-size: 0.85rem; padding: 8px 12px; border-color: var(--color-3); background: var(--color-4);">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold" style="color: var(--color-2);">Replace Logo</label>
                        <div class="d-flex align-items-center gap-3">
                            <input type="file" name="logo" class="form-control brand-logo-input" accept="image/*" style="border-radius: 10px; font-size: 0.85rem; border-color: var(--color-3); background: var(--color-4); padding: 4px 10px;">
                            <img src="" id="edit_brand_logo_preview" class="rounded-2 shadow-sm border" style="width: 52px; height: 52px; object-fit: contain; border: 2px solid white; display: none; flex-shrink: 0; background: white; padding: 4px;">
                        </div>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="edit_brand_is_active">
                        <label class="form-check-label" for="edit_brand_is_active">Active</label>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 10px; font-size: 0.85rem; font-weight: 500;">Cancel</button>
                    <button type="submit" class="btn" style="background: var(--brand-color); color: white; border-radius: 10px; font-size: 0.85rem; font-weight: 600; padding: 10px 20px; border: none;">Update Brand</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editButtons = document.querySelectorAll('.edit-brand-btn');
            const editModal = new bootstrap.Modal(document.getElementById('editBrandModal'));
            const editForm = document.getElementById('editBrandForm');
            const editName = document.getElementById('edit_brand_name');
            const editLogoPreview = document.getElementById('edit_brand_logo_preview');
            const editIsActive = document.getElementById('edit_brand_is_active');

            editButtons.forEach(button => {
                button.addEventListener('click', function () {
                    editForm.action = `/admin/brands/${this.dataset.id}`;
                    editName.value = this.dataset.name || '';
                    editIsActive.checked = this.dataset.is_active === '1';

                    const logo = this.dataset.logo;
                    if (logo) {
                        editLogoPreview.src = logo;
                        editLogoPreview.style.display = 'inline-block';
                    } else {
                        editLogoPreview.style.display = 'none';
                    }

                    editModal.show();
                });
            });

            document.querySelectorAll('.delete-brand-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const form = this.closest('form');
                    if (window.auraConfirm) {
                        window.auraConfirm('This brand will be permanently removed. Are you sure you want to proceed?', () => form.submit());
                        return;
                    }

                    if (confirm('Delete brand?')) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
@endsection
