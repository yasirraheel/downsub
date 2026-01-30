<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SystemLogController extends Controller
{
    public function index()
    {
        $logPath = storage_path('logs/laravel.log');
        $logs = [];

        if (File::exists($logPath)) {
            $content = File::get($logPath);
            // Limit to last 2000 lines or characters to avoid memory issues
            // For simplicity, let's just read the file. If it's huge, we might want to tail it.
            // Let's read the last 5MB if it's large, otherwise read all.
            
            $fileSize = File::size($logPath);
            if ($fileSize > 5 * 1024 * 1024) {
                 // Logic to read last N bytes could go here, but for now let's just warn or clear.
                 // Simple approach: read everything, but explode by new lines and take last 200.
            }
            
            // Read file into array of lines
            $fileLines = file($logPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            
            if ($fileLines) {
                 // Get last 200 lines
                 $logs = array_slice($fileLines, -200);
                 // Reverse to show newest first
                 $logs = array_reverse($logs);
            }
        }

        return view('admin.system-logs', compact('logs'));
    }

    public function clear()
    {
        $logPath = storage_path('logs/laravel.log');
        
        if (File::exists($logPath)) {
            File::put($logPath, '');
        }

        return redirect()->back()->with('success', 'System logs cleared successfully.');
    }
}
