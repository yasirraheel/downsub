# Laravel Starter Kit Features

This document provides a reference for the built-in features of this Laravel Starter Kit.

## 1. Global Toaster Notifications (Toastr.js)

The application comes with a pre-configured global toaster notification system using **Toastr.js**. It automatically listens for Laravel Session Flash messages.

### How to Use

In your Controller, simply use standard Laravel redirect with flash messages:

```php
// Success Notification (Green)
return redirect()->back()->with('success', 'Operation completed successfully!');

// Error Notification (Red)
return redirect()->back()->with('error', 'Something went wrong.');

// Warning Notification (Yellow)
return redirect()->back()->with('warning', 'Please check your input.');

// Info Notification (Blue)
return redirect()->back()->with('info', 'This is an informational message.');
```

**Note:** Validation errors (`$errors`) are also automatically caught and displayed as toaster error notifications.

---

## 2. Global SweetAlert2 Confirmations

A global confirmation system is implemented using **SweetAlert2**. You don't need to write custom JavaScript for every delete button.

### How to Use

#### A. For Links (GET requests)
Simply add the class `confirm-action` to any `<a>` tag.

```html
<a href="{{ route('users.delete', $user->id) }}" class="confirm-action">Delete User</a>
```

#### B. For Forms (POST/DELETE requests)
Add `confirm-action` to the button and specify the `data-form-id`.

```html
<form id="delete-form-1" action="{{ route('users.destroy', 1) }}" method="POST">
    @csrf
    @method('DELETE')
</form>

<button class="btn btn-danger confirm-action" data-form-id="delete-form-1">
    Delete User
</button>
```

#### C. Customizing the Message
You can customize the alert text using data attributes:

```html
<a href="..." 
   class="confirm-action" 
   data-title="Are you sure?" 
   data-message="This action cannot be undone!" 
   data-confirm-text="Yes, delete it!">
   Delete
</a>
```

#### D. SweetAlert Success Modal
If you prefer a large success modal instead of a small toast, use `sweet_success`:

```php
return redirect()->route('home')->with('sweet_success', 'Welcome to the dashboard!');
```

---

## 3. Dynamic Settings

The application has a built-in Settings system backed by a database table and a Helper Model.

### How to Use

#### Get a Setting
To retrieve a setting value anywhere in your code (Views or Controllers):

```php
use App\Models\Setting;

// Get value (returns null if not found)
$appName = Setting::get('app_name');

// Get value with default fallback
$appName = Setting::get('app_name', 'My App');
```

#### Set/Update a Setting
To update or create a new setting:

```php
use App\Models\Setting;

Setting::set('app_name', 'New App Name');
```

### Dynamic Logo
The logo in the sidebar automatically uses the `app_name` setting. If you update the Application Name in the Admin Settings page, the logo text will update instantly across the site.
