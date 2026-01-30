@extends('layouts.admin')
@section('title', 'Channel Downloader')

@section('content')
<div class="card shadow-sm" style="border: none; border-radius: 12px; margin-bottom: 2rem;">
    <div class="card-header bg-white" style="border-bottom: 1px solid #f0f0f0; padding: 1.5rem 2rem;">
        <h3 class="mb-0" style="color: #333; font-weight: 600;">Channel Downloader</h3>
    </div>
    <div class="card-body" style="padding: 2rem;">
        @if(!$ytDlpAvailable)
            <div class="alert alert-danger shadow-sm" style="border-radius: 8px;">
                <strong><i class="fas fa-exclamation-triangle"></i> Error:</strong> <code>yt-dlp</code> is not detected on the server.
                <p class="mb-2">Please install it or ensure it is in the system PATH to use this module.</p>
                <a href="https://github.com/yt-dlp/yt-dlp/releases" target="_blank" class="btn btn-sm btn-light" style="font-weight: 500;">Download yt-dlp</a>
            </div>
        @else
            <div class="alert alert-success shadow-sm" style="border-radius: 8px; margin-bottom: 2rem;">
                <i class="fas fa-check-circle"></i> <strong>System Ready:</strong> yt-dlp is installed and detected.
            </div>
        @endif

        <form action="{{ route('admin.channel-downloader.store') }}" method="POST" class="mb-5">
            @csrf
            <div class="form-group">
                <label for="url" style="font-weight: 600; color: #555; margin-bottom: 0.5rem;">YouTube Channel URL</label>
                <div class="input-group input-group-lg">
                    <input type="url" name="url" id="url" class="form-control" placeholder="https://www.youtube.com/@ChannelName" required style="border-radius: 8px 0 0 8px; border: 1px solid #ddd; padding: 0.75rem 1.25rem;">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary" {{ !$ytDlpAvailable ? 'disabled' : '' }} style="border-radius: 0 8px 8px 0; padding: 0 2rem; font-weight: 600;">
                            <i class="fas fa-search mr-2"></i> Fetch Channel
                        </button>
                    </div>
                </div>
                <small class="text-muted mt-2 d-block"><i class="fas fa-info-circle mr-1"></i> Enter the full channel URL (e.g., https://www.youtube.com/@ChannelName). Fetching large channels may take time.</small>
            </div>
        </form>

        <h5 class="mb-3" style="font-weight: 600; color: #444;">Recent Channels</h5>
        <div class="table-responsive" style="border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
            <table class="table table-hover mb-0">
                <thead style="background-color: #f8f9fa;">
                    <tr>
                        <th style="padding: 1rem; border-bottom: 2px solid #e9ecef; color: #666;">ID</th>
                        <th style="padding: 1rem; border-bottom: 2px solid #e9ecef; color: #666;">Name</th>
                        <th style="padding: 1rem; border-bottom: 2px solid #e9ecef; color: #666;">URL</th>
                        <th style="padding: 1rem; border-bottom: 2px solid #e9ecef; color: #666;">Status</th>
                        <th style="padding: 1rem; border-bottom: 2px solid #e9ecef; color: #666;">Videos</th>
                        <th style="padding: 1rem; border-bottom: 2px solid #e9ecef; color: #666;">Created At</th>
                        <th style="padding: 1rem; border-bottom: 2px solid #e9ecef; color: #666; width: 180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($channels as $channel)
                    <tr>
                        <td style="padding: 1rem; vertical-align: middle;">{{ $channel->id }}</td>
                        <td style="padding: 1rem; vertical-align: middle; font-weight: 500;">{{ $channel->name }}</td>
                        <td style="padding: 1rem; vertical-align: middle;">
                            <a href="{{ $channel->url }}" target="_blank" style="color: #007bff; text-decoration: none;">
                                <i class="fab fa-youtube mr-1"></i> {{ Str::limit($channel->url, 30) }}
                            </a>
                        </td>
                        <td style="padding: 1rem; vertical-align: middle;">
                            <span class="badge badge-{{ $channel->status == 'completed' ? 'success' : ($channel->status == 'failed' ? 'danger' : 'warning') }}" style="padding: 0.5em 0.8em; border-radius: 20px; font-weight: 500;">
                                {{ ucfirst($channel->status) }}
                            </span>
                        </td>
                        <td style="padding: 1rem; vertical-align: middle;">{{ $channel->video_count }}</td>
                        <td style="padding: 1rem; vertical-align: middle; color: #888;">{{ $channel->created_at->diffForHumans() }}</td>
                        <td style="padding: 1rem; vertical-align: middle;">
                            <a href="{{ route('admin.channel-downloader.show', $channel) }}" class="btn btn-sm btn-info" style="border-radius: 6px; margin-right: 5px;">
                                <i class="fas fa-eye"></i> View
                            </a>
                            @if($channel->status == 'failed' || $channel->status == 'completed')
                            <form action="{{ route('admin.channel-downloader.retry', $channel) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-warning confirm-action" data-title="Retry Fetching?" data-message="This will re-scan the channel for new videos." style="border-radius: 6px;">
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
        <div class="mt-4">
            {{ $channels->links() }}
        </div>
    </div>
</div>
@endsection
