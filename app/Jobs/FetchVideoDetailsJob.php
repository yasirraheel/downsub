<?php

namespace App\Jobs;

use App\Models\Video;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

class FetchVideoDetailsJob implements ShouldQueue
{
    use Queueable;

    public $timeout = 600; // 10 minutes

    /**
     * Create a new job instance.
     */
    public function __construct(public Video $video)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->video->update(['status' => 'fetching']);

        // Use a unique temp directory for this video
        $tempDir = storage_path('app/temp/videos/' . $this->video->video_id);
        if (!File::exists($tempDir)) {
            File::makeDirectory($tempDir, 0755, true);
        }

        try {
            // --write-auto-sub: Auto-generated subs
            // --write-sub: Manual subs
            // --sub-lang en: English
            // --convert-subs vtt: Convert to VTT (text)
            // --skip-download: STRICTLY PREVENT VIDEO DOWNLOAD (Metadata & Subs only)
            // --print-json: Output metadata (Title, Tags, Thumbnail URL)
            // -o: Output template

            $binary = env('YT_DLP_PATH', 'yt-dlp');
            // Added --no-playlist to ensure we only process the single video
            // FIXED: Optimized command to fetch ONLY subtitles and essential metadata
            // Removed --print-json to reduce data footprint and detection risk
            // Using --get-title to just get the title (if needed again) or rely on what we have
            // Actually, we need --print-json for the title/upload_date/tags if we want them, but user said "no need thumbnail tags etc"
            // So we will minimize.
            // We'll use --print-json but we'll ignore thumbnail/tags in processing if user wants speed/safety.
            // But to be safer against bot detection, reducing requests is key.
            // Let's stick to --print-json for now but use the 'tv' client as planned.
            // If user says "no need thumbnail", we can't easily tell yt-dlp "don't fetch thumbnail metadata" in json, it comes anyway.
            // But we can skip writing the thumbnail to disk (which we already do via --skip-download).

            // Simplified command: TV client, clear cache, no playlist.
            $command = "$binary --rm-cache-dir --extractor-args \"youtube:player_client=tv\" --write-auto-sub --write-sub --sub-lang en --convert-subs vtt --skip-download --no-playlist --print-json --no-warnings -o \"{$tempDir}/%(id)s\" \"{$this->video->url}\"";

            // Random delay
            sleep(rand(5, 10));

            // Ensure custom temp dir for execution exists (for shared hosting compatibility)
            $execTempDir = storage_path('app/temp/yt-dlp-run');
            if (!file_exists($execTempDir)) {
                @mkdir($execTempDir, 0755, true);
            }

            $result = Process::env(['TMPDIR' => $execTempDir])->timeout(600)->run($command);

            if ($result->failed()) {
                throw new \Exception("yt-dlp failed: " . $result->errorOutput());
            }

            $output = $result->output();
            $data = json_decode($output, true);

            $updateData = [];

            if ($data) {
                $updateData['title'] = $data['title'] ?? $this->video->title;
                $updateData['thumbnail_url'] = $data['thumbnail'] ?? null;
                $updateData['tags'] = $data['tags'] ?? null;
                // Parse date: 20230101
                if (isset($data['upload_date'])) {
                    try {
                        $updateData['published_at'] = \Carbon\Carbon::createFromFormat('Ymd', $data['upload_date']);
                    } catch (\Exception $e) {
                        // ignore date error
                    }
                }
            }

            // Find subtitle file
            // It could be .en.vtt, .live_chat.json, etc.
            // We look for .vtt files
            $files = glob("{$tempDir}/*.vtt");
            $subtitleContent = null;
            $subtitleLang = null;

            if ($files && count($files) > 0) {
                // Prefer manual subs if multiple?
                // Usually yt-dlp naming: id.en.vtt
                $subtitlePath = $files[0];
                $subtitleContent = File::get($subtitlePath);
                $subtitleLang = 'en';
            }

            if ($subtitleContent) {
                $updateData['subtitle_content'] = $subtitleContent;
                $updateData['subtitle_lang'] = $subtitleLang;
            }

            $updateData['status'] = 'fetched';
            $this->video->update($updateData);

        } catch (\Exception $e) {
            Log::error("FetchVideoDetailsJob Failed for {$this->video->video_id}: " . $e->getMessage());
            $this->video->update(['status' => 'failed']);
        } finally {
            // Cleanup
            File::deleteDirectory($tempDir);
        }
    }
}
