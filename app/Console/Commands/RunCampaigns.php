<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Campaign;
use App\Models\CampaignContact;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class RunCampaigns extends Command
{
    protected $signature = 'campaign:run';
    protected $description = 'Run pending campaigns';

    public function handle()
    {
        $campaigns = Campaign::where('status', 'pending')
            ->orWhere('status', 'running')
            ->get();

        foreach ($campaigns as $campaign) {
            if ($campaign->scheduled_at && Carbon::parse($campaign->scheduled_at)->isFuture()) {
                continue;
            }

            if ($campaign->status === 'pending') {
                $campaign->update(['status' => 'running']);
            }

            // Process batch
            $contacts = $campaign->contacts()->where('status', 'pending')->take(5)->get();

            if ($contacts->isEmpty()) {
                // Check if any pending left
                if ($campaign->contacts()->where('status', 'pending')->count() === 0) {
                     $campaign->update(['status' => 'completed']);
                }
                continue;
            }

            foreach ($contacts as $contact) {
                try {
                    $response = Http::post('http://localhost:3000/send-message', [
                        'number' => $contact->phone_number,
                        'message' => $campaign->message
                    ]);

                    if ($response->successful()) {
                        $contact->update(['status' => 'sent']);
                    } else {
                        $contact->update(['status' => 'failed', 'error_message' => 'Node API Error: ' . $response->body()]);
                    }
                } catch (\Exception $e) {
                    $contact->update(['status' => 'failed', 'error_message' => $e->getMessage()]);
                }
                
                sleep(rand(2, 5)); // Random delay
            }
        }
    }
}
