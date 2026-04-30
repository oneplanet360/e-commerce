@extends('layouts.layout')

@section('title', 'Admin')
@section('page-title', 'Dashboard')

@section('content')
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-4">
        <!-- Stat Cards -->
        <div class="col">
            <div class="card h-100 admin-card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 uppercase" style="color: var(--color-2);">Products</h6>
                    <div class="d-flex align-items-center">
                        <h3 class="card-title mb-0 me-2" style="color: var(--color-1);">{{ number_format($productsCount) }}</h3>
                    </div>
                    <p class="card-text small mt-2" style="color: var(--color-2);">Total catalog products</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card h-100 admin-card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 uppercase" style="color: var(--color-2);">Brands</h6>
                    <div class="d-flex align-items-center">
                        <h3 class="card-title mb-0 me-2" style="color: var(--color-1);">{{ number_format($brandsCount) }}</h3>
                    </div>
                    <p class="card-text small mt-2" style="color: var(--color-2);">Available brands</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card h-100 admin-card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 uppercase" style="color: var(--color-2);">Categories</h6>
                    <div class="d-flex align-items-center">
                        <h3 class="card-title mb-0 me-2" style="color: var(--color-1);">{{ number_format($categoriesCount) }}</h3>
                    </div>
                    <p class="card-text small mt-2" style="color: var(--color-2);">Total categories</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card h-100 admin-card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 uppercase" style="color: var(--color-2);">Reviews</h6>
                    <div class="d-flex align-items-center">
                        <h3 class="card-title mb-0 me-2" style="color: var(--color-1);">{{ number_format($reviewsCount) }}</h3>
                    </div>
                    <p class="card-text small mt-2" style="color: var(--color-2);">Total review entries</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="card admin-card h-100">
                <div class="card-header border-bottom-0 pt-3" style="background: var(--color-5);">
                    <h5 class="card-title" style="color: var(--color-1);">Monthly Product Additions</h5>
                </div>
                <div class="card-body">
                    <canvas id="activityChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card admin-card h-100">
                <div class="card-header border-bottom-0 pt-3" style="background: var(--color-5);">
                    <h5 class="card-title" style="color: var(--color-1);">Review Status Distribution</h5>
                </div>
                <div class="card-body">
                    @foreach($channelStats as $channel)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small mb-1">
                                <span>{{ $channel['name'] }}</span>
                                <span>{{ $channel['percent'] }}%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar" role="progressbar" style="width: {{ $channel['percent'] }}%; background: var(--brand-color);"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-6">
            <div class="card admin-card h-100">
                <div class="card-header border-bottom-0 pt-3" style="background: var(--color-5);">
                    <h5 class="card-title" style="color: var(--color-1);">Latest Products</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th class="px-3">Order</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentProducts as $product)
                                    <tr>
                                        <td class="px-3">#{{ $product->id }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>₹{{ number_format($product->price) }}</td>
                                        <td>{{ $product->stock }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center py-3">No products found</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card admin-card h-100">
                <div class="card-header border-bottom-0 pt-3" style="background: var(--color-5);">
                    <h5 class="card-title" style="color: var(--color-1);">Top Products</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse($topProducts as $product)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                {{ $product['name'] }}
                                <span class="badge bg-secondary rounded-pill">{{ $product['sold_qty'] }} reviews</span>
                            </li>
                        @empty
                            <li class="list-group-item px-0">No product sales yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('activityChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Products Added',
                        data: @json($chartData),
                        borderColor: '#1e172aff',
                        backgroundColor: 'rgba(30, 23, 42, 0.08)',
                        fill: true,
                        tension: 0.1
                    }]
                }
            });
        });
    </script>
@endpush
