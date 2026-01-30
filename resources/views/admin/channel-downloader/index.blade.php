@extends('layouts.admin')
@section('title', 'Channel Downloader')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Channel Downloader</h3>
    </div>
    <div class="card-body">
        @if(!$ytDlpAvailable)
            <div class="alert alert-danger">
                <strong>Error:</strong> <code>yt-dlp</code> is not detected on the server.
                <p>Please install it or ensure it is in the system PATH to use this module.</p>
                <a href="https://github.com/yt-dlp/yt-dlp/releases" target="_blank" class="btn btn-sm btn-light">Download yt-dlp</a>
            </div>
        @else
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <strong>System Ready:</strong> yt-dlp is installed and detected.
            </div>
        @endif

        <form action="{{ route('admin.channel-downloader.store') }}" method="POST" class="mb-4">
            @csrf
            <div class="form-group">
                <label for="url">YouTube Channel URL</label>
                <div class="input-group">
                    <input type="url" name="url" id="url" class="form-control" placeholder="https://www.youtube.com/@ChannelName" required>
                    <button type="submit" class="btn btn-primary" {{ !$ytDlpAvailable ? 'disabled' : '' }}>
                        <i class="fas fa-search"></i> Fetch Channel
                    </button>
                </div>
                <small class="text-muted">Enter the full channel URL (e.g., https://www.youtube.com/@ChannelName). Fetching large channels may take time.</small>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>URL</th>
                        <th>Status</th>
                        <th>Videos</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($channels as $channel)
                    <tr>
                        <td>{{ $channel->id }}</td>
                        <td>{{ $channel->name }}</td>
                        <td><a href="{{ $channel->url }}" target="_blank">{{ Str::limit($channel->url, 30) }}</a></td>
                        <td>
                            <span class="badge badge-{{ $channel->status == 'completed' ? 'success' : ($channel->status == 'failed' ? 'danger' : 'warning') }}">
                                {{ ucfirst($channel->status) }}
                            </span>
                        </td>
                        <td>{{ $channel->video_count }}</td>
                        <td>{{ $channel->created_at->diffForHumans() }}</td>
                        <td>
                            <a href="{{ route('admin.channel-downloader.show', $channel) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> View
                            </a>
                            @if($channel->status == 'failed' || $channel->status == 'completed')
                            <form action="{{ route('admin.channel-downloader.retry', $channel) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-warning confirm-action" data-title="Retry Fetching?" data-message="This will re-scan the channel for new videos.">
                                    <i class="fas fa-sync"></i> Retry
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $channels->links() }}
    </div>
</div>
@endsection
