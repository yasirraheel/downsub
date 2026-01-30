<?php

namespace App\Jobs;

use App\Models\Channel;
use App\Models\Video;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

class FetchChannelVideosJob implements ShouldQueue
{
    use Queueable;

    public $timeout = 3600; // 1 hour

    /**
     * Create a new job instance.
     */
    public function __construct(public Channel $channel)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->channel->update(['status' => 'processing']);

        try {
            // Fetch Channel Info and Video List
            // --flat-playlist: Don't download videos, just list them
            // --dump-single-json: Output as one JSON object
            // We use Process to run the command.
            // Note: This checks for env variable or assumes yt-dlp is in PATH.
            $binary = env('YT_DLP_PATH', 'yt-dlp');
            
            $url = $this->channel->url;
            // Fix for YouTube Channel Root URLs:
            // If the user provides "https://www.youtube.com/@Channel", yt-dlp returns the tabs (Videos, Shorts, Live) as entries.
            // We want the actual videos, so we append '/videos' to target the main video list by default.
            // We only do this if it looks like a root channel URL and not a specific playlist or video.
            if (str_contains($url, 'youtube.com/') && !str_contains($url, 'list=') && !str_contains($url, '/watch')) {
                 if (preg_match('/(@[\w\.-]+|channel\/[\w-]+)\/?$/', $url)) {
                      $url = rtrim($url, '/') . '/videos';
                      Log::info("Adjusted Channel URL to: $url");
                 }
            }

            $command = "$binary --flat-playlist --dump-single-json \"{$url}\"";
            
            // Use custom temp dir to avoid shared hosting /tmp restrictions
            $tempDir = storage_path('app/temp/yt-dlp-run');
            if (!file_exists($tempDir)) {
                @mkdir($tempDir, 0755, true);
            }

            $result = Process::env(['TMPDIR' => $tempDir])->timeout(3600)->run($command);

            if ($result->failed()) {
                throw new \Exception("yt-dlp failed: " . $result->errorOutput());
            }

            $output = $result->output();
            // Verify output is valid JSON
            if (empty($output)) {
                throw new \Exception("yt-dlp returned empty output.");
            }

            $data = json_decode($output, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                 // Fallback: sometimes yt-dlp returns multiple JSON objects if something is weird,
                 // but --dump-single-json should prevent that.
                 throw new \Exception("Failed to decode JSON: " . json_last_error_msg());
            }

            if (!isset($data['entries'])) {
                // It might be a single video URL provided as channel?
                // Or empty channel.
                // But usually it has entries.
                // If it's a list of entries directly?
                // No, dump-single-json wrapping playlist/channel wraps in object.
                Log::warning("No entries found for channel: {$this->channel->url}");
            }

            // Update Channel Name if available
            if (isset($data['title'])) {
                $this->channel->update([
                    'name' => $data['title'],
                    'channel_id' => $data['id'] ?? null,
                ]);
            }

            $entries = $data['entries'] ?? [];
            $count = 0;

            foreach ($entries as $entry) {
                // IMPORTANT: Ensure we have a valid video ID
                // Sometimes yt-dlp might return entries that are not videos or have different structures
                if (!isset($entry['id'])) continue;

                $videoId = $entry['id'];

                // CRITICAL FIX: Skip entries that are not videos (e.g., Channel or Playlist IDs)
                // Video IDs are typically 11 characters. Channel IDs are ~24 chars (UC...).
                if (strlen($videoId) !== 11) {
                    Log::info("Skipping non-video entry: {$videoId} (Title: " . ($entry['title'] ?? 'Unknown') . ")");
                    continue;
                }
                
                // Construct the URL properly
                // Sometimes 'url' field in entry is better if 'id' is just part of it
                $videoUrl = $entry['url'] ?? "https://www.youtube.com/watch?v={$videoId}";

                // If the URL is just an ID, fix it
                if (!filter_var($videoUrl, FILTER_VALIDATE_URL)) {
                     $videoUrl = "https://www.youtube.com/watch?v={$videoId}";
                }

                $video = Video::firstOrCreate(
                    [
                        'channel_id' => $this->channel->id,
                        'video_id' => $videoId,
                    ],
                    [
                        'title' => $entry['title'] ?? 'Unknown Title',
                        'url' => $videoUrl,
                        'status' => 'pending',
                    ]
                );

                // Dispatch job to fetch details for this video
                // We delay them slightly to avoid rate limiting if necessary, but sequential is fine.
                // Added progressive delay (20 seconds per video) to prevent bot detection
                FetchVideoDetailsJob::dispatch($video)->delay(now()->addSeconds($count * 20));
                $count++;
            }

            $this->channel->update([
                'video_count' => $count,
                'status' => 'completed',
            ]);

        } catch (\Exception $e) {
            Log::error("FetchChannelVideosJob Failed: " . $e->getMessage());
            $this->channel->update(['status' => 'failed']);
        }
    }
}
