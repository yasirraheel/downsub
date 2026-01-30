@extends('layouts.admin')

@section('title', 'UI Elements')

@section('content')
<div class="card">
    <div style="margin-bottom: 2rem;">
        <h3>UI Library</h3>
        <p class="text-muted">A collection of reusable UI components. Copy the HTML code to use them in your views.</p>
    </div>

    <div class="grid-2">
        <!-- Uiverse Button -->
        <div class="ui-item" style="border: 1px solid #333; padding: 1.5rem; border-radius: 8px;">
            <h4 style="margin-bottom: 1rem; color: var(--primary);">Gradient Button</h4>
            
            <div style="display: flex; justify-content: center; align-items: center; padding: 2rem; background: #121212; border-radius: 4px; margin-bottom: 1rem;">
                <!-- Preview -->
                <button class="btn-uiverse">
                    <span>Hover Me</span>
                </button>
            </div>

            <div style="background: #000; padding: 1rem; border-radius: 4px; overflow-x: auto;">
                <code style="color: #0f0; font-family: monospace;">
&lt;button class="btn-uiverse"&gt;<br>
&nbsp;&nbsp;&lt;span&gt;Button Text&lt;/span&gt;<br>
&lt;/button&gt;
                </code>
            </div>
            <div style="margin-top: 0.5rem; font-size: 0.8rem; color: #888;">
                Class: <span style="color: #fff; background: #333; padding: 2px 5px; border-radius: 3px;">.btn-uiverse</span>
            </div>
        </div>

        <!-- Uiverse Loader -->
        <div class="ui-item" style="border: 1px solid #333; padding: 1.5rem; border-radius: 8px;">
            <h4 style="margin-bottom: 1rem; color: var(--primary);">SVG Ring Loader</h4>
            
            <div style="display: flex; justify-content: center; align-items: center; padding: 2rem; background: #121212; border-radius: 4px; margin-bottom: 1rem;">
                <!-- Preview -->
                <svg class="pl" viewBox="0 0 240 240">
                    <circle class="pl__ring pl__ring--a" cx="120" cy="120" r="105" fill="none" stroke-width="20" stroke-dasharray="0 660" stroke-dashoffset="-330" stroke-linecap="round"></circle>
                    <circle class="pl__ring pl__ring--b" cx="120" cy="120" r="35" fill="none" stroke-width="20" stroke-dasharray="0 220" stroke-dashoffset="-110" stroke-linecap="round"></circle>
                    <circle class="pl__ring pl__ring--c" cx="85" cy="120" r="70" fill="none" stroke-width="20" stroke-dasharray="0 440" stroke-linecap="round"></circle>
                    <circle class="pl__ring pl__ring--d" cx="155" cy="120" r="70" fill="none" stroke-width="20" stroke-dasharray="0 440" stroke-linecap="round"></circle>
                </svg>
            </div>

            <div style="background: #000; padding: 1rem; border-radius: 4px; overflow-x: auto;">
                <code style="color: #0f0; font-family: monospace;">
&lt;svg class="pl" viewBox="0 0 240 240"&gt;<br>
&nbsp;&nbsp;&lt;circle class="pl__ring pl__ring--a" cx="120" cy="120" r="105" fill="none" stroke-width="20" stroke-dasharray="0 660" stroke-dashoffset="-330" stroke-linecap="round"&gt;&lt;/circle&gt;<br>
&nbsp;&nbsp;&lt;circle class="pl__ring pl__ring--b" cx="120" cy="120" r="35" fill="none" stroke-width="20" stroke-dasharray="0 220" stroke-dashoffset="-110" stroke-linecap="round"&gt;&lt;/circle&gt;<br>
&nbsp;&nbsp;&lt;circle class="pl__ring pl__ring--c" cx="85" cy="120" r="70" fill="none" stroke-width="20" stroke-dasharray="0 440" stroke-linecap="round"&gt;&lt;/circle&gt;<br>
&nbsp;&nbsp;&lt;circle class="pl__ring pl__ring--d" cx="155" cy="120" r="70" fill="none" stroke-width="20" stroke-dasharray="0 440" stroke-linecap="round"&gt;&lt;/circle&gt;<br>
&lt;/svg&gt;
                </code>
            </div>
            <div style="margin-top: 0.5rem; font-size: 0.8rem; color: #888;">
                Class: <span style="color: #fff; background: #333; padding: 2px 5px; border-radius: 3px;">.pl</span>
            </div>
        </div>
    </div>
</div>
@endsection
