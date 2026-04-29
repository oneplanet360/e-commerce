@extends('layouts.layout')

@section('title', 'Admin - Settings')
@section('page-title', 'Store Settings')

@section('content')
@php
    $activeTab = old('active_tab', session('active_tab', 'general'));

    if ($errors->hasAny(['app_name', 'project_description', 'app_email', 'support_phone', 'support_address'])) {
        $activeTab = 'general';
    } elseif ($errors->hasAny(['terms_title', 'terms_content'])) {
        $activeTab = 'terms';
    } elseif ($errors->hasAny(['faq_title', 'faq_content'])) {
        $activeTab = 'faq';
    } elseif ($errors->hasAny(['privacy_policy_title', 'privacy_policy_content', 'return_policy_title', 'return_policy_content'])) {
        $activeTab = 'policies';
    }
@endphp
<div class="card admin-card border-0 shadow-sm" style="background: var(--color-5); border-radius: 16px; overflow: hidden;">
    <div class="card-header border-0 py-3 px-4 bg-transparent d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h4 class="mb-0 fw-bold" style="color: var(--color-1); font-size: 1.1rem;">Project Settings</h4>
    </div>
    <div class="card-body p-4" style="background: var(--color-4);">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            <input type="hidden" name="active_tab" id="active_tab" value="{{ $activeTab }}">

            <ul class="nav nav-tabs gap-2 mb-4" id="settingsTab" role="tablist" style="border-bottom: 1px solid var(--color-3);">
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab === 'general' ? 'active' : '' }}" id="general-tab" data-bs-toggle="tab" data-bs-target="#general-pane" type="button" role="tab" aria-controls="general-pane" aria-selected="{{ $activeTab === 'general' ? 'true' : 'false' }}" data-tab-key="general" style="color: var(--color-2); font-weight: 600;">General</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab === 'terms' ? 'active' : '' }}" id="terms-tab" data-bs-toggle="tab" data-bs-target="#terms-pane" type="button" role="tab" aria-controls="terms-pane" aria-selected="{{ $activeTab === 'terms' ? 'true' : 'false' }}" data-tab-key="terms" style="color: var(--color-2); font-weight: 600;">Terms & Conditions</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab === 'faq' ? 'active' : '' }}" id="faq-tab" data-bs-toggle="tab" data-bs-target="#faq-pane" type="button" role="tab" aria-controls="faq-pane" aria-selected="{{ $activeTab === 'faq' ? 'true' : 'false' }}" data-tab-key="faq" style="color: var(--color-2); font-weight: 600;">FAQ</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab === 'policies' ? 'active' : '' }}" id="policies-tab" data-bs-toggle="tab" data-bs-target="#policies-pane" type="button" role="tab" aria-controls="policies-pane" aria-selected="{{ $activeTab === 'policies' ? 'true' : 'false' }}" data-tab-key="policies" style="color: var(--color-2); font-weight: 600;">Policies</button>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade {{ $activeTab === 'general' ? 'show active' : '' }}" id="general-pane" role="tabpanel" aria-labelledby="general-tab">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="app_name" class="form-label small fw-semibold" style="color: var(--color-2);">App Name</label>
                            <input type="text" name="app_name" id="app_name" class="form-control"
                                value="{{ $settings['app_name'] ?? '' }}" placeholder="Your app name" required
                                style="border-radius: 10px; font-size: 0.85rem; padding: 8px 12px; border-color: var(--color-3); background: white;">
                            <div class="form-text text-muted small mt-1">The main name of your application.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="app_email" class="form-label small fw-semibold" style="color: var(--color-2);">App Email</label>
                            <input type="email" name="app_email" id="app_email" class="form-control"
                                value="{{ $settings['app_email'] ?? '' }}" placeholder="contact@example.com" required
                                style="border-radius: 10px; font-size: 0.85rem; padding: 8px 12px; border-color: var(--color-3); background: white;">
                            <div class="form-text text-muted small mt-1">Primary email for application and customer inquiries.</div>
                        </div>

                        <div class="col-md-12">
                            <label for="project_description" class="form-label small fw-semibold" style="color: var(--color-2);">App Description</label>
                            <textarea name="project_description" id="project_description" class="form-control" rows="3"
                                placeholder="Brief description of your app..."
                                style="border-radius: 10px; font-size: 0.85rem; padding: 8px 12px; border-color: var(--color-3); background: white;">{{ $settings['project_description'] ?? '' }}</textarea>
                            <div class="form-text text-muted small mt-1">A brief description of your application.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="support_phone" class="form-label small fw-semibold" style="color: var(--color-2);">Support Phone</label>
                            <input type="text" name="support_phone" id="support_phone" class="form-control"
                                value="{{ $settings['support_phone'] ?? '' }}" placeholder="+1-800-000-0000"
                                style="border-radius: 10px; font-size: 0.85rem; padding: 8px 12px; border-color: var(--color-3); background: white;">
                            <div class="form-text text-muted small mt-1">Customer support phone number (optional).</div>
                        </div>

                        <div class="col-md-6">
                            <label for="support_address" class="form-label small fw-semibold" style="color: var(--color-2);">Support Address</label>
                            <input type="text" name="support_address" id="support_address" class="form-control"
                                value="{{ $settings['support_address'] ?? '' }}" placeholder="123 Business St, City, State"
                                style="border-radius: 10px; font-size: 0.85rem; padding: 8px 12px; border-color: var(--color-3); background: white;">
                            <div class="form-text text-muted small mt-1">Business address for customer reference (optional).</div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade {{ $activeTab === 'terms' ? 'show active' : '' }}" id="terms-pane" role="tabpanel" aria-labelledby="terms-tab">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="terms_title" class="form-label small fw-semibold" style="color: var(--color-2);">Terms Title</label>
                            <input type="text" name="terms_title" id="terms_title" class="form-control"
                                value="{{ $settings['terms_title'] ?? '' }}" placeholder="Terms & Conditions"
                                style="border-radius: 10px; font-size: 0.85rem; padding: 8px 12px; border-color: var(--color-3); background: white;">
                        </div>

                        <div class="col-md-12">
                            <label for="terms_content" class="form-label small fw-semibold" style="color: var(--color-2);">Terms Content</label>
                            <textarea name="terms_content" id="terms_content" class="form-control" rows="10"
                                placeholder="Write the terms and conditions content here..."
                                style="border-radius: 10px; font-size: 0.85rem; padding: 12px; border-color: var(--color-3); background: white;">{{ $settings['terms_content'] ?? '' }}</textarea>
                            <div class="form-text text-muted small mt-1">This content can be shown on your storefront terms page.</div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade {{ $activeTab === 'faq' ? 'show active' : '' }}" id="faq-pane" role="tabpanel" aria-labelledby="faq-tab">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="faq_title" class="form-label small fw-semibold" style="color: var(--color-2);">FAQ Title</label>
                            <input type="text" name="faq_title" id="faq_title" class="form-control"
                                value="{{ $settings['faq_title'] ?? '' }}" placeholder="Frequently Asked Questions"
                                style="border-radius: 10px; font-size: 0.85rem; padding: 8px 12px; border-color: var(--color-3); background: white;">
                        </div>

                        <div class="col-md-12">
                            <label for="faq_content" class="form-label small fw-semibold" style="color: var(--color-2);">FAQ Content</label>
                            <textarea name="faq_content" id="faq_content" class="form-control" rows="10"
                                placeholder="Add questions and answers here, one per section..."
                                style="border-radius: 10px; font-size: 0.85rem; padding: 12px; border-color: var(--color-3); background: white;">{{ $settings['faq_content'] ?? '' }}</textarea>
                            <div class="form-text text-muted small mt-1">Use any format you prefer, such as Q&A blocks or bullet points.</div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade {{ $activeTab === 'policies' ? 'show active' : '' }}" id="policies-pane" role="tabpanel" aria-labelledby="policies-tab">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="privacy_policy_title" class="form-label small fw-semibold" style="color: var(--color-2);">Privacy Policy Title</label>
                            <input type="text" name="privacy_policy_title" id="privacy_policy_title" class="form-control"
                                value="{{ $settings['privacy_policy_title'] ?? '' }}" placeholder="Privacy Policy"
                                style="border-radius: 10px; font-size: 0.85rem; padding: 8px 12px; border-color: var(--color-3); background: white;">
                        </div>

                        <div class="col-md-12">
                            <label for="privacy_policy_content" class="form-label small fw-semibold" style="color: var(--color-2);">Privacy Policy Content</label>
                            <textarea name="privacy_policy_content" id="privacy_policy_content" class="form-control" rows="8"
                                placeholder="Write your privacy policy here..."
                                style="border-radius: 10px; font-size: 0.85rem; padding: 12px; border-color: var(--color-3); background: white;">{{ $settings['privacy_policy_content'] ?? '' }}</textarea>
                        </div>

                        <div class="col-md-12">
                            <label for="return_policy_title" class="form-label small fw-semibold" style="color: var(--color-2);">Return Policy Title</label>
                            <input type="text" name="return_policy_title" id="return_policy_title" class="form-control"
                                value="{{ $settings['return_policy_title'] ?? '' }}" placeholder="Return Policy"
                                style="border-radius: 10px; font-size: 0.85rem; padding: 8px 12px; border-color: var(--color-3); background: white;">
                        </div>

                        <div class="col-md-12">
                            <label for="return_policy_content" class="form-label small fw-semibold" style="color: var(--color-2);">Return Policy Content</label>
                            <textarea name="return_policy_content" id="return_policy_content" class="form-control" rows="8"
                                placeholder="Write your return and refund policy here..."
                                style="border-radius: 10px; font-size: 0.85rem; padding: 12px; border-color: var(--color-3); background: white;">{{ $settings['return_policy_content'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn w-100 py-2" 
                    style="background: var(--brand-color); color: white; border-radius: 10px; font-weight: 600; font-size: 0.9rem; border: none;">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const activeTabInput = document.getElementById('active_tab');
    const tabButtons = document.querySelectorAll('#settingsTab [data-bs-toggle="tab"]');

    tabButtons.forEach(function (button) {
        button.addEventListener('shown.bs.tab', function (event) {
            activeTabInput.value = event.target.getAttribute('data-tab-key') || 'general';
        });
    });
});
</script>
@endpush
@endsection
