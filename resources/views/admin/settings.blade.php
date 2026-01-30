@extends('layouts.admin')

@section('title', 'Settings')

@section('content')

<div class="grid-2">
    <div class="card">
        <h3>General Settings</h3>
        <form action="{{ route('admin.settings.updateGeneral') }}" method="POST" style="margin-top: 1.5rem;">
            @csrf
            <div class="form-group">
                <label class="form-label">Application Name</label>
                <input type="text" name="app_name" class="form-control" value="{{ $settings['app_name'] ?? 'Laravel Starter' }}">
            </div>
            <div class="form-group">
                <label class="form-label">Admin Email</label>
                <input type="email" name="admin_email" class="form-control" value="{{ $settings['admin_email'] ?? '' }}">
            </div>
            <div class="form-group">
                <label class="form-label">Timezone</label>
                <select name="timezone" class="form-control">
                    <option value="UTC" {{ ($settings['timezone'] ?? '') == 'UTC' ? 'selected' : '' }}>UTC</option>
                    <option value="Asia/Karachi" {{ ($settings['timezone'] ?? '') == 'Asia/Karachi' ? 'selected' : '' }}>Asia/Karachi</option>
                    <option value="America/New_York" {{ ($settings['timezone'] ?? '') == 'America/New_York' ? 'selected' : '' }}>America/New_York</option>
                    <option value="Europe/London" {{ ($settings['timezone'] ?? '') == 'Europe/London' ? 'selected' : '' }}>Europe/London</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Save General</button>
        </form>
    </div>

    <div class="card">
        <h3>Security</h3>
        <form action="{{ route('admin.settings.updatePassword') }}" method="POST" style="margin-top: 1.5rem;">
            @csrf
            <div class="form-group">
                <label class="form-label">Current Password</label>
                <input type="password" name="current_password" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">New Password</label>
                <input type="password" name="new_password" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="new_password_confirmation" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-danger">Update Password</button>
        </form>
    </div>
</div>
@endsection
