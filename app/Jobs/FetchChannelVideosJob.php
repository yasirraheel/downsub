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
            $command = "$binary --flat-playlist --dump-single-json \"{$this->channel->url}\"";

            $result = Process::timeout(3600)->run($command);

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
                if (!isset($entry['id'])) continue;

                $video = Video::firstOrCreate(
                    [
                        'channel_id' => $this->channel->id,
                        'video_id' => $entry['id'],
                    ],
                    [
                        'title' => $entry['title'] ?? 'Unknown Title',
                        'url' => "https://www.youtube.com/watch?v={$entry['id']}",
                        'status' => 'pending',
                    ]
                );

                // Dispatch job to fetch details for this video
                // We delay them slightly to avoid rate limiting if necessary, but sequential is fine.
                FetchVideoDetailsJob::dispatch($video);
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
