<?php

namespace App\Http\Controllers;

use App\Models\WhatsAppAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AdminWhatsAppController extends Controller
{
    public function index()
    {
        $accounts = WhatsAppAccount::latest()->get();
        return view('admin.wa-accounts', compact('accounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
        ]);

        $sessionId = Str::random(10);

        $account = WhatsAppAccount::create([
            'session_id' => $sessionId,
            'name' => $request->name ?? 'WhatsApp Account ' . (WhatsAppAccount::count() + 1),
            'status' => 'disconnected',
        ]);

        // Trigger Node.js to init session
        try {
            Http::post('http://localhost:3000/sessions/init', [
                'sessionId' => $sessionId,
            ]);
        } catch (\Exception $e) {
            // Node might not be running
        }

        return redirect()->route('admin.wa-accounts')->with('success', 'Account created. Please scan QR code.');
    }

    public function destroy($id)
    {
        $account = WhatsAppAccount::findOrFail($id);

        // Trigger Node.js to delete session
        try {
            Http::post('http://localhost:3000/sessions/delete', [
                'sessionId' => $account->session_id,
            ]);
        } catch (\Exception $e) {
            // Ignore
        }

        $account->delete();
        return redirect()->route('admin.wa-accounts')->with('success', 'Account removed successfully.');
    }

    public function getQr($sessionId)
    {
        try {
            $response = Http::get("http://localhost:3000/sessions/{$sessionId}/status");

            // If session not found in Node (e.g. server restart), try to re-init
            if ($response->status() === 404) {
                try {
                    Http::post('http://localhost:3000/sessions/init', [
                        'sessionId' => $sessionId,
                    ]);
                    return response()->json(['status' => 'initializing', 'qr' => null, 'message' => 'Initializing session...']);
                } catch (\Exception $e) {
                    return response()->json(['error' => 'Failed to initialize session'], 500);
                }
            }

            if ($response->successful()) {
                $data = $response->json();

                // If session exists but is disconnected, try to wake it up
                if ($data['status'] === 'disconnected') {
                     try {
                        Http::post('http://localhost:3000/sessions/init', [
                            'sessionId' => $sessionId,
                        ]);
                        return response()->json(['status' => 'initializing', 'qr' => null, 'message' => 'Restarting session...']);
                    } catch (\Exception $e) {
                        // Ignore error, will retry next poll
                    }
                }

                // Update DB status if changed
                $account = WhatsAppAccount::where('session_id', $sessionId)->first();
                if ($account && $account->status !== $data['status']) {
                    $account->update(['status' => $data['status']]);
                    if(isset($data['phoneNumber'])) {
                        $account->update(['phone_number' => $data['phoneNumber']]);
                    }
                }

                return response()->json($data);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Node server unavailable: ' . $e->getMessage()], 503);
        }

        return response()->json(['status' => 'disconnected', 'qr' => null]);
    }
}
