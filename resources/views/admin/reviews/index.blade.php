@extends('layouts.layout')

@section('title', 'Reviews')
@section('page-title', 'Review Management')

@section('content')
<div class="card admin-card border-0 shadow-sm">
    <div class="card-header"><h4 class="mb-0">Reviews</h4></div>
    <div class="card-body">
        <form action="{{ route('admin.reviews.index') }}" method="GET" class="row g-2 mb-3">
            <div class="col-md-3"><input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Customer name"></div>
            <div class="col-md-3">
                <select name="product_id" class="form-select">
                    <option value="">All Products</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ (string) request('product_id') === (string) $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="col-md-2"><input type="number" min="1" max="5" name="rating" value="{{ request('rating') }}" class="form-control" placeholder="Rating"></div>
            <div class="col-md-1"><button class="btn btn-outline-dark w-100">Go</button></div>
            <div class="col-md-2"><a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary w-100">Reset</a></div>
        </form>

        <form action="{{ route('admin.reviews.store') }}" method="POST" class="row g-2 mb-4">
            @csrf
            <div class="col-md-3">
                <select name="product_id" class="form-select" required>
                    <option value="">Product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2"><input type="text" name="customer_name" class="form-control" placeholder="Customer" required></div>
            <div class="col-md-2"><input type="number" name="rating" class="form-control" min="1" max="5" placeholder="Rating" required></div>
            <div class="col-md-2">
                <select name="status" class="form-select" required>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
            <div class="col-md-2"><input type="text" name="review_text" class="form-control" placeholder="Review"></div>
            <div class="col-md-1"><button class="btn btn-dark w-100">Add</button></div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead><tr><th>ID</th><th>Product</th><th>Customer</th><th>Rating</th><th>Status</th><th>Action</th></tr></thead>
                <tbody>
                    @forelse($reviews as $review)
                    <tr>
                        <td>{{ $review->id }}</td>
                        <td>{{ $review->product?->name }}</td>
                        <td>{{ $review->customer_name }}</td>
                        <td>{{ $review->rating }}</td>
                        <td>{{ ucfirst($review->status) }}</td>
                        <td class="d-flex gap-2">
                            <form action="{{ route('admin.reviews.update', $review) }}" method="POST" class="d-flex gap-2">
                                @csrf @method('PUT')
                                <input type="number" name="rating" class="form-control form-control-sm" min="1" max="5" value="{{ $review->rating }}">
                                <select name="status" class="form-select form-select-sm">
                                    <option value="pending" {{ $review->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ $review->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ $review->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                                <input type="text" name="review_text" class="form-control form-control-sm" value="{{ $review->review_text }}">
                                <button class="btn btn-sm btn-primary">Update</button>
                            </form>
                            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete review?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center">No reviews found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $reviews->links('vendor.pagination.aura') }}
    </div>
</div>
@endsection
