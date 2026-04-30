@extends('layouts.layout')

@section('title', 'Products')
@section('page-title', 'Product Management')

@section('content')
<div class="card admin-card border-0 shadow-sm" style="background: var(--color-5); border-radius: 16px; overflow: hidden;">
    <!-- Optimized Header -->
    <div class="card-header border-0 py-3 px-4 bg-transparent d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h4 class="mb-0 fw-bold" style="color: var(--color-1); font-size: 1.1rem; letter-spacing: -0.02em;">Products</h4>
        
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary d-flex align-items-center gap-2 px-3 py-1" style="border-color: var(--color-3); color: var(--color-2); font-size: 0.75rem; border-radius: 10px; background: var(--color-4);">
                <i class="bi bi-box-arrow-up"></i> Export
            </button>
            <button type="button" class="btn d-flex align-items-center gap-2 px-3 py-1 ms-1" style="background: var(--brand-color); color: white; border-radius: 10px; font-weight: 600; font-size: 0.75rem; border: none; box-shadow: 0 4px 12px var(--brand-color-soft);" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i class="bi bi-plus-lg"></i> New Product
            </button>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background: var(--color-4); border-top: 1px solid var(--color-3); border-bottom: 1px solid var(--color-3);">
                    <tr class="text-uppercase" style="letter-spacing: 0.08em; font-size: 0.65rem; color: var(--color-2); font-weight: 700;">
                        <th class="px-3 py-3 border-0" style="width: 48px;">#</th>
                        <th class="ps-4 py-3 border-0" style="width: 60px;">Image</th>
                        <th class="py-3 border-0">Name</th>
                        <th class="py-3 border-0">Description</th>
                        <th class="py-3 border-0">Category</th>
                        <th class="py-3 border-0">Price</th>
                        <th class="py-3 border-0">Status</th>
                        <th class="py-3 border-0">Popular</th>
                        <th class="py-3 border-0">Stock</th>
                        <th class="text-end px-4 py-3 border-0">Action</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($products as $product)
                    <tr style="border-bottom: 1px solid var(--color-4); transition: background 0.2s;">
                        <td class="px-3 py-3">{{ $products->firstItem() ? $products->firstItem() + $loop->index : $loop->iteration }}</td>
                        <td class="ps-4 py-3">
                            @if($product->image)
                                <img src="{{ asset($product->image) }}" class="rounded-3 shadow-sm" style="width: 45px; height: 45px; object-fit: cover; border: 2px solid white;" alt="{{ $product->name }}">
                            @else
                                <div class="rounded-3 d-flex align-items-center justify-content-center border" style="width: 45px; height: 45px; background: var(--color-4); color: var(--color-2); border-color: var(--color-3) !important;">
                                    <i class="bi bi-box-seam" style="font-size: 1rem;"></i>
                                </div>
                            @endif
                        </td>
                        <td class="py-3">
                            <span class="fw-bold d-block" style="color: var(--color-1); font-size: 0.8rem;">{{ $product->name }}</span>
                        </td>
                        <td class="py-3">
                            <p class="mb-0 text-muted" style="font-size: 0.75rem; max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $product->description ?: 'No description' }}
                            </p>
                        </td>
                        <td class="py-3">
                            <span class="badge px-2 py-1" style="background: var(--color-4); color: var(--color-2); border-radius: 6px; font-size: 0.65rem; font-weight: 600;">
                                {{ $product->category->name }}
                            </span>
                        </td>
                        <td class="py-3">
                            @php
                                $displayPrice = $product->price;
                                $actualPrice = $product->price;
                                $displayOldPrice = $product->discount_price ?: null;
                                
                                if($product->variants->count() > 0) {
                                    $v = $product->variants->first();
                                    $displayOldPrice = $v->old_price;
                                    $actualPrice = $v->price;
                                }
                            @endphp
                            
                            @if($displayOldPrice)
                                <div class="text-muted text-decoration-line-through mb-0" style="font-size: 0.65rem; line-height: 1;">₹{{ number_format($displayOldPrice, 0) }}</div>
                            @endif
                            <div class="fw-bold" style="color: var(--color-1); font-size: 0.9rem;">₹{{ number_format($actualPrice, 0) }}</div>
                        </td>
                        <td class="py-3">
                            <form action="{{ route('admin.products.toggle-status', $product->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <div class="form-check form-switch m-0">
                                    <input class="form-check-input auto-submit-switch" type="checkbox" aria-label="Toggle product status" {{ $product->status ? 'checked' : '' }}>
                                </div>
                            </form>
                        </td>
                        <td class="py-3">
                            <form class="popular-toggle-form" data-product-id="{{ $product->id }}" style="display: inline;">
                                @csrf
                                <div class="form-check form-switch" style="margin: 0;">
                                    <input class="form-check-input popular-toggle-checkbox" type="checkbox" id="popular_{{ $product->id }}" {{ $product->is_popular ? 'checked' : '' }} data-product-id="{{ $product->id }}" style="cursor: pointer;">
                                </div>
                            </form>
                        </td>
                        <td class="py-3">
                            @php
                                $totalStock = $product->variants->sum('stock') ?: $product->stock;
                            @endphp
                            <span class="fw-bold" style="color: {{ $totalStock < 10 ? '#f43f5e' : 'var(--color-1)' }}; font-size: 0.8rem;">
                                {{ $totalStock }}
                            </span>
                        </td>
                        <td class="text-end px-4">
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn p-0 d-flex align-items-center justify-content-center view-product-btn"
                                    data-id="{{ $product->id }}"
                                    data-name="{{ $product->name }}"
                                    data-category="{{ $product->category->name }}"
                                    data-description="{{ $product->description }}"
                                    data-price="{{ $product->price }}"
                                    data-discount_price="{{ $product->discount_price }}"
                                    data-stock="{{ $product->stock }}"
                                    data-status="{{ $product->status ? 'Active' : 'Inactive' }}"
                                    data-brand-id="{{ $product->brand_id }}"
                                    data-brand="{{ optional($product->brandRef)->name ?: ($product->brand ?: 'N/A') }}"
                                    data-tags="{{ $product->tags }}"
                                    data-specifications="{{ is_array($product->specifications) ? json_encode($product->specifications) : '[]' }}"
                                    data-features="{{ is_array($product->features) ? json_encode($product->features) : '[]' }}"
                                    data-variants="{{ $product->variants->toJson() }}"
                                    data-gallery="{{ $product->gallery->toJson() }}"
                                    data-image="{{ $product->image ? asset($product->image) : '' }}"
                                    data-delivery_type="{{ $product->delivery_type }}"
                                    data-delivery_charge="{{ $product->delivery_charge }}"
                                    style="width: 28px; height: 28px; background: var(--color-4); color: var(--brand-color); border-radius: 8px; border: 1px solid var(--color-3); transition: all 0.2s;">
                                    <i class="bi bi-eye" style="font-size: 0.75rem;"></i>
                                </button>
                                <button type="button" class="btn p-0 d-flex align-items-center justify-content-center edit-product-btn"
                                    data-id="{{ $product->id }}"
                                    data-name="{{ $product->name }}"
                                    data-category_id="{{ $product->category_id }}"
                                    data-description="{{ $product->description }}"
                                    data-price="{{ $product->price }}"
                                    data-discount_price="{{ $product->discount_price }}"
                                    data-stock="{{ $product->stock }}"
                                    data-status="{{ $product->status }}"
                                    data-is_popular="{{ $product->is_popular }}"
                                    data-sku="{{ $product->sku }}"
                                    data-brand-id="{{ $product->brand_id }}"
                                    data-brand="{{ $product->brand }}"
                                    data-tags="{{ $product->tags }}"
                                    data-short_description="{{ $product->short_description }}"
                                    data-meta_title="{{ $product->meta_title }}"
                                    data-meta_description="{{ $product->meta_description }}"
                                    data-specifications="{{ is_array($product->specifications) ? implode("\n", $product->specifications) : '' }}"
                                    data-features="{{ is_array($product->features) ? implode("\n", $product->features) : '' }}"
                                    data-has_variants="{{ $product->variants->count() > 0 ? '1' : '0' }}"
                                    data-variants="{{ $product->variants->toJson() }}"
                                    data-gallery="{{ $product->gallery->toJson() }}"
                                    data-image="{{ $product->image ? asset($product->image) : '' }}"
                                    data-delivery_type="{{ $product->delivery_type }}"
                                    data-delivery_charge="{{ $product->delivery_charge }}"
                                    style="width: 28px; height: 28px; background: var(--color-4); color: var(--color-2); border-radius: 8px; border: 1px solid var(--color-3); transition: all 0.2s;">
                                    <i class="bi bi-pencil" style="font-size: 0.75rem;"></i>
                                </button>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="delete-product-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn p-0 d-flex align-items-center justify-content-center delete-product-btn" style="width: 28px; height: 28px; background: #fff1f2; color: #f43f5e; border-radius: 8px; border: 1px solid #ffe4e6; transition: all 0.2s;">
                                        <i class="bi bi-trash3" style="font-size: 0.75rem;"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-5 text-muted" style="font-size: 0.75rem;">
                            No products yet. Click "New Product" to get started!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-4 py-3 border-top d-flex flex-column align-items-center gap-2" style="background: var(--color-4);">
            <div class="aura-pagination">
                {{ $products->links('vendor.pagination.aura') }}
            </div>
            <p class="mb-0 text-muted text-center" style="font-size: 0.65rem; font-weight: 500; letter-spacing: 0.3px;">
                Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} entries
            </p>
        </div>
    </div>
