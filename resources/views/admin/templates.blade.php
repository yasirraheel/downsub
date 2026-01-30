@extends('layouts.admin')

@section('title', 'Templates')

@section('content')
<div class="grid-3">
    <!-- Template Card -->
    <div class="card">
        <div class="d-flex justify-between align-center" style="margin-bottom: 1rem;">
            <h3>Order Confirmation</h3>
            <span class="text-success">Approved</span>
        </div>
        <p class="text-muted" style="margin-bottom: 1rem; font-size: 0.9rem;">
            Hello {{1}}, your order #{{2}} has been confirmed...
        </p>
        <button class="btn btn-outline w-100">Edit Template</button>
    </div>

    <!-- Template Card -->
    <div class="card">
        <div class="d-flex justify-between align-center" style="margin-bottom: 1rem;">
            <h3>OTP Verification</h3>
            <span class="text-success">Approved</span>
        </div>
        <p class="text-muted" style="margin-bottom: 1rem; font-size: 0.9rem;">
            Your verification code is {{1}}. Do not share...
        </p>
        <button class="btn btn-outline w-100">Edit Template</button>
    </div>

    <!-- Add New -->
    <div class="card d-flex align-center justify-center flex-column" style="cursor: pointer; border-style: dashed;">
        <i class="fas fa-plus" style="font-size: 2rem; color: var(--muted); margin-bottom: 1rem;"></i>
        <h3 class="text-muted">Create New Template</h3>
    </div>
</div>
@endsection
