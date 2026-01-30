@extends('layouts.admin')
@section('title', 'Channel Downloader')

@section('content')

<div class="card mb-4">
    <h3>Fetch New Channel</h3>
    
    @if(!$ytDlpStatus['available'])
        <div class="alert alert-danger" style="margin-top: 1rem;">
            <strong>Error:</strong> <code>yt-dlp</code> is not working properly.
            <hr>
            <p class="mb-1"><strong>Attempted Path:</strong> <code>{{ $ytDlpStatus['path'] }}</code></p>
            <p class="mb-1"><strong>Error Details:</strong> {{ $ytDlpStatus['error'] }}</p>
            <hr>
            <p>Please check your <code>.env</code> file. Ensure <code>YT_DLP_PATH</code> is set to the absolute path of the executable.</p>
            <a href="https://github.com/yt-dlp/yt-dlp/releases" target="_blank" class="btn btn-sm btn-light">Download yt-dlp</a>
        </div>
    @else
        <div class="alert alert-success" style="margin-top: 1rem;">
            <i class="fas fa-check-circle"></i> <strong>System Ready:</strong> yt-dlp is installed (v{{ $ytDlpStatus['version'] }}).
        </div>
    @endif

    <form action="{{ route('admin.channel-downloader.store') }}" method="POST" style="margin-top: 1.5rem;">
        @csrf
        <div class="form-group">
            <label class="form-label">YouTube Channel URL</label>
            <div class="input-group">
                <input type="url" name="url" class="form-control" placeholder="https://www.youtube.com/@ChannelName" required>
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary" {{ !$ytDlpStatus['available'] ? 'disabled' : '' }}>
                        <i class="fas fa-search"></i> Fetch
                    </button>
                </div>
            </div>
            <small class="text-muted">Enter the full channel URL (e.g., https://www.youtube.com/@ChannelName).</small>
        </div>
    </form>
</div>

<div class="card">
    <h3>Channel List</h3>
    <div class="table-responsive" style="margin-top: 1.5rem;">
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
@endsection
