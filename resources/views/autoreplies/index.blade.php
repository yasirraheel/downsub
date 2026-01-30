@extends('layouts.app')
@section('content')
<h2>Auto Replies</h2>
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Add New Rule</div>
            <div class="card-body">
                <form action="{{ route('autoreplies.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Keyword</label>
                        <input type="text" name="keyword" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Response</label>
                        <textarea name="response" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Match Type</label>
                        <select name="match_type" class="form-control">
                            <option value="contains">Contains</option>
                            <option value="exact">Exact Match</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Add</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <table class="table table-bordered">
            <thead><tr><th>Keyword</th><th>Response</th><th>Type</th><th>Action</th></tr></thead>
            <tbody>
                @foreach($autoReplies as $r)
                <tr>
                    <td>{{ $r->keyword }}</td>
                    <td>{{ $r->response }}</td>
                    <td>{{ $r->match_type }}</td>
                    <td>
                        <form action="{{ route('autoreplies.destroy', $r) }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
