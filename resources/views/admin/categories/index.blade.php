@extends('layouts.layout')

@section('title', 'Categories')
@section('page-title', 'Category Management')

@section('content')
    <div class="card admin-card border-0 shadow-sm"
        style="background: var(--color-5); border-radius: 16px; overflow: hidden;">
        <!-- Optimized Header -->
        <div
            class="card-header border-0 py-2 px-3 bg-transparent d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h4 class="mb-0 fw-bold" style="color: var(--color-1); font-size: 1.1rem; letter-spacing: -0.02em;">Categories
            </h4>

            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary d-flex align-items-center gap-2 px-3 py-1"
                    style="border-color: var(--color-3); color: var(--color-2); font-size: 0.75rem; border-radius: 10px; background: var(--color-4);">
                    <i class="bi bi-box-arrow-up"></i> Export
                </button>
                <button class="btn btn-outline-secondary d-flex align-items-center gap-2 px-3 py-1"
                    style="border-color: var(--color-3); color: var(--color-2); font-size: 0.75rem; border-radius: 10px; background: var(--color-4);">
                    <i class="bi bi-box-arrow-in-down"></i> Import
                </button>
                <button type="button" class="btn d-flex align-items-center gap-2 px-3 py-1 ms-1"
                    style="background: var(--brand-color); color: white; border-radius: 10px; font-weight: 600; font-size: 0.75rem; border: none; box-shadow: 0 4px 12px var(--brand-color-soft);"
                    data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="bi bi-plus-lg"></i> New Category
                </button>
            </div>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive" style="max-width: 100%;">
                <table class="table table-hover align-middle mb-0">
                    <thead
                        style="background: var(--color-4); border-top: 1px solid var(--color-3); border-bottom: 1px solid var(--color-3);">
                        <tr class="text-uppercase"
                            style="letter-spacing: 0.08em; font-size: 0.65rem; color: var(--color-2); font-weight: 700;">
                            <th class="px-4 py-1 border-0">Image</th>
                            <th class="py-1 border-0">Name</th>
                            <th class="py-1 border-0" style="width: 40%;">Description</th>
                            <th class="py-1 border-0">Status</th>
                            <th class="text-end px-4 py-1 border-0">Action</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($categories as $category)
                            <tr style="border-bottom: 1px solid var(--color-4); transition: background 0.2s;">
                                <td class="px-4 py-1">
                                    @if($category->image)
                                        <img src="{{ asset($category->image) }}" class="rounded-3 shadow-sm"
                                            style="width: 36px; height: 36px; object-fit: cover; border: 2px solid white;"
                                            alt="{{ $category->name }}">
                                    @else
                                        <div class="rounded-3 d-flex align-items-center justify-content-center border"
                                            style="width: 36px; height: 36px; background: var(--color-4); color: var(--color-2); border-color: var(--color-3) !important;">
                                            <i class="bi bi-tag" style="font-size: 0.8rem;"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="py-1">
                                    <span class="fw-bold"
                                        style="color: var(--color-1); font-size: 0.8rem;">{{ $category->name }}</span>
                                </td>
                                <td class="py-1">
                                    <span class="text-muted"
                                        style="font-size: 0.75rem;">{{ Str::limit($category->description, 80) ?: 'No description' }}</span>
                                </td>
                                <td>
                                    <form action="{{ route('admin.categories.toggle-status', $category->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        <div class="form-check form-switch m-0">
                                            <input class="form-check-input auto-submit-switch" type="checkbox" aria-label="Toggle category status" {{ $category->status ? 'checked' : '' }}>
                                        </div>
                                    </form>
                                </td>
                                <td class="text-end px-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="button"
                                            class="btn p-0 d-flex align-items-center justify-content-center edit-category-btn"
                                            data-id="{{ $category->id }}" data-name="{{ $category->name }}"
                                            data-description="{{ $category->description }}"
                                            data-status="{{ $category->status }}"
                                            data-image="{{ $category->image ? asset($category->image) : '' }}"
                                            style="width: 28px; height: 28px; background: var(--color-4); color: var(--color-2); border-radius: 8px; border: 1px solid var(--color-3); transition: all 0.2s;">
                                            <i class="bi bi-pencil" style="font-size: 0.75rem;"></i>
                                        </button>
                                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST"
                                            class="delete-category-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="btn p-0 d-flex align-items-center justify-content-center delete-category-btn"
                                                style="width: 28px; height: 28px; background: #fff1f2; color: #f43f5e; border-radius: 8px; border: 1px solid #ffe4e6; transition: all 0.2s;">
                                                <i class="bi bi-trash3" style="font-size: 0.75rem;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted" style="font-size: 0.75rem;">
                                    No categories yet. Click "New Category" to get started!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 border-top d-flex flex-column align-items-center gap-2"
                style="background: var(--color-4);">
                <div class="aura-pagination">
                    {{ $categories->links('vendor.pagination.aura') }}
                </div>
                <p class="mb-0 text-muted text-center" style="font-size: 0.65rem; font-weight: 500; letter-spacing: 0.3px;">
                    Showing {{ $categories->firstItem() ?? 0 }} to {{ $categories->lastItem() ?? 0 }} of
                    {{ $categories->total() }} entries
                </p>
            </div>
        </div>
    </div>

    <!-- ADD MODAL -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="--bs-modal-margin: 0.75rem;">
            <div class="modal-content border-0" style="border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold" style="color: var(--color-1); font-size: 1.1rem;">Add New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body px-4">
                        <div class="mb-2">
                            <label class="form-label small fw-semibold" style="color: var(--color-2);">Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Smartphones" required
                                style="border-radius: 10px; font-size: 0.85rem; padding: 6px 10px; border-color: var(--color-3); background: var(--color-4);">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold" style="color: var(--color-2);">Description</label>
                            <textarea name="description" class="form-control" rows="2"
                                placeholder="Tell us more about this category..."
                                style="border-radius: 10px; font-size: 0.85rem; padding: 6px 10px; border-color: var(--color-3); background: var(--color-4);"></textarea>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold" style="color: var(--color-2);">Status</label>
                            <select name="status" class="form-select"
                                style="border-radius: 10px; font-size: 0.85rem; padding: 6px 10px; border-color: var(--color-3); background: var(--color-4);">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold" style="color: var(--color-2);">Image</label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="file" name="image" class="form-control category-image-input" accept="image/*"
                                    style="border-radius: 10px; font-size: 0.85rem; border-color: var(--color-3); background: var(--color-4); padding: 4px 10px;">
                                <div class="image-preview-area" style="display: none; flex-shrink: 0;">
                                    <img src="" class="rounded-2 shadow-sm border"
                                        style="width: 45px; height: 45px; object-fit: cover; border: 2px solid white;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pb-5 px-4 pt-0" style="margin-top: -0.5rem;">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                            style="border-radius: 10px; font-size: 0.85rem; font-weight: 500;">Cancel</button>
                        <button type="submit" class="btn"
                            style="background: var(--brand-color); color: white; border-radius: 10px; font-size: 0.85rem; font-weight: 600; padding: 10px 20px; border: none;">Save
                            Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="--bs-modal-margin: 0.75rem;">
            <div class="modal-content border-0" style="border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold" style="color: var(--color-1); font-size: 1.1rem;">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editCategoryForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body px-4">
                        <div class="mb-2">
                            <label class="form-label small fw-semibold" style="color: var(--color-2);">Name</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required
                                style="border-radius: 10px; font-size: 0.85rem; padding: 6px 10px; border-color: var(--color-3); background: var(--color-4);">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold" style="color: var(--color-2);">Description</label>
                            <textarea name="description" id="edit_description" class="form-control" rows="2"
                                style="border-radius: 10px; font-size: 0.85rem; padding: 6px 10px; border-color: var(--color-3); background: var(--color-4);"></textarea>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold" style="color: var(--color-2);">Status</label>
                            <select name="status" id="edit_status" class="form-select"
                                style="border-radius: 10px; font-size: 0.85rem; padding: 6px 10px; border-color: var(--color-3); background: var(--color-4);">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold" style="color: var(--color-2);">Image</label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="file" name="image" class="form-control category-image-input" accept="image/*"
                                    style="border-radius: 10px; font-size: 0.85rem; border-color: var(--color-3); background: var(--color-4); padding: 4px 10px;">
                                <img src="" id="edit_image_display" class="rounded-2 shadow-sm border"
                                    style="width: 45px; height: 45px; object-fit: cover; border: 2px solid white; display: none; flex-shrink: 0;">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pb-5 px-4 pt-0" style="margin-top: -0.5rem;">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                            style="border-radius: 10px; font-size: 0.85rem; font-weight: 500;">Cancel</button>
                        <button type="submit" class="btn"
                            style="background: var(--brand-color); color: white; border-radius: 10px; font-size: 0.85rem; font-weight: 600; padding: 10px 20px; border: none;">Update
                            Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Image Preview Logic
                document.querySelectorAll('.category-image-input').forEach(input => {
                    input.addEventListener('change', function () {
                        const file = this.files[0];
                        const modal = this.closest('.modal');
                        const previewArea = modal.querySelector('.image-preview-area');
                        const imgDisplay = modal.querySelector('img');

                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function (e) {
                                imgDisplay.src = e.target.result;
                                if (previewArea) previewArea.style.display = 'block';
                                imgDisplay.style.display = 'inline-block';
                            }
                            reader.readAsDataURL(file);
                        }
                    });
                });

                // Populate Edit Modal
                const editButtons = document.querySelectorAll('.edit-category-btn');
                const editModal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
                const editForm = document.getElementById('editCategoryForm');

                editButtons.forEach(btn => {
                    btn.addEventListener('click', function () {
                        const id = this.dataset.id;
                        const name = this.dataset.name;
                        const description = this.dataset.description;
                        const status = this.dataset.status;
                        const image = this.dataset.image;

                        editForm.action = `/admin/categories/${id}`;
                        document.getElementById('edit_name').value = name;
                        document.getElementById('edit_description').value = description;
                        document.getElementById('edit_status').value = status;

                        const imgDisplay = document.getElementById('edit_image_display');
                        if (image) {
                            imgDisplay.src = image;
                            imgDisplay.style.display = 'inline-block';
                        } else {
                            imgDisplay.style.display = 'none';
                        }

                        editModal.show();
                    });
                });

                // Global Custom Confirmation for Delete
                document.querySelectorAll('.delete-category-btn').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const form = this.closest('form');
                        window.auraConfirm(
                            "This category will be permanently removed. Are you sure you want to proceed?",
                            () => {
                                form.submit();
                            }
                        );
                    });
                });
            });
        </script>
    @endpush

    <style>
        tr:hover {
            background-color: var(--color-4) !important;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .modal-backdrop {
            background-color: rgba(15, 23, 42, 0.4);
        }
    </style>
@endsection