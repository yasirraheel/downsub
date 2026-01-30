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
        $ytDlpStatus = $this->checkYtDlpStatus();

        return view('admin.channel-downloader.index', compact('channels', 'ytDlpStatus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'url' => 'required|url|unique:channels,url',
        ]);

        $status = $this->checkYtDlpStatus();
        if (!$status['available']) {
            return redirect()->back()->with('error', 'yt-dlp error: ' . $status['error']);
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

    public function destroy(Channel $channel)
    {
        $channel->delete(); // Cascading delete handles videos
        return redirect()->route('admin.channel-downloader.index')
            ->with('success', 'Channel and its videos deleted successfully.');
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

    private function checkYtDlpStatus()
    {
        $binary = env('YT_DLP_PATH', 'yt-dlp');

        // Define a custom temp directory for yt-dlp execution
        // This solves "failed to map segment from shared object" errors on shared hosting
        $tempDir = storage_path('app/temp/yt-dlp-run');
        if (!file_exists($tempDir)) {
            @mkdir($tempDir, 0755, true);
        }

        try {
            // Pass TMPDIR environment variable to override system /tmp
            $process = Process::env(['TMPDIR' => $tempDir])->run("$binary --version");

            if ($process->successful()) {
                return ['available' => true, 'path' => $binary, 'version' => trim($process->output())];
            }
            return ['available' => false, 'path' => $binary, 'error' => $process->errorOutput() ?: 'Unknown error (exit code ' . $process->exitCode() . ')'];
        } catch (\Exception $e) {
            return ['available' => false, 'path' => $binary, 'error' => $e->getMessage()];
        }
    }
}
