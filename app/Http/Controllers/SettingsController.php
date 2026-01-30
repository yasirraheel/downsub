<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        $timezones = \DateTimeZone::listIdentifiers();
        return view('admin.settings', compact('settings', 'timezones'));
    }

    public function updateGeneral(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255',
            'timezone' => 'required|string|max:255',
        ]);

        Setting::set('app_name', $request->app_name);
        Setting::set('admin_email', $request->admin_email);
        Setting::set('timezone', $request->timezone);

        return redirect()->back()->with('success', 'General settings updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->back()->with('success', 'Password updated successfully.');
    }
}
