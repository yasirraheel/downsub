@extends('layouts.admin')

@section('title', 'Campaigns')

@section('content')
<div class="d-flex justify-between align-center" style="margin-bottom: 2rem;">
    <div>
        <h3 class="text-muted">Manage broadcasting campaigns</h3>
    </div>
    <button class="btn btn-primary">
        <i class="fas fa-plus" style="margin-right: 0.5rem;"></i> New Campaign
    </button>
</div>

<div class="card">
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Campaign Name</th>
                    <th>Audience</th>
                    <th>Sent / Total</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Summer Sale Blast</td>
                    <td>All Customers (Tags: VIP)</td>
                    <td>450 / 500</td>
                    <td><span class="text-warning">Sending...</span></td>
                    <td>2024-05-10</td>
                    <td>
                        <button class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">Pause</button>
                    </td>
                </tr>
                <tr>
                    <td>New Feature Announcement</td>
                    <td>Developers</td>
                    <td>1,200 / 1,200</td>
                    <td><span class="text-success">Completed</span></td>
                    <td>2024-05-01</td>
                    <td>
                        <button class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">Report</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
