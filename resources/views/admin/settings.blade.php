@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
<div class="grid-2">
    <div class="card">
        <h3>General Settings</h3>
        <form style="margin-top: 1.5rem;">
            <div class="form-group">
                <label class="form-label">Application Name</label>
                <input type="text" class="form-control" value="WaSender">
            </div>
            <div class="form-group">
                <label class="form-label">Admin Email</label>
                <input type="email" class="form-control" value="admin@wasender.com">
            </div>
            <div class="form-group">
                <label class="form-label">Timezone</label>
                <select class="form-control">
                    <option>UTC</option>
                    <option>Asia/Karachi</option>
                    <option>America/New_York</option>
                </select>
            </div>
            <button class="btn btn-primary">Save General</button>
        </form>
    </div>

    <div class="card">
        <h3>Security</h3>
        <form style="margin-top: 1.5rem;">
            <div class="form-group">
                <label class="form-label">Current Password</label>
                <input type="password" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">New Password</label>
                <input type="password" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <input type="password" class="form-control">
            </div>
            <button class="btn btn-danger">Update Password</button>
        </form>
    </div>
</div>
@endsection
