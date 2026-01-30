<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignContact;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::latest()->get();
        return view('campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        return view('campaigns.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'phones' => 'required|string', // Comma or newline separated
            'scheduled_at' => 'nullable|date',
        ]);

        $campaign = Campaign::create([
            'title' => $validated['title'],
            'message' => $validated['message'],
            'scheduled_at' => $validated['scheduled_at'],
            'status' => 'pending'
        ]);

        $phones = preg_split('/[\r\n,]+/', $validated['phones']);
        foreach ($phones as $phone) {
            $phone = trim($phone);
            if ($phone) {
                CampaignContact::create([
                    'campaign_id' => $campaign->id,
                    'phone_number' => $phone,
                ]);
            }
        }

        return redirect()->route('campaigns.index')->with('success', 'Campaign created successfully.');
    }
}
