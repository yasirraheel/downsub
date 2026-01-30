@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="grid-4">
    <div class="card stat-card">
        <div class="stat-value">0</div>
        <div class="stat-label">Users</div>
    </div>
    <div class="card stat-card">
        <div class="stat-value text-success">0</div>
        <div class="stat-label">Active Sessions</div>
    </div>
    <div class="card stat-card">
        <div class="stat-value text-warning">0</div>
        <div class="stat-label">Pending Items</div>
    </div>
    <div class="card stat-card">
        <div class="stat-value text-primary">0</div>
        <div class="stat-label">Total Actions</div>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <h3 style="margin-bottom: 1rem;">Performance</h3>
        <div style="height: 300px; background: #222; display: flex; align-items: center; justify-content: center; border-radius: 4px; border: 1px dashed #444;">
            <span class="text-muted">Chart Placeholder</span>
        </div>
    </div>
    <div class="card">
        <h3 style="margin-bottom: 1rem;">Recent Activity</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="3" class="text-center text-muted">No recent activity</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
