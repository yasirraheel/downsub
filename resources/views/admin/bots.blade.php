@extends('layouts.admin')

@section('title', 'Bots')

@section('content')
<div class="d-flex justify-between align-center" style="margin-bottom: 2rem;">
    <div>
        <h3 class="text-muted">Configure automated responses and bots</h3>
    </div>
    <button class="btn btn-primary">
        <i class="fas fa-plus" style="margin-right: 0.5rem;"></i> Create Bot
    </button>
</div>

<div class="grid-3">
    <!-- Bot Card -->
    <div class="card">
        <div class="d-flex justify-between align-center" style="margin-bottom: 1rem;">
            <h3>Welcome Bot</h3>
            <span class="text-success"><i class="fas fa-circle" style="font-size: 0.6rem;"></i> Active</span>
        </div>
        <p class="text-muted" style="margin-bottom: 1.5rem;">Greets new users and provides main menu options.</p>
        <div class="d-flex justify-between">
            <button class="btn btn-outline" style="flex: 1; margin-right: 0.5rem;">Edit</button>
            <button class="btn btn-outline" style="flex: 1;">Logs</button>
        </div>
    </div>

    <!-- Bot Card -->
    <div class="card">
        <div class="d-flex justify-between align-center" style="margin-bottom: 1rem;">
            <h3>Support Auto-Reply</h3>
            <span class="text-success"><i class="fas fa-circle" style="font-size: 0.6rem;"></i> Active</span>
        </div>
        <p class="text-muted" style="margin-bottom: 1.5rem;">Handles common FAQs and ticket creation.</p>
        <div class="d-flex justify-between">
            <button class="btn btn-outline" style="flex: 1; margin-right: 0.5rem;">Edit</button>
            <button class="btn btn-outline" style="flex: 1;">Logs</button>
        </div>
    </div>

    <!-- Bot Card -->
    <div class="card">
        <div class="d-flex justify-between align-center" style="margin-bottom: 1rem;">
            <h3>Survey Bot</h3>
            <span class="text-muted">Paused</span>
        </div>
        <p class="text-muted" style="margin-bottom: 1.5rem;">Collects user feedback after purchase.</p>
        <div class="d-flex justify-between">
            <button class="btn btn-outline" style="flex: 1; margin-right: 0.5rem;">Edit</button>
            <button class="btn btn-primary" style="flex: 1;">Enable</button>
        </div>
    </div>
</div>
@endsection
