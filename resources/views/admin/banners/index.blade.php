@extends('layouts.layout')

@section('title', 'Banners')
@section('page-title', 'Banner Management')

@section('content')
@php
    $sections = [
        'main_banner' => 'Main Banner',
        'middle_banner' => 'Middle Banner',
        'bottom_banner' => 'Bottom Banner',
    ];
@endphp

<div class="card admin-card border-0 shadow-sm" style="background: var(--color-5); border-radius: 16px; overflow: hidden;">
    <div class="card-header border-0 py-3 px-4 bg-transparent d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h4 class="mb-0 fw-bold" style="color: var(--color-1); font-size: 1.1rem;">Banner Zones</h4>
    </div>

    <div class="card-body p-4" style="background: var(--color-4);">
        <ul class="nav nav-pills gap-2 mb-4" id="bannerTabs" role="tablist">
            @foreach($sections as $key => $title)
                <li class="nav-item" role="presentation">
                    <button class="nav-link banner-tab-btn {{ $loop->first ? 'active' : '' }}" id="tab-{{ $key }}" data-bs-toggle="pill" data-bs-target="#pane-{{ $key }}" type="button" role="tab" aria-controls="pane-{{ $key }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                        {{ $title }}
                    </button>
                </li>
            @endforeach
        </ul>

        <div class="tab-content" id="bannerTabContent">
            @foreach($sections as $key => $title)
                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="pane-{{ $key }}" role="tabpanel" aria-labelledby="tab-{{ $key }}">
                    <div class="card border-0 shadow-sm" style="border-radius: 14px;">
                        <div class="card-header bg-transparent border-0 py-3 px-3 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold" style="font-size: 0.95rem; color: var(--color-1);">{{ $title }}</h6>
                            <button type="button" class="btn d-flex align-items-center gap-2 px-3 py-1" style="background: var(--brand-color); color: white; border-radius: 10px; font-weight: 600; font-size: 0.75rem; border: none;" data-bs-toggle="modal" data-bs-target="#addBannerModal_{{ $key }}">
                                <i class="bi bi-plus-lg"></i> Add {{ $title }}
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead style="background: var(--color-4);">
                                    <tr class="text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.07em; color: var(--color-2);">
                                        <th class="px-3 py-2">Image</th>
                                        <th class="py-2">Title</th>
                                        <th class="py-2">Order</th>
                                        <th class="py-2">Status</th>
                                        <th class="text-end px-3 py-2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse(($banners[$key] ?? collect()) as $banner)
                                    <tr>
                                        <td class="px-3 py-2">
                                            <img src="{{ asset($banner->image) }}" alt="{{ $banner->title }}" style="width: 90px; height: 44px; object-fit: cover; border-radius: 8px; border: 1px solid var(--color-3);">
                                        </td>
                                        <td class="py-2">
                                            <span class="fw-semibold" style="font-size: 0.8rem; color: var(--color-1);">{{ $banner->title ?: 'Untitled' }}</span>
                                        </td>
                                        <td class="py-2">
                                            <span style="font-size: 0.75rem;">{{ $banner->sort_order }}</span>
                                        </td>
                                        <td class="py-2">
                                            <form action="{{ route('admin.banners.toggle-status', $banner->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                <div class="form-check form-switch m-0">
                                                    <input class="form-check-input auto-submit-switch" type="checkbox" aria-label="Toggle banner status" {{ $banner->is_active ? 'checked' : '' }}>
                                                </div>
                                            </form>
                                        </td>
                                        <td class="text-end px-3 py-2">
                                            <div class="d-flex justify-content-end gap-2">
                                                <button type="button" class="btn p-0 d-flex align-items-center justify-content-center edit-banner-btn"
                                                    data-id="{{ $banner->id }}"
                                                    data-title="{{ $banner->title }}"
                                                    data-position="{{ $banner->position }}"
                                                    data-sort_order="{{ $banner->sort_order }}"
                                                    data-description="{{ $banner->description }}"
                                                    data-background_color="{{ $banner->background_color }}"
                                                    data-is_active="{{ $banner->is_active }}"
                                                    data-image="{{ asset($banner->image) }}"
                                                    data-section="{{ $key }}"
                                                    style="width: 28px; height: 28px; background: var(--color-4); color: var(--color-2); border-radius: 8px; border: 1px solid var(--color-3);">
                                                    <i class="bi bi-pencil" style="font-size: 0.75rem;"></i>
                                                </button>
                                                <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" class="delete-banner-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn p-0 d-flex align-items-center justify-content-center delete-banner-btn" style="width: 28px; height: 28px; background: #fff1f2; color: #f43f5e; border-radius: 8px; border: 1px solid #ffe4e6;">
                                                        <i class="bi bi-trash3" style="font-size: 0.75rem;"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted" style="font-size: 0.75rem;">No items in this section yet.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@foreach($sections as $key => $title)