</div>

<!-- ADD MODAL -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0" style="border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold" style="color: var(--color-1); font-size: 1.1rem;">Add New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="addProductForm">
                @csrf
                <div class="modal-body px-4">
                    <div class="row">
                        <div class="col-md-7 mb-3">
                            <label class="form-label small fw-semibold" style="color: var(--color-2);">Product Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Aura Whitening Cream" required style="border-radius: 10px; font-size: 0.85rem; padding: 10px; border-color: var(--color-3); background: var(--color-4);">
                        </div>
                        <div class="col-md-5 mb-3">
                            <label class="form-label small fw-semibold" style="color: var(--color-2);">Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select select2-init" required style="border-radius: 10px; font-size: 0.85rem; padding: 10px; border-color: var(--color-3); background: var(--color-4);">
                                <option value="" disabled selected>Search Category...</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-semibold" style="color: var(--color-2);">Base Price <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text pt-2" style="background: var(--color-4); border-color: var(--color-3);">₹</span>
                                <input type="number" id="base_price_input" name="price" class="form-control" min="0" step="1" placeholder="e.g. 600" required style="font-size: 0.85rem; border-color: var(--color-3); background: var(--color-4);">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-semibold" style="color: var(--color-2);">Old Price <span class="text-muted">(Optional)</span></label>
                            <div class="input-group">
                                <span class="input-group-text pt-2" style="background: var(--color-4); border-color: var(--color-3);">₹</span>
                                <input type="number" name="discount_price" class="form-control" min="0" step="1" placeholder="e.g. 800" style="font-size: 0.85rem; border-color: var(--color-3); background: var(--color-4);">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-semibold" style="color: var(--color-2);">Total Stock <span class="text-muted">(Optional)</span></label>
                            <input type="number" name="stock" class="form-control" min="0" step="1" placeholder="Overall Stock" style="border-radius: 10px; font-size: 0.85rem; padding: 10px; border-color: var(--color-3); background: var(--color-4);">
                        </div>
                        <div class="col-12 mt-0 mb-1" id="price_override_notice" style="display: none;">
                            <small class="text-warning fw-bold" style="font-size: 0.7rem;"><i class="bi bi-exclamation-triangle"></i> Variants added. Base price & overall stock will be ignored.</small>
                        </div>
                    </div>

                    <div class="row pt-2 border-top mt-2">
                        <div class="col-12 mt-1 mb-2">
                            <h6 class="fw-bold mb-0" style="color: var(--brand-color); font-size: 0.85rem;">Delivery Charge Details</h6>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-semibold" style="color: var(--color-2);">Delivery Type <span class="text-danger">*</span></label>
                            <select name="delivery_type" class="form-select delivery-type-select" required style="border-radius: 10px; font-size: 0.85rem; padding: 10px; border-color: var(--color-3); background: var(--color-4);">
                                <option value="free" selected>Free Delivery</option>
                                <option value="paid">Paid Delivery</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3 delivery-charge-container" style="display: none;">
                            <label class="form-label small fw-semibold" style="color: var(--color-2);">Delivery Amount (₹) <span class="text-danger">*</span></label>
                            <input type="number" name="delivery_charge" class="form-control" min="0" value="0" placeholder="e.g. 50" style="border-radius: 10px; font-size: 0.85rem; padding: 10px; border-color: var(--color-3); background: var(--color-4);">
                        </div>
                    </div>

                    <!-- Variants Section -->
                    <div class="row pt-2 border-top mt-2">
                        <div class="col-12 mt-1 mb-2">
                            <h6 class="fw-bold mb-0" style="color: var(--brand-color); font-size: 0.85rem;">Variants <span class="text-muted fw-normal">(Optional)</span></h6>
                        </div>

                        <div class="col-12 mb-2">
                            <div class="p-3 rounded-3" style="background: var(--color-4); border: 1px solid var(--color-3);">
                                <div class="row g-2 mb-2 text-muted small fw-bold" style="font-size: 0.65rem;">
                                    <div class="col-4">Variant Name (e.g. Size/Color)</div>
                                    <div class="col-3">Price (₹)</div>
                                    <div class="col-2">Old Price</div>
                                    <div class="col-2">Stock</div>
                                    <div class="col-1"></div>
                                </div>
                                <div id="variants_container">
                                    <div class="row g-2 mb-2 align-items-center variant-row">
                                        <div class="col-4">
                                            <input type="text" name="variant_names[]" class="form-control form-control-sm variant-input" placeholder="e.g. 1kg / Red" style="border-radius: 8px; font-size: 0.8rem;">
                                        </div>
                                        <div class="col-3">
                                            <input type="number" name="variant_prices[]" class="form-control form-control-sm variant-input" placeholder="0" style="border-radius: 8px; font-size: 0.8rem;">
                                        </div>
                                        <div class="col-2">
                                            <input type="number" name="variant_old_prices[]" class="form-control form-control-sm variant-input" placeholder="Optional" style="border-radius: 8px; font-size: 0.8rem;">
                                        </div>
                                        <div class="col-2">
                                            <input type="number" name="variant_stocks[]" class="form-control form-control-sm variant-input" placeholder="Optional" style="border-radius: 8px; font-size: 0.8rem;">
                                        </div>
                                        <div class="col-1">
                                            <button type="button" class="btn btn-link link-danger p-0 remove-variant-row" style="font-size: 1rem;"><i class="bi bi-dash-circle-fill"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mb-3 mt-1">
                            <button type="button" class="btn btn-sm w-100 py-2 border add-variant-row" style="background: white; color: var(--brand-color); border-radius: 10px; font-weight: 600; font-size: 0.75rem;">
                                <i class="bi bi-plus-circle me-1"></i> Add Another Variant
                            </button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-semibold">Brand <span class="text-muted">(Optional)</span></label>
                            <select name="brand_id" class="form-select select2-init" style="border-radius: 10px; font-size: 0.85rem; padding: 10px; border-color: var(--color-3); background: var(--color-4);">
                                <option value="">Select Brand...</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted small mt-1">Choose a brand from the existing brands list.</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-semibold">Tags (Chips)</label>
                            <input type="text" name="tags" class="form-control" placeholder="comma, separated, tags" style="border-radius: 10px; font-size: 0.85rem; padding: 10px; border-color: var(--color-3); background: var(--color-4);">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label small fw-semibold d-block">Status</label>
                            <div class="form-check form-switch pt-2">
                                <input class="form-check-input" type="checkbox" name="status" value="1" checked style="width: 40px; height: 20px;">
                            </div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label small fw-semibold d-block">Popular</label>
                            <div class="form-check form-switch pt-2">
                                <input class="form-check-input" type="checkbox" name="is_popular" value="1" style="width: 40px; height: 20px;">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Short Description</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Brief info about the product..." style="border-radius: 10px; font-size: 0.85rem; padding: 10px; border-color: var(--color-3); background: var(--color-4);"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-semibold">Main Image <span class="text-danger">*</span></label>
                            <div class="position-relative">
                                <input type="file" name="image" class="form-control product-image-input form-control-lg" accept="image/*" required style="border-radius: 10px; font-size: 0.85rem; border-color: var(--color-3); background: var(--color-4);">
                                <div class="mt-2 preview-container" style="display: none;">
                                    <img src="" class="rounded-3 border shadow-sm" style="width: 80px; height: 80px; object-fit: cover; border: 3px solid white;">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-semibold">Gallery <span class="text-muted">(Multiple)</span></label>
                            <input type="file" name="gallery[]" class="form-control form-control-lg" multiple accept="image/*" style="border-radius: 10px; font-size: 0.85rem; border-color: var(--color-3); background: var(--color-4);">
                            <div class="mt-2 text-muted small" style="font-size: 0.7rem;"><i class="bi bi-info-circle"></i> Hold Ctrl/Cmd to select multiple images.</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-semibold">Technical Specs (Structured)</label>
                            <textarea name="specifications" class="form-control" rows="2" placeholder="Color: Blue&#10;Material: Silk" style="border-radius: 10px; font-size: 0.75rem; padding: 10px; border-color: var(--color-3); background: var(--color-4);"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-semibold">Key Highlights</label>
                            <textarea name="features" class="form-control" rows="2" placeholder="Skin Friendly&#10;Natural Ingredients" style="border-radius: 10px; font-size: 0.75rem; padding: 10px; border-color: var(--color-3); background: var(--color-4);"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4 pt-0 mt-2">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius: 10px; font-size: 0.85rem; font-weight: 500; border-color: var(--color-3);">Cancel</button>
                    <button type="submit" class="btn btn-primary submit-btn" id="createProductBtn" style="background: var(--brand-color); border-color: var(--brand-color); color: white; border-radius: 10px; font-size: 0.85rem; font-weight: 600; padding: 10px 20px;">
                        <span class="submit-text">Create Product</span>
                        <span class="spinner-border spinner-border-sm d-none submit-spinner" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- VIEW MODAL -->
