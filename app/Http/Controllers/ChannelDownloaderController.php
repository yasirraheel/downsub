<?php

namespace App\Http\Controllers;

use App\Jobs\FetchChannelVideosJob;
use App\Models\Channel;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;

class ChannelDownloaderController extends Controller
{
    public function index()
    {
        $channels = Channel::withCount('videos')->latest()->paginate(10);
        $ytDlpAvailable = $this->checkYtDlp();

        return view('admin.channel-downloader.index', compact('channels', 'ytDlpAvailable'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'url' => 'required|url|unique:channels,url',
        ]);

        if (!$this->checkYtDlp()) {
            return redirect()->back()->with('error', 'yt-dlp is not installed or not found in PATH. Please install it first.');
        }

        $channel = Channel::create([
            'url' => $request->url,
            'name' => 'Pending...',
            'status' => 'pending',
        ]);

        FetchChannelVideosJob::dispatch($channel);

        return redirect()->route('admin.channel-downloader.index')
            ->with('success', 'Channel added. Fetching started in background.');
    }

    public function show(Channel $channel)
    {
        $videos = $channel->videos()->latest('published_at')->paginate(20);
        return view('admin.channel-downloader.show', compact('channel', 'videos'));
    }

    public function retry(Channel $channel)
    {
        $channel->update(['status' => 'pending']);
        FetchChannelVideosJob::dispatch($channel);

        return redirect()->back()->with('success', 'Retry command sent.');
    }

    public function downloadSubtitle(Video $video)
    {
        if (!$video->subtitle_content) {
            return redirect()->back()->with('error', 'No subtitle content available.');
        }

        $filename = Str::slug($video->title) . '_subtitle.txt';

        return response()->streamDownload(function () use ($video) {
            echo $video->subtitle_content;
        }, $filename);
    }

    private function checkYtDlp()
    {
        try {
            $process = Process::run('yt-dlp --version');
            return $process->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}