<div class="modal fade" id="addBannerModal_{{ $key }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius: 16px;">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold" style="font-size: 1rem;">Add {{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="form_type" value="add">
                <input type="hidden" name="position" value="{{ $key }}">
                <div class="modal-body px-4">
                    @if ($errors->any() && old('form_type') === 'add' && old('position') === $key)
                        <div class="alert alert-danger mb-3" style="font-size: 0.8rem; border-radius: 10px;">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Title (Optional)</label>
                        <input type="text" name="title" class="form-control" value="{{ old('form_type') === 'add' && old('position') === $key ? old('title') : '' }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Description (Optional)</label>
                        <textarea name="description" class="form-control" rows="2">{{ old('form_type') === 'add' && old('position') === $key ? old('description') : '' }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Background Color (Optional)</label>
                        <input type="color" name="background_color" class="form-control form-control-color w-100" value="{{ old('form_type') === 'add' && old('position') === $key ? old('background_color', '#fcebed') : '#fcebed' }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('form_type') === 'add' && old('position') === $key ? old('sort_order', 0) : 0 }}" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active_add_banner_{{ $key }}" {{ old('form_type') === 'add' && old('position') === $key ? (old('is_active') ? 'checked' : '') : 'checked' }}>
                        <label class="form-check-label" for="is_active_add_banner_{{ $key }}">Active</label>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn" style="background: var(--brand-color); color: white;">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editBannerModal_{{ $key }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius: 16px;">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold" style="font-size: 1rem;">Edit {{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editBannerForm_{{ $key }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="form_type" value="edit">
                <input type="hidden" name="position" value="{{ $key }}">
                <div class="modal-body px-4">
                    @if ($errors->any() && old('form_type') === 'edit' && old('position') === $key)
                        <div class="alert alert-danger mb-3" style="font-size: 0.8rem; border-radius: 10px;">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Title (Optional)</label>
                        <input type="text" name="title" id="edit_title_{{ $key }}" class="form-control" value="{{ old('form_type') === 'edit' && old('position') === $key ? old('title') : '' }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Description (Optional)</label>
                        <textarea name="description" id="edit_description_{{ $key }}" class="form-control" rows="2">{{ old('form_type') === 'edit' && old('position') === $key ? old('description') : '' }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Background Color (Optional)</label>
                        <input type="color" name="background_color" id="edit_background_color_{{ $key }}" class="form-control form-control-color w-100" value="{{ old('form_type') === 'edit' && old('position') === $key ? old('background_color') : '#fcebed' }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Sort Order</label>
                        <input type="number" name="sort_order" id="edit_sort_order_{{ $key }}" class="form-control" min="0" required value="{{ old('form_type') === 'edit' && old('position') === $key ? old('sort_order') : '' }}">
                    </div>
                    <div class="mb-2">
                        <img id="edit_banner_preview_{{ $key }}" src="" style="width: 130px; height: 64px; object-fit: cover; border-radius: 8px; border: 1px solid var(--color-3); display: none;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Replace Image (Optional)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="edit_is_active_{{ $key }}" {{ old('form_type') === 'edit' && old('position') === $key && old('is_active') ? 'checked' : '' }}>
                        <label class="form-check-label" for="edit_is_active_{{ $key }}">Active</label>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn" style="background: var(--brand-color); color: white;">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.edit-banner-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const section = this.dataset.section;
                const editForm = document.getElementById(`editBannerForm_${section}`);
                const preview = document.getElementById(`edit_banner_preview_${section}`);

                editForm.action = `/admin/banners/${this.dataset.id}`;
                document.getElementById(`edit_title_${section}`).value = this.dataset.title || '';
                document.getElementById(`edit_description_${section}`).value = this.dataset.description || '';
                document.getElementById(`edit_background_color_${section}`).value = this.dataset.background_color || '#fcebed';
                document.getElementById(`edit_sort_order_${section}`).value = this.dataset.sort_order || 0;
                document.getElementById(`edit_is_active_${section}`).checked = this.dataset.is_active === '1';

                if (this.dataset.image) {
                    preview.src = this.dataset.image;
                    preview.style.display = 'block';
                } else {
                    preview.style.display = 'none';
                }

                const editModal = new bootstrap.Modal(document.getElementById(`editBannerModal_${section}`));
                editModal.show();
            });
        });

        document.querySelectorAll('.delete-banner-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const form = this.closest('form');
                if (window.auraConfirm) {
                    window.auraConfirm('This item will be permanently deleted. Continue?', () => form.submit());
                } else {
                    if (confirm('Delete this item?')) form.submit();
                }
            });
        });

        @if ($errors->any() && old('position'))
            const failedFormType = @json(old('form_type'));
            const failedSection = @json(old('position'));
            const failedModalId = failedFormType === 'edit'
                ? `editBannerModal_${failedSection}`
                : `addBannerModal_${failedSection}`;
            const failedModalEl = document.getElementById(failedModalId);
            if (failedModalEl) {
                new bootstrap.Modal(failedModalEl).show();
            }
        @endif
    });
</script>
@endpush

@push('styles')
<style>
    .banner-tab-btn {
        font-size: 0.78rem;
        font-weight: 700;
        border-radius: 10px;
        padding: 0.5rem 0.9rem;
        background: #eef2ff;
        color: var(--color-2);
    }

    .banner-tab-btn.active {
        background: var(--brand-color) !important;
        color: #fff !important;
    }
</style>
@endpush