<div class="modal fade" id="viewProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <div>
                    <h5 class="modal-title fw-bold mb-1" id="view_name" style="color: var(--color-1); font-size: 1.25rem;"></h5>
                    <span class="badge" id="view_category" style="background: var(--color-4); color: var(--brand-color); font-size: 0.7rem; letter-spacing: 0.5px;"></span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <!-- Left Column: Visuals -->
                    <div class="col-md-5">
                        <div class="rounded-4 overflow-hidden shadow-sm border mb-3 text-center bg-white">
                            <img src="" id="view_image" class="img-fluid w-100" style="max-height: 280px; object-fit: cover;">
                        </div>
                        
                        <div id="view_gallery_section" style="display: none;">
                            <label class="small fw-bold text-muted mb-2 uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">Photo Gallery</label>
                            <div class="d-flex flex-wrap gap-2" id="view_gallery_list"></div>
                        </div>
                    </div>

                    <!-- Right Column: Meta & info -->
                    <div class="col-md-7">
                        <div class="d-flex flex-wrap gap-4 mb-4">
                            <div>
                                <label class="d-block small text-muted mb-1" style="font-size: 0.65rem;">Brand</label>
                                <span class="fw-bold" id="view_brand" style="color: var(--color-1); font-size: 0.85rem;"></span>
                            </div>
                            <div>
                                <label class="d-block small text-muted mb-1" style="font-size: 0.65rem;">Tags</label>
                                <div id="view_tags" class="d-flex gap-1 flex-wrap"></div>
                            </div>
                            <div>
                                <label class="d-block small text-muted mb-1" style="font-size: 0.65rem;">Stock Status</label>
                                <span class="badge" id="view_status" style="font-size: 0.65rem;"></span>
                            </div>
                            <div>
                                <label class="d-block small text-muted mb-1" style="font-size: 0.65rem;">Delivery</label>
                                <span class="badge" id="view_delivery" style="font-size: 0.65rem;"></span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="d-block small text-muted mb-2" style="font-size: 0.65rem;">Variants & Pricing</label>
                            <div class="rounded-3 border overflow-hidden" style="background: var(--color-5);">
                                <table class="table table-sm table-borderless mb-0" style="font-size: 0.75rem;">
                                    <thead style="background: var(--color-4);">
                                        <tr class="text-muted">
                                            <th class="ps-3 py-2">Variant</th>
                                            <th class="py-2">Price</th>
                                            <th class="pe-3 py-2 text-end">Stock</th>
                                        </tr>
                                    </thead>
                                    <tbody id="view_variants_body"></tbody>
                                </table>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="d-block small text-muted mb-1" style="font-size: 0.65rem;">Description</label>
                            <p id="view_description" class="text-muted" style="font-size: 0.8rem; line-height: 1.6;"></p>
                        </div>
                        
                        <div class="row pt-2 border-top">
                            <div class="col-6" id="view_specs_col" style="display: none;">
                                <label class="d-block small text-muted mb-2" style="font-size: 0.65rem;">Specifications</label>
                                <ul id="view_specs_list" class="ps-3 mb-0" style="font-size: 0.7rem; color: var(--color-2);"></ul>
                            </div>
                            <div class="col-6" id="view_features_col" style="display: none;">
                                <label class="d-block small text-muted mb-2" style="font-size: 0.65rem;">Features</label>
                                <ul id="view_features_list" class="ps-3 mb-0" style="font-size: 0.7rem; color: var(--color-2);"></ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pb-4 pe-4">
                <button type="button" class="btn px-4 py-2" data-bs-dismiss="modal" style="background: var(--color-4); color: var(--color-1); border-radius: 12px; font-weight: 600; font-size: 0.8rem; border: none;">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- EDIT MODAL -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0" style="border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold" style="color: var(--color-1); font-size: 1.1rem;">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editProductForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body px-4">
                    <div class="row">
                        <div class="col-md-7 mb-3">
                            <label class="form-label small fw-semibold">Product Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit_name" class="form-control" required style="border-radius: 10px; font-size: 0.85rem; padding: 10px; border-color: var(--color-3); background: var(--color-4);">
                        </div>
                        <div class="col-md-5 mb-3">
                            <label class="form-label small fw-semibold">Category <span class="text-danger">*</span></label>
                            <select name="category_id" id="edit_category_id" class="form-select select2-init" required style="border-radius: 10px; font-size: 0.85rem; padding: 10px; border-color: var(--color-3); background: var(--color-4);">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-semibold" style="color: var(--color-2);">Base Price <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text pt-2" style="background: var(--color-4); border-color: var(--color-3);">₹</span>
                                <input type="number" name="price" id="edit_price" class="form-control" min="0" step="1" required style="font-size: 0.85rem; padding: 10px; border-color: var(--color-3); background: var(--color-4);">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-semibold" style="color: var(--color-2);">Old Price <span class="text-muted">(Optional)</span></label>
                            <div class="input-group">
                                <span class="input-group-text pt-2" style="background: var(--color-4); border-color: var(--color-3);">₹</span>
                                <input type="number" name="discount_price" id="edit_discount_price" class="form-control" min="0" step="1" style="font-size: 0.85rem; padding: 10px; border-color: var(--color-3); background: var(--color-4);">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-semibold" style="color: var(--color-2);">Total Stock <span class="text-muted">(Optional)</span></label>
                            <input type="number" name="stock" id="edit_stock" class="form-control" min="0" step="1" placeholder="Overall Stock" style="border-radius: 10px; font-size: 0.85rem; padding: 10px; border-color: var(--color-3); background: var(--color-4);">
                        </div>
                        <div class="col-12 mt-0 mb-1" id="edit_price_override_notice" style="display: none;">
                            <small class="text-warning fw-bold" style="font-size: 0.7rem;"><i class="bi bi-exclamation-triangle"></i> Variants exists. Base price & overall stock will be ignored.</small>
                        </div>
                    </div>

                    <div class="row pt-2 border-top mt-2">
                        <div class="col-12 mt-1 mb-2">
                            <h6 class="fw-bold mb-0" style="color: var(--brand-color); font-size: 0.85rem;">Delivery Charge Details</h6>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-semibold" style="color: var(--color-2);">Delivery Type <span class="text-danger">*</span></label>
                            <select name="delivery_type" id="edit_delivery_type" class="form-select delivery-type-select" required style="border-radius: 10px; font-size: 0.85rem; padding: 10px; border-color: var(--color-3); background: var(--color-4);">
                                <option value="free">Free Delivery</option>
                                <option value="paid">Paid Delivery</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3 delivery-charge-container" id="edit_delivery_charge_container" style="display: none;">
                            <label class="form-label small fw-semibold" style="color: var(--color-2);">Delivery Amount (₹) <span class="text-danger">*</span></label>
                            <input type="number" name="delivery_charge" id="edit_delivery_charge" class="form-control" min="0" placeholder="e.g. 50" style="border-radius: 10px; font-size: 0.85rem; padding: 10px; border-color: var(--color-3); background: var(--color-4);">
                        </div>
                    </div>

                    <!-- Variants Section -->
                    <div class="row pt-2 border-top mt-2">
                        <div class="col-12 mt-1 mb-2">
                            <h6 class="fw-bold mb-0" style="color: var(--brand-color); font-size: 0.85rem;">Variants <span class="text-muted fw-normal">(Optional)</span></h6>
                        </div>

                        <div class="col-12 mb-2">
                            <div class="p-3 rounded-3" style="background: var(--color-4); border: 1px solid var(--color-3);">
                                <div class="row g-2 mb-2 text-muted small fw-bold" style="font-size: 0.65rem;">
                                    <div class="col-4">Variant Name (e.g. Size/Color)</div>
                                    <div class="col-3">Price (₹)</div>
                                    <div class="col-2">Old Price</div>
                                    <div class="col-2">Stock</div>
                                    <div class="col-1"></div>
                                </div>
                                <div id="edit_variants_container">
                                    <!-- Populated by JS -->
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mb-3 mt-1">
                            <button type="button" class="btn btn-sm w-100 py-2 border add-variant-row" style="background: white; color: var(--brand-color); border-radius: 10px; font-weight: 600; font-size: 0.75rem;">
                                <i class="bi bi-plus-circle me-1"></i> Add Another Variant
                            </button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-semibold">Brand <span class="text-muted">(Optional)</span></label>
                            <select name="brand_id" id="edit_brand_id" class="form-select select2-init" style="border-radius: 10px; font-size: 0.85rem; padding: 10px; border-color: var(--color-3); background: var(--color-4);">
                                <option value="">Select Brand...</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted small mt-1">Choose the brand linked to this product.</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-semibold">Tags (Chips)</label>
                            <input type="text" name="tags" id="edit_tags" class="form-control" placeholder="comma, separated, tags" style="border-radius: 10px; font-size: 0.85rem; padding: 10px; border-color: var(--color-3); background: var(--color-4);">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label small fw-semibold d-block">Status</label>
                            <div class="form-check form-switch pt-2">
                                <input class="form-check-input" type="checkbox" id="edit_status" name="status" value="1" style="width: 40px; height: 20px;">
                            </div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label small fw-semibold d-block">Popular</label>
                            <div class="form-check form-switch pt-2">
                                <input class="form-check-input" type="checkbox" id="edit_is_popular" name="is_popular" value="1" style="width: 40px; height: 20px;">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Description</label>
                        <textarea name="description" id="edit_description" class="form-control" rows="2" style="border-radius: 10px; font-size: 0.85rem; padding: 10px; border-color: var(--color-3); background: var(--color-4);"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-5 mb-3 text-center">
                            <label class="form-label small fw-semibold d-block text-start">Main Image <span class="text-muted">(Optional)</span></label>
                            <div class="position-relative d-inline-block text-start w-100">
                                <img src="" id="edit_image_display" class="rounded-3 shadow-sm border mb-2" style="width: 80px; height: 80px; object-fit: cover; border: 3px solid white; display: none;">
                                <input type="file" name="image" class="form-control product-image-input form-control-lg" accept="image/*" style="border-radius: 10px; font-size: 0.75rem; background: var(--color-4); border-color: var(--color-3);">
                            </div>
                        </div>
                        <div class="col-md-7 mb-3">
                            <label class="form-label small fw-semibold">Add to Gallery <span class="text-muted">(Multiple)</span></label>
                            <input type="file" name="gallery[]" class="form-control form-control-lg" multiple accept="image/*" style="border-radius: 10px; font-size: 0.85rem; border-color: var(--color-3); background: var(--color-4);">
                        </div>
                    </div>

                    <!-- Existing Gallery -->
                    <div id="existing_gallery_container" class="mb-3 p-3 rounded-3" style="background: var(--color-4); border: 1px dashed var(--color-3); display: none;">
                        <label class="form-label small fw-bold d-block mb-2" style="color: var(--color-1); font-size: 0.75rem;">Current Gallery</label>
                        <div class="d-flex flex-wrap gap-3" id="existing_gallery_list">
                            <!-- Populated by JS -->
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-semibold">Technical Specs</label>
                            <textarea name="specifications" id="edit_specifications" class="form-control" rows="2" style="border-radius: 10px; font-size: 0.75rem; padding: 10px; border-color: var(--color-3); background: var(--color-4);"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-semibold">Key Highlights</label>
                            <textarea name="features" id="edit_features" class="form-control" rows="2" style="border-radius: 10px; font-size: 0.75rem; padding: 10px; border-color: var(--color-3); background: var(--color-4);"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4 pt-0 mt-2">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius: 10px; font-size: 0.85rem; font-weight: 500; border-color: var(--color-3);">Cancel</button>
                    <button type="submit" class="btn btn-primary submit-btn" id="editProductBtn" style="background: var(--brand-color); border-color: var(--brand-color); color: white; border-radius: 10px; font-size: 0.85rem; font-weight: 600; padding: 10px 20px;">
                        <span class="submit-text">Update Product</span>
                        <span class="spinner-border spinner-border-sm d-none submit-spinner" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Variant Row Template (Dynamic with remove button)
        const variantRow = (name = '', price = '', stock = '', oldPrice = '') => `
            <div class="row g-2 mb-2 align-items-center variant-row">
                <div class="col-4">
                    <input type="text" name="variant_names[]" value="${name}" class="form-control form-control-sm variant-input" placeholder="e.g. 1kg / Red" style="border-radius: 8px; font-size: 0.8rem;">
                </div>
                <div class="col-3">
                    <input type="number" name="variant_prices[]" value="${price}" class="form-control form-control-sm variant-input" placeholder="0" style="border-radius: 8px; font-size: 0.8rem;">
                </div>
                <div class="col-2">
                    <input type="number" name="variant_old_prices[]" value="${oldPrice}" class="form-control form-control-sm variant-input" placeholder="Optional" style="border-radius: 8px; font-size: 0.8rem;">
                </div>
                <div class="col-2">
                    <input type="number" name="variant_stocks[]" value="${stock}" class="form-control form-control-sm variant-input" placeholder="Optional" style="border-radius: 8px; font-size: 0.8rem;">
                </div>
                <div class="col-1">
                    <button type="button" class="btn btn-link link-danger p-0 remove-variant-row" style="font-size: 1rem;"><i class="bi bi-dash-circle-fill"></i></button>
                </div>
            </div>
        `;

        // Variant Conflict UI Logic
        function checkVariantConflict() {
            // Check Add Modal
            const variantRows = document.querySelectorAll('#variants_container .variant-row');
            let hasVariants = false;
            if (variantRows.length > 1) {
                hasVariants = true;
            } else if (variantRows.length === 1) {
                const input = variantRows[0].querySelector('input[name="variant_names[]"]');
                if (input && input.value.trim() !== '') hasVariants = true;
            }

            const basePriceInput = document.getElementById('base_price_input');
            const notice = document.getElementById('price_override_notice');
            if (basePriceInput) {
                if (hasVariants) {
                    basePriceInput.removeAttribute('required');
                    basePriceInput.style.opacity = '0.5';
                    basePriceInput.style.pointerEvents = 'none';
                    if (notice) notice.style.display = 'block';
                } else {
                    basePriceInput.setAttribute('required', 'required');
                    basePriceInput.style.opacity = '1';
                    basePriceInput.style.pointerEvents = 'auto';
                    if (notice) notice.style.display = 'none';
                }
            }

            // Check Edit Modal
            const editVariantRows = document.querySelectorAll('#edit_variants_container .variant-row');
            let hasEditVariants = false;
            if (editVariantRows.length > 1) {
                hasEditVariants = true;
            } else if (editVariantRows.length === 1) {
                const input = editVariantRows[0].querySelector('input[name="variant_names[]"]');
                if (input && input.value.trim() !== '') hasEditVariants = true;
            }

            const editBasePriceInput = document.getElementById('edit_price');
            const editNotice = document.getElementById('edit_price_override_notice');
            if (editBasePriceInput) {
                if (hasEditVariants) {
                    editBasePriceInput.removeAttribute('required');
                    editBasePriceInput.style.opacity = '0.5';
                    editBasePriceInput.style.pointerEvents = 'none';
                    if (editNotice) editNotice.style.display = 'block';
                } else {
                    editBasePriceInput.setAttribute('required', 'required');
                    editBasePriceInput.style.opacity = '1';
                    editBasePriceInput.style.pointerEvents = 'auto';
                    if (editNotice) editNotice.style.display = 'none';
                }
            }
        }

        document.getElementById('variants_container')?.addEventListener('input', checkVariantConflict);
        document.getElementById('edit_variants_container')?.addEventListener('input', checkVariantConflict);

        // Add Variant Row Logic
        document.querySelectorAll('.add-variant-row').forEach(btn => {
            btn.addEventListener('click', function() {
                const container = this.closest('.row.pt-2').querySelector('[id*="variants_container"]');
                container.insertAdjacentHTML('beforeend', variantRow());
                checkVariantConflict();
            });
        });

        // Remove Variant Row Logic
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-variant-row')) {
                const row = e.target.closest('.variant-row');
                row.remove();
                checkVariantConflict();
            }
        });

        // Image Preview Logic
        document.querySelectorAll('.product-image-input').forEach(input => {
            input.addEventListener('change', function() {
                const file = this.files[0];
                const previewContainer = this.closest('div').querySelector('.preview-container');
                const imgDisplay = previewContainer ? previewContainer.querySelector('img') : this.closest('.modal').querySelector('img');

                if (file && imgDisplay) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imgDisplay.src = e.target.result;
                        if (previewContainer) {
                            previewContainer.style.display = 'block';
                        } else {
                            imgDisplay.style.display = 'inline-block';
                        }
                    }
                    reader.readAsDataURL(file);
                }
            });
        });

        // Delivery Charge Visibility Toggle
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('delivery-type-select')) {
                const container = e.target.closest('.row').querySelector('.delivery-charge-container');
                if (container) {
                    if (e.target.value === 'paid') {
                        container.style.display = 'block';
                        container.querySelector('input').setAttribute('required', 'required');
                    } else {
                        container.style.display = 'none';
                        container.querySelector('input').removeAttribute('required');
                    }
                }
            }
        });

        // Form Submit State Tracking
        function attachSubmitState(formId, btnId) {
            const form = document.getElementById(formId);
            if (form) {
                form.addEventListener('submit', function() {
                    const btn = document.getElementById(btnId);
                    if (btn) {
                        const text = btn.querySelector('.submit-text');
                        const spinner = btn.querySelector('.submit-spinner');
                        btn.style.pointerEvents = 'none';
                        btn.style.opacity = '0.8';
                        if (text) text.textContent = 'Saving...';
                        if (spinner) spinner.classList.remove('d-none');
                    }
                });
            }
        }
        
        attachSubmitState('addProductForm', 'createProductBtn');
        attachSubmitState('editProductForm', 'editProductBtn');

        // Optional: Init Select2 if loaded
        if (typeof jQuery !== 'undefined' && jQuery.fn.select2) {
            jQuery('#addProductModal .select2-init').select2({ dropdownParent: jQuery('#addProductModal') });
            jQuery('#editProductModal .select2-init').select2({ dropdownParent: jQuery('#editProductModal') });
        }

        // Populate Edit Modal
        const editButtons = document.querySelectorAll('.edit-product-btn');
        const editModal = new bootstrap.Modal(document.getElementById('editProductModal'));
        const editForm = document.getElementById('editProductForm');

        editButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                
                editForm.action = `/admin/products/${id}`;
                document.getElementById('edit_name').value = this.dataset.name;
                document.getElementById('edit_category_id').value = this.dataset.category_id;
                document.getElementById('edit_description').value = this.dataset.description;
                document.getElementById('edit_price').value = this.dataset.price !== 'null' ? this.dataset.price : '';
                document.getElementById('edit_discount_price').value = this.dataset.discount_price !== 'null' ? this.dataset.discount_price : '';
                document.getElementById('edit_stock').value = this.dataset.stock !== 'null' ? this.dataset.stock : '';
                
                // Status Toggle handling
                document.getElementById('edit_status').checked = this.dataset.status === '1';
                
                // Popular Product Toggle handling
                document.getElementById('edit_is_popular').checked = this.dataset.is_popular === '1';
                
                document.getElementById('edit_brand_id').value = this.dataset.brandId !== 'null' ? this.dataset.brandId : '';
                const tagsField = document.getElementById('edit_tags');
                if (tagsField) tagsField.value = this.dataset.tags || '';
                
                document.getElementById('edit_specifications').value = this.dataset.specifications !== 'null' ? this.dataset.specifications : '';
                document.getElementById('edit_features').value = this.dataset.features !== 'null' ? this.dataset.features : '';

                // Variants logic for edit
                const variantsContainer = document.getElementById('edit_variants_container');
                variantsContainer.innerHTML = '';
                
                try {
                    const variants = JSON.parse(this.dataset.variants);
                    if (variants.length > 0) {
                        variants.forEach(v => {
                            variantsContainer.insertAdjacentHTML('beforeend', variantRow(v.name, v.price, v.stock, v.old_price));
                        });
                    } else {
                        variantsContainer.insertAdjacentHTML('beforeend', variantRow());
                    }
                } catch (e) {
                    variantsContainer.insertAdjacentHTML('beforeend', variantRow());
                }
                
                // Trigger variant check immediately when editing to hide base price if necessary
                checkVariantConflict();

                const image = this.dataset.image;
                const imgDisplay = document.getElementById('edit_image_display');
                if (image) {
                    imgDisplay.src = image;
                    imgDisplay.style.display = 'inline-block';
                } else {
                    imgDisplay.style.display = 'none';
                }

                // Gallery preview logic
                const galleryContainer = document.getElementById('existing_gallery_container');
                const galleryList = document.getElementById('existing_gallery_list');
                galleryList.innerHTML = '';
                
                try {
                    const gallery = JSON.parse(this.dataset.gallery);
                    if (gallery && gallery.length > 0) {
                        galleryContainer.style.display = 'block';
                        gallery.forEach(item => {
                            const imgHtml = `
                                <div class="position-relative gallery-item" data-id="${item.id}">
                                    <img src="/${item.image_path}" class="rounded-2" style="width: 60px; height: 60px; object-fit: cover; border: 2px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                    <button type="button" class="btn btn-danger p-0 position-absolute top-0 end-0 rounded-circle delete-gallery-img" 
                                        data-id="${item.id}"
                                        style="width: 18px; height: 18px; font-size: 10px; margin-top: -5px; margin-right: -5px;">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                            `;
                            galleryList.insertAdjacentHTML('beforeend', imgHtml);
                        });
                    } else {
                        galleryContainer.style.display = 'none';
                    }
                } catch (e) {
                    galleryContainer.style.display = 'none';
                }

                // Delivery info for edit
                const deliveryTypeSelect = document.getElementById('edit_delivery_type');
                deliveryTypeSelect.value = this.dataset.delivery_type || 'free';
                
                const deliveryChargeContainer = document.getElementById('edit_delivery_charge_container');
                const deliveryChargeInput = document.getElementById('edit_delivery_charge');
                deliveryChargeInput.value = this.dataset.delivery_charge || 0;
                
                if (deliveryTypeSelect.value === 'paid') {
                    deliveryChargeContainer.style.display = 'block';
                    deliveryChargeInput.setAttribute('required', 'required');
                } else {
                    deliveryChargeContainer.style.display = 'none';
                    deliveryChargeInput.removeAttribute('required');
                }

                editModal.show();
            });
        });

        // Populate View Modal
        const viewModal = new bootstrap.Modal(document.getElementById('viewProductModal'));
        document.querySelectorAll('.view-product-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('view_name').textContent = this.dataset.name;
                document.getElementById('view_category').textContent = this.dataset.category;
                document.getElementById('view_brand').textContent = this.dataset.brand;
                
                const viewTags = document.getElementById('view_tags');
                if (viewTags) {
                    viewTags.innerHTML = '';
                    if (this.dataset.tags && this.dataset.tags !== 'null') {
                        this.dataset.tags.split(',').forEach(tag => {
                            if (tag.trim()) {
                                viewTags.innerHTML += `<span class="badge" style="background: var(--color-4); color: var(--color-2); border: 1px solid var(--color-3); font-size: 0.65rem; font-weight: 500;">${tag.trim()}</span>`;
                            }
                        });
                    } else {
                        viewTags.innerHTML = '<span class="text-muted" style="font-size: 0.75rem;">None</span>';
                    }
                }

                document.getElementById('view_description').textContent = this.dataset.description || 'No description provided.';
                
                const statusBadge = document.getElementById('view_status');
                statusBadge.textContent = this.dataset.status;
                statusBadge.className = 'badge ' + (this.dataset.status === 'Active' ? 'bg-success-soft text-success' : 'bg-danger-soft text-danger');
                statusBadge.style.cssText = 'background: ' + (this.dataset.status === 'Active' ? '#ecfdf5' : '#fff1f2') + '; color: ' + (this.dataset.status === 'Active' ? '#059669' : '#e11d48') + ';';

                document.getElementById('view_image').src = this.dataset.image || '';

                // Delivery Info for view
                const deliveryBadge = document.getElementById('view_delivery');
                if (this.dataset.delivery_type === 'paid') {
                    deliveryBadge.textContent = 'Paid (₹' + this.dataset.delivery_charge + ')';
                    deliveryBadge.style.cssText = 'background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa;';
                } else {
                    deliveryBadge.textContent = 'Free';
                    deliveryBadge.style.cssText = 'background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0;';
                }

                // Variants/Pricing
                const variantsBody = document.getElementById('view_variants_body');
                variantsBody.innerHTML = '';
                try {
                    const variants = JSON.parse(this.dataset.variants);
                    if (variants.length > 0) {
                        variants.forEach(v => {
                            variantsBody.insertAdjacentHTML('beforeend', `
                                <tr>
                                    <td class="ps-3 py-2 fw-semibold">${v.name}</td>
                                    <td class="py-2">
                                        <span class="fw-bold text-dark">₹${v.price}</span>
                                        ${v.old_price ? `<span class="text-muted text-decoration-line-through ms-1" style="font-size: 0.65rem;">₹${v.old_price}</span>` : ''}
                                    </td>
                                    <td class="pe-3 py-2 text-end text-muted">${v.stock}</td>
                                </tr>
                            `);
                        });
                    } else {
                        variantsBody.insertAdjacentHTML('beforeend', `
                            <tr>
                                <td class="ps-3 py-2 fw-semibold">Standard</td>
                                <td class="py-2">
                                    <span class="fw-bold text-dark">₹${this.dataset.price}</span>
                                    ${this.dataset.discount_price !== 'null' && this.dataset.discount_price ? `<span class="text-muted text-decoration-line-through ms-1" style="font-size: 0.65rem;">₹${this.dataset.discount_price}</span>` : ''}
                                </td>
                                <td class="pe-3 py-2 text-end text-muted">${this.dataset.stock}</td>
                            </tr>
                        `);
                    }
                } catch (e) { console.error('Error parsing variants', e); }

                // Gallery
                const galleryList = document.getElementById('view_gallery_list');
                const gallerySection = document.getElementById('view_gallery_section');
                galleryList.innerHTML = '';
                try {
                    const gallery = JSON.parse(this.dataset.gallery);
                    if (gallery && gallery.length > 0) {
                        gallerySection.style.display = 'block';
                        gallery.forEach(item => {
                            galleryList.insertAdjacentHTML('beforeend', `<img src="/${item.image_path}" class="rounded-2 border" style="width: 50px; height: 50px; object-fit: cover;">`);
                        });
                    } else {
                        gallerySection.style.display = 'none';
                    }
                } catch (e) { gallerySection.style.display = 'none'; }

                // Specs & Features
                const specsList = document.getElementById('view_specs_list');
                const specsCol = document.getElementById('view_specs_col');
                specsList.innerHTML = '';
                try {
                    const specs = JSON.parse(this.dataset.specifications);
                    if (specs.length > 0) {
                        specsCol.style.display = 'block';
                        specs.forEach(s => specsList.insertAdjacentHTML('beforeend', `<li>${s}</li>`));
                    } else { specsCol.style.display = 'none'; }
                } catch (e) { specsCol.style.display = 'none'; }

                const featuresList = document.getElementById('view_features_list');
                const featuresCol = document.getElementById('view_features_col');
                featuresList.innerHTML = '';
                try {
                    const features = JSON.parse(this.dataset.features);
                    if (features.length > 0) {
                        featuresCol.style.display = 'block';
                        features.forEach(f => featuresList.insertAdjacentHTML('beforeend', `<li>${f}</li>`));
                    } else { featuresCol.style.display = 'none'; }
                } catch (e) { featuresCol.style.display = 'none'; }

                viewModal.show();
            });
        });

        // Delete Gallery Image Logic (Ajax)
        document.addEventListener('click', function(e) {
            if (e.target.closest('.delete-gallery-img')) {
                const btn = e.target.closest('.delete-gallery-img');
                const id = btn.dataset.id;
                const item = btn.closest('.gallery-item');

                window.auraConfirm("Are you sure you want to delete this gallery image?", () => {
                    fetch(`/admin/gallery/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            item.remove();
                            // Optional: show toast
                        }
                    });
                });
            }
        });

        // Popular Toggle Logic (AJAX)
        document.querySelectorAll('.popular-toggle-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const productId = this.dataset.productId;
                const isChecked = this.checked ? 1 : 0;
                
                console.log('Toggling product', productId, 'to', isChecked);
                
                // Send AJAX request to update is_popular
                fetch(`/admin/products/${productId}/toggle-popular`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        is_popular: isChecked
                    })
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success) {
                        console.log('Product popular status updated successfully');
                    } else {
                        this.checked = !this.checked;
                        alert('Failed to update product status: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.checked = !this.checked;
                    alert('Error updating product status: ' + error.message);
                });
            });
        });

        // Custom Confirmation for Delete
        document.querySelectorAll('.delete-product-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const form = this.closest('form');
                window.auraConfirm(
                    "This product will be permanently deleted. Are you sure you want to proceed?",
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
    tr:hover { background-color: var(--color-4) !important; }
    .btn:hover { transform: translateY(-1px); }
    .modal-backdrop { background-color: rgba(15, 23, 42, 0.4); }
</style>
@endsection
