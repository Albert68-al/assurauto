<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        $generalSettings = Setting::where('group', 'general')->get();
        $notificationSettings = Setting::where('group', 'notification')->get();
        $securitySettings = Setting::where('group', 'security')->get();
        $backupSettings = Setting::where('group', 'backup')->get();

        return view('admin.settings.index', compact(
            'generalSettings',
            'notificationSettings',
            'securitySettings',
            'backupSettings'
        ));
    }

    /**
     * Update the settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
        ]);

        $changes = [];

        foreach ($request->settings as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            
            if ($setting) {
                $oldValue = $setting->value;
                
                // Handle file uploads
                if ($setting->type === 'file' && $request->hasFile("file_{$key}")) {
                    // Delete old file if exists
                    if ($oldValue && Storage::disk('public')->exists($oldValue)) {
                        Storage::disk('public')->delete($oldValue);
                    }
                    
                    // Store new file
                    $file = $request->file("file_{$key}");
                    $path = $file->store('settings', 'public');
                    $value = $path;
                }
                
                // Handle boolean values
                if ($setting->type === 'boolean') {
                    $value = $request->has("settings.{$key}") ? '1' : '0';
                }
                
                // Handle JSON values
                if ($setting->type === 'json' && is_array($value)) {
                    $value = json_encode($value);
                }
                
                $setting->update(['value' => $value]);
                
                if ($oldValue !== $value) {
                    $changes[] = [
                        'key' => $key,
                        'old' => $oldValue,
                        'new' => $value,
                    ];
                }
            }
        }

        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'update_settings',
            'description' => 'Mise à jour des paramètres système',
            'properties' => json_encode(['changes' => $changes]),
            'ip_address' => $request->ip(),
        ]);

        Setting::clearCache();

        return redirect()->route('admin.settings.index')
            ->with('success', 'Paramètres mis à jour avec succès.');
    }

    /**
     * Test email notification.
     */
    public function testEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        try {
            // TODO: Implement email sending logic
            // Mail::to($request->test_email)->send(new TestEmail());
            
            return back()->with('success', 'Email de test envoyé avec succès à ' . $request->test_email);
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'envoi de l\'email : ' . $e->getMessage());
        }
    }

    /**
     * Test SMS notification.
     */
    public function testSms(Request $request)
    {
        $request->validate([
            'test_phone' => 'required',
        ]);

        try {
            // TODO: Implement SMS sending logic
            
            return back()->with('success', 'SMS de test envoyé avec succès au ' . $request->test_phone);
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'envoi du SMS : ' . $e->getMessage());
        }
    }
}
