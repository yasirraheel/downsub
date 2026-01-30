@extends('layouts.admin')
@section('title', 'Channel Videos: ' . $channel->name)

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <a href="{{ route('admin.channel-downloader.index') }}" class="btn btn-secondary mr-2">&larr; Back</a>
        <h3 class="d-inline-block align-middle mb-0">{{ $channel->name }} <small class="text-muted">({{ $channel->videos()->count() }} Videos)</small></h3>
    </div>
    <div>
        <span class="badge badge-{{ $channel->status == 'completed' ? 'success' : ($channel->status == 'failed' ? 'danger' : 'warning') }} p-2">
            Status: {{ ucfirst($channel->status) }}
        </span>
    </div>
</div>

<div class="grid-4">
    @foreach($videos as $video)
    <div class="card h-100 shadow-sm">
        @if($video->thumbnail_url)
        <img src="{{ $video->thumbnail_url }}" class="card-img-top" alt="{{ $video->title }}" style="height: 160px; object-fit: cover;">
        @else
        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 160px;">
            <span class="text-white">No Thumbnail</span>
        </div>
        @endif
        <div class="card-body d-flex flex-column">
            <h6 class="card-title text-truncate" title="{{ $video->title }}">{{ $video->title }}</h6>
            <div class="small text-muted mb-2">
                <i class="far fa-clock"></i> {{ $video->published_at ? $video->published_at->format('M d, Y') : 'N/A' }}
                <br>
                Status: <span class="text-{{ $video->status == 'fetched' ? 'success' : ($video->status == 'failed' ? 'danger' : 'warning') }}">{{ $video->status }}</span>
            </div>

            <div class="mt-auto btn-group w-100">
                <a href="{{ $video->url }}" target="_blank" class="btn btn-sm btn-outline-danger" title="Watch on YouTube">
                    <i class="fab fa-youtube"></i>
                </a>
                @if($video->subtitle_content)
                <a href="{{ route('admin.videos.download-subtitle', $video) }}" class="btn btn-sm btn-primary" title="Download Subtitle (TXT)">
                    <i class="fas fa-file-alt"></i> TXT
                </a>
                @else
                <button class="btn btn-sm btn-secondary" disabled title="No Subtitle">
                    <i class="fas fa-file-alt"></i>
                </button>
                @endif

                <button type="button" class="btn btn-sm btn-info" onclick="showTags('{{ addslashes(json_encode($video->tags ?? [])) }}')">
                    <i class="fas fa-tags"></i>
                </button>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="mt-4">
    {{ $videos->links() }}
</div>

<script>
function showTags(tagsJson) {
    try {
        const tags = JSON.parse(tagsJson);
        if (!tags || tags.length === 0) {
            Swal.fire('No Tags', 'This video has no tags.', 'info');
            return;
        }
        Swal.fire({
            title: 'Video Tags',
            html: tags.map(t => `<span class="badge badge-secondary m-1">${t}</span>`).join(' '),
            width: 600
        });
    } catch(e) {
        console.error(e);
    }
}
</script>
@endsection
