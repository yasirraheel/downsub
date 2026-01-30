@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Campaigns</h2>
    <a href="{{ route('campaigns.create') }}" class="btn btn-primary">Create New</a>
</div>
<table class="table table-bordered">
    <thead><tr><th>ID</th><th>Title</th><th>Status</th><th>Scheduled At</th></tr></thead>
    <tbody>
        @foreach($campaigns as $c)
        <tr>
            <td>{{ $c->id }}</td>
            <td>{{ $c->title }}</td>
            <td>{{ $c->status }}</td>
            <td>{{ $c->scheduled_at }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
