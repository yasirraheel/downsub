@extends('layouts.admin')
@section('title', 'Channel Videos: ' . $channel->name)

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4" style="background: #fff; padding: 1.5rem 2rem; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
    <div>
        <a href="{{ route('admin.channel-downloader.index') }}" class="btn btn-secondary mr-3" style="border-radius: 6px; font-weight: 500;">
            <i class="fas fa-arrow-left mr-1"></i> Back
        </a>
        <h3 class="d-inline-block align-middle mb-0" style="font-weight: 600; color: #333;">
            {{ $channel->name }}
            <small class="text-muted ml-2" style="font-size: 60%; font-weight: 400;">({{ $channel->videos()->count() }} Videos)</small>
        </h3>
    </div>
    <div>
        <span class="badge badge-{{ $channel->status == 'completed' ? 'success' : ($channel->status == 'failed' ? 'danger' : 'warning') }} p-2 px-3" style="border-radius: 20px; font-size: 0.9rem; font-weight: 500;">
            Status: {{ ucfirst($channel->status) }}
        </span>
    </div>
</div>

<div class="grid-4" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
    @foreach($videos as $video)
    <div class="card h-100 shadow-sm" style="border: none; border-radius: 12px; transition: transform 0.2s, box-shadow 0.2s; overflow: hidden;">
        @if($video->thumbnail_url)
        <div style="position: relative; overflow: hidden;">
            <img src="{{ $video->thumbnail_url }}" class="card-img-top" alt="{{ $video->title }}" style="height: 180px; object-fit: cover; width: 100%;">
            <a href="{{ $video->url }}" target="_blank" class="play-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.2s;">
                <i class="fab fa-youtube text-white" style="font-size: 3rem;"></i>
            </a>
        </div>
        @else
        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 180px;">
            <span class="text-white font-weight-bold">No Thumbnail</span>
        </div>
        @endif
        <div class="card-body d-flex flex-column" style="padding: 1.25rem;">
            <h6 class="card-title text-truncate mb-2" title="{{ $video->title }}" style="font-weight: 600; font-size: 1rem; color: #333;">{{ $video->title }}</h6>
            <div class="small text-muted mb-3" style="line-height: 1.6;">
                <div class="d-flex align-items-center mb-1">
                    <i class="far fa-clock mr-2" style="width: 16px; text-align: center;"></i>
                    {{ $video->published_at ? $video->published_at->format('M d, Y') : 'N/A' }}
                </div>
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle mr-2" style="width: 16px; text-align: center;"></i>
                    <span class="text-{{ $video->status == 'fetched' ? 'success' : ($video->status == 'failed' ? 'danger' : 'warning') }}" style="font-weight: 500;">{{ ucfirst($video->status) }}</span>
                </div>
            </div>

            <div class="mt-auto pt-2">
                <div class="btn-group w-100 shadow-sm" style="border-radius: 8px; overflow: hidden;">
                    <a href="{{ $video->url }}" target="_blank" class="btn btn-sm btn-outline-danger" title="Watch on YouTube" style="border: 1px solid #dc3545; padding: 0.5rem;">
                        <i class="fab fa-youtube"></i> Watch
                    </a>
                    @if($video->subtitle_content)
                    <a href="{{ route('admin.videos.download-subtitle', $video) }}" class="btn btn-sm btn-primary" title="Download Subtitle (TXT)" style="border: 1px solid #007bff; padding: 0.5rem;">
                        <i class="fas fa-file-alt mr-1"></i> TXT
                    </a>
                    @else
                    <button class="btn btn-sm btn-secondary" disabled title="No Subtitle" style="border: 1px solid #6c757d; padding: 0.5rem;">
                        <i class="fas fa-file-alt mr-1"></i> No Sub
                    </button>
                    @endif

                    <button type="button" class="btn btn-sm btn-info" onclick="showTags('{{ addslashes(json_encode($video->tags ?? [])) }}')" style="border: 1px solid #17a2b8; padding: 0.5rem;">
                        <i class="fas fa-tags"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="mt-4">
    {{ $videos->links() }}
</div>

<style>
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .card:hover .play-overlay {
        opacity: 1 !important;
    }
</style>

<script>
function showTags(tagsJson) {
    try {
        const tags = JSON.parse(tagsJson);
        if (!tags || tags.length === 0) {
            Swal.fire({
                icon: 'info',
                title: 'No Tags',
                text: 'This video has no tags.',
                confirmButtonColor: '#3085d6',
            });
            return;
        }
        Swal.fire({
            title: 'Video Tags',
            html: '<div style="text-align: left; line-height: 2;">' +
                  tags.map(t => `<span class="badge badge-secondary m-1 p-2" style="font-size: 0.9rem;">${t}</span>`).join(' ') +
                  '</div>',
            width: 600,
            confirmButtonText: 'Close',
            confirmButtonColor: '#3085d6',
        });
    } catch(e) {
        console.error(e);
    }
}
</script>
@endsection
