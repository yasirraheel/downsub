<?php

namespace App\Http\Controllers;

use App\Models\AutoReply;
use Illuminate\Http\Request;

class AutoReplyController extends Controller
{
    public function index()
    {
        $autoReplies = AutoReply::latest()->get();
        return view('autoreplies.index', compact('autoReplies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'keyword' => 'required|string',
            'response' => 'required|string',
            'match_type' => 'required|in:exact,contains',
        ]);

        AutoReply::create($validated);

        return back()->with('success', 'Auto reply created successfully.');
    }

    public function destroy(AutoReply $autoReply)
    {
        $autoReply->delete();
        return back()->with('success', 'Auto reply deleted.');
    }
}
