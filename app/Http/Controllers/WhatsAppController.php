<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WhatsAppController extends Controller
{
    private $nodeUrl = 'http://localhost:3000';

    public function index()
    {
        return view('dashboard');
    }

    public function getStatus()
    {
        try {
            $response = Http::timeout(3)->get($this->nodeUrl . '/status');
            return $response->json();
        } catch (\Exception $e) {
            return response()->json(['status' => 'disconnected', 'message' => 'Server unreachable']);
        }
    }

    public function getQr()
    {
        try {
            $response = Http::timeout(5)->get($this->nodeUrl . '/qr');
            return $response->json();
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Server unreachable']);
        }
    }

    public function logout()
    {
        try {
            $response = Http::post($this->nodeUrl . '/logout');
            return $response->json();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to logout']);
        }
    }

    public function showSendMessage()
    {
        return view('message.create');
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'number' => 'required',
            'message' => 'required'
        ]);

        try {
            $response = Http::post($this->nodeUrl . '/send-message', [
                'number' => $request->number,
                'message' => $request->message
            ]);

            if ($response->successful()) {
                return back()->with('success', 'Message sent successfully!');
            } else {
                return back()->withErrors(['error' => 'Failed to send message: ' . $response->body()]);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error connecting to WhatsApp server']);
        }
    }
}
