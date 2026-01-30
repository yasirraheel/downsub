@extends('layouts.app')
@section('content')
<h2>Create Campaign</h2>
<form action="{{ route('campaigns.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Title</label>
        <input type="text" name="title" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Message</label>
        <textarea name="message" class="form-control" rows="4" required></textarea>
    </div>
    <div class="mb-3">
        <label>Phone Numbers (comma or newline separated)</label>
        <textarea name="phones" class="form-control" rows="4" required></textarea>
    </div>
    <div class="mb-3">
        <label>Schedule (Optional)</label>
        <input type="datetime-local" name="scheduled_at" class="form-control">
    </div>
    <button type="submit" class="btn btn-success">Save</button>
</form>
@endsection
