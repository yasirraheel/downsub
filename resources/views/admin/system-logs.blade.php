@extends('layouts.admin')

@section('title', 'System Logs')

@section('content')

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 1rem;">
        <h3>Application Logs (Last 200 Lines)</h3>
        <form action="{{ route('admin.system-logs.clear') }}" method="POST" id="clear-logs-form">
            @csrf
            <button type="button" class="btn btn-danger confirm-action"
                data-form-id="clear-logs-form"
                data-title="Clear all logs?"
                data-message="This will permanently delete the current log file."
                data-confirm-text="Yes, clear it!">
                <i class="fas fa-trash"></i> Clear Logs
            </button>
        </form>
    </div>

    <div style="height: 600px; background-color: #1e1e1e; color: #d4d4d4; font-family: 'Consolas', 'Monaco', monospace; padding: 1rem; overflow-y: auto; border-radius: 4px; font-size: 13px; line-height: 1.5; border: 1px solid #333;">
        @if(count($logs) > 0)
            @foreach($logs as $log)
                @php
                    $color = '#d4d4d4'; // Default
                    if (str_contains($log, '.ERROR') || str_contains($log, '.CRITICAL') || str_contains($log, '.ALERT') || str_contains($log, '.EMERGENCY')) {
                        $color = '#f44336'; // Red
                    } elseif (str_contains($log, '.WARNING')) {
                        $color = '#ff9800'; // Orange
                    } elseif (str_contains($log, '.INFO')) {
                        $color = '#4caf50'; // Green
                    } elseif (str_contains($log, '.DEBUG')) {
                        $color = '#2196f3'; // Blue
                    }
                @endphp
                <div style="color: {{ $color }}; white-space: pre-wrap; margin-bottom: 2px; border-bottom: 1px solid #333; padding-bottom: 2px;">{{ $log }}</div>
            @endforeach
        @else
            <div style="text-align: center; color: #888; padding-top: 2rem;">No logs found or log file is empty.</div>
        @endif
    </div>
</div>
@endsection
