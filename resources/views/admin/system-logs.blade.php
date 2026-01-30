@extends('layouts.admin')

@section('title', 'System Logs')

@section('content')
<div class="card">
    <div style="height: 500px; background-color: #000; color: #0f0; font-family: monospace; padding: 1rem; overflow-y: auto; border-radius: 4px;">
        <div>[2024-05-12 10:00:01] INFO: Worker started processing job #9928.</div>
        <div>[2024-05-12 10:00:02] INFO: Connected to WA Session: Main.</div>
        <div>[2024-05-12 10:00:05] INFO: Message sent to +923001234567.</div>
        <div style="color: #f00;">[2024-05-12 10:05:00] ERROR: Rate limit exceeded for campaign #55. Pausing for 60s.</div>
        <div>[2024-05-12 10:06:00] INFO: Resuming campaign #55.</div>
        <div>[2024-05-12 10:10:00] INFO: Backup completed successfully.</div>
    </div>
</div>
@endsection
