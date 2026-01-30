<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\AutoReply;
use Illuminate\Support\Facades\Http;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/whatsapp/webhook', function (Request $request) {
    $message = $request->input('message');
    $from = $request->input('from');
    
    if (!$message || !$from) return response()->json(['status' => 'ignored']);

    $reply = AutoReply::where('is_active', true)
        ->get()
        ->filter(function($rule) use ($message) {
            if ($rule->match_type === 'exact') {
                return strtolower(trim($message)) === strtolower(trim($rule->keyword));
            }
            return str_contains(strtolower($message), strtolower($rule->keyword));
        })
        ->first();

    if ($reply) {
        try {
            Http::post('http://localhost:3000/send-message', [
                'number' => $from,
                'message' => $reply->response
            ]);
            return response()->json(['status' => 'replied']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }

    return response()->json(['status' => 'no_match']);
});
