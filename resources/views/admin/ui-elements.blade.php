@extends('layouts.admin')

@section('title', 'UI Elements')

@section('content')

<!-- Add New Element Form -->
<div class="card mb-4" style="margin-bottom: 2rem;">
    <h3>Add New UI Element</h3>
    <form action="{{ route('admin.ui-elements.store') }}" method="POST">
        @csrf
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">Element Name</label>
                <input type="text" name="name" class="form-control" placeholder="e.g. Neon Button" required>
            </div>
            <div class="form-group">
                <label class="form-label">Class Name (for reference)</label>
                <input type="text" name="class_name" class="form-control" placeholder="e.g. .btn-neon">
            </div>
        </div>
        
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">HTML Code</label>
                <textarea name="html_code" class="form-control" rows="6" style="font-family: monospace; font-size: 13px;" required placeholder="<button class='btn-neon'>Click Me</button>"></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">CSS Code</label>
                <textarea name="css_code" class="form-control" rows="6" style="font-family: monospace; font-size: 13px;" required placeholder=".btn-neon { background: #0f0; ... }"></textarea>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Element
        </button>
    </form>
</div>

<div class="card">
    <div style="margin-bottom: 2rem;">
        <h3>UI Library</h3>
        <p class="text-muted">A collection of reusable UI components. Copy the HTML code to use them in your views.</p>
    </div>

    <div class="grid-2">
        <!-- Database Elements -->
        @foreach($elements as $element)
        <div class="ui-item" style="border: 1px solid #333; padding: 1.5rem; border-radius: 8px; position: relative;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                <h4 style="color: var(--primary); margin: 0;">{{ $element->name }}</h4>
                <form id="delete-form-{{ $element->id }}" action="{{ route('admin.ui-elements.destroy', $element->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-outline btn-sm confirm-action" 
                            data-form-id="delete-form-{{ $element->id }}"
                            data-title="Delete UI Element?"
                            data-message="Are you sure you want to delete '{{ $element->name }}'? This cannot be undone."
                            data-confirm-text="Yes, delete it!"
                            style="padding: 0.2rem 0.5rem; color: var(--danger); border-color: var(--danger);">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
            
            <div style="display: flex; justify-content: center; align-items: center; padding: 2rem; background: #121212; border-radius: 4px; margin-bottom: 1rem; overflow: hidden;">
                <!-- Preview (CSS is loaded via custom-ui.css) -->
                {!! $element->html_code !!}
            </div>

            <div style="background: #000; padding: 1rem; border-radius: 4px; overflow-x: auto;">
                <code style="color: #0f0; font-family: monospace; white-space: pre-wrap;">{{ $element->html_code }}</code>
            </div>
            @if($element->class_name)
            <div style="margin-top: 0.5rem; font-size: 0.8rem; color: #888;">
                Class: <span style="color: #fff; background: #333; padding: 2px 5px; border-radius: 3px;">{{ $element->class_name }}</span>
            </div>
            @endif
        </div>
        @endforeach

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
