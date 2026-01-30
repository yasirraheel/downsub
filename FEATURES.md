# AI Agent & Developer Instructions (System Context)

**IMPORTANT FOR AI AGENTS:** Read this file FIRST before implementing new features. This project has established patterns and helper systems. **DO NOT reinvent the wheel.** Use the existing tools and components described below.

---

## 1. Project Architecture & Standards

*   **Type:** Laravel Starter Kit (Laravel 10+)
*   **Theme:** Admin Panel (Custom CSS/Blade Layouts)
*   **Database:** SQLite (Local) / MySQL (Production) - **Always use Migrations**
*   **Authentication:** Custom Admin Auth (Middleware: `IsAdmin`)

---

## 2. Global UI Components (MANDATORY USE)

### A. Notifications (Toastr.js)
**DO NOT** create custom alert divs or flash messages. The system automatically handles standard Laravel session keys.

*   **Success:** `return redirect()->back()->with('success', 'Message');` (Green Toast)
*   **Error:** `return redirect()->back()->with('error', 'Message');` (Red Toast)
*   **Warning:** `return redirect()->back()->with('warning', 'Message');` (Yellow Toast)
*   **Info:** `return redirect()->back()->with('info', 'Message');` (Blue Toast)
*   **Validation Errors:** Automatically caught and displayed as Red Toasts.

### B. Confirmations (SweetAlert2)
**DO NOT** write custom JavaScript for confirmation dialogs. Use the global `confirm-action` system.

*   **For Links:** Add class `confirm-action`.
    ```html
    <a href="/delete/1" class="confirm-action">Delete</a>
    ```
*   **For Forms:** Add class `confirm-action` to the button and `data-form-id="my-form"`.
    ```html
    <button class="confirm-action" data-form-id="delete-form-1">Delete</button>
    ```
*   **Customization:**
    ```html
    <a href="..." class="confirm-action" 
       data-title="Are you sure?" 
       data-message="This cannot be undone!" 
       data-confirm-text="Yes, do it!">Delete</a>
    ```

### C. Big Success Modal (SweetAlert2)
For major actions where a simple toast isn't enough:
```php
return redirect()->route('home')->with('sweet_success', 'Welcome!');
```

---

## 3. System Configuration (Settings)

**DO NOT** hardcode configuration values. Use the `Setting` model.

*   **Get Setting:** `\App\Models\Setting::get('key', 'default_value')`
*   **Set Setting:** `\App\Models\Setting::set('key', 'value')`
*   **Global Timezone:** The app automatically applies the timezone from settings via `SetAppTimezone` middleware.

---

## 4. System Logging

**DO NOT** create custom log viewers.
*   **View Logs:** `/admin/system-logs` (Reads `storage/logs/laravel.log`)
*   **Clear Logs:** `/admin/system-logs/clear`
*   **Writing Logs:** Use standard Laravel logging: `Log::info('Message')`, `Log::error('Error')`.

---

## 5. Development Workflow

1.  **Routes:** Define web routes in `routes/web.php`.
2.  **Controllers:** Keep controllers clean. Use Form Requests for validation if complex.
3.  **Views:** Extend `layouts.admin` for admin pages.
    ```blade
    @extends('layouts.admin')
    @section('title', 'Page Title')
    @section('content')
        ...
    @endsection
    ```
