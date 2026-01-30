@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="grid-4">
    <div class="card stat-card">
        <div class="stat-value">1</div>
        <div class="stat-label">Connected Accounts</div>
    </div>
    <div class="card stat-card">
        <div class="stat-value text-success">3</div>
        <div class="stat-label">Active Bots</div>
    </div>
    <div class="card stat-card">
        <div class="stat-value text-warning">12</div>
        <div class="stat-label">Campaigns</div>
    </div>
    <div class="card stat-card">
        <div class="stat-value text-primary">1,245</div>
        <div class="stat-label">Messages Sent</div>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <h3 style="margin-bottom: 1rem;">Performance</h3>
        <div style="height: 300px; background: #222; display: flex; align-items: center; justify-content: center; border-radius: 4px; border: 1px dashed #444;">
            <span class="text-muted">Chart Placeholder (Messages/Month)</span>
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
                    <td>Campaign "Promo A" started</td>
                    <td>2 mins ago</td>
                    <td class="text-success">Running</td>
                </tr>
                <tr>
                    <td>Bot "Support" auto-reply</td>
                    <td>15 mins ago</td>
                    <td class="text-success">Sent</td>
                </tr>
                <tr>
                    <td>New device login</td>
                    <td>1 hour ago</td>
                    <td class="text-warning">Alert</td>
                </tr>
                <tr>
                    <td>System backup</td>
                    <td>Yesterday</td>
                    <td class="text-success">Completed</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
