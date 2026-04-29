<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function index()
    {
        $settings = DB::table('settings')->pluck('value', 'key');

        if (!isset($settings['app_name']) && isset($settings['project_name'])) {
            $settings['app_name'] = $settings['project_name'];
        }

        if (!isset($settings['app_email']) && isset($settings['project_email'])) {
            $settings['app_email'] = $settings['project_email'];
        }

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'project_description' => 'nullable|string|max:1000',
            'app_email' => 'required|email|max:255',
            'support_phone' => 'nullable|string|max:20',
            'support_address' => 'nullable|string|max:500',
            'terms_title' => 'nullable|string|max:255',
            'terms_content' => 'nullable|string|max:10000',
            'faq_title' => 'nullable|string|max:255',
            'faq_content' => 'nullable|string|max:10000',
            'privacy_policy_title' => 'nullable|string|max:255',
            'privacy_policy_content' => 'nullable|string|max:10000',
            'return_policy_title' => 'nullable|string|max:255',
            'return_policy_content' => 'nullable|string|max:10000',
        ]);

        $settings = [
            'app_name',
            'project_description',
            'app_email',
            'support_phone',
            'support_address',
            'terms_title',
            'terms_content',
            'faq_title',
            'faq_content',
            'privacy_policy_title',
            'privacy_policy_content',
            'return_policy_title',
            'return_policy_content',
        ];

        foreach ($settings as $key) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $request->input($key), 'updated_at' => now()]
            );
        }

        DB::table('settings')->whereIn('key', ['project_name', 'project_email'])->delete();

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.')
            ->with('active_tab', $request->input('active_tab', 'general'));
    }
}
