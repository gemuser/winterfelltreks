<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function index()
    {
        $groups = SiteSetting::select('group')
            ->distinct()
            ->orderBy('group')
            ->pluck('group');

        $settings = SiteSetting::orderBy('group')
            ->orderBy('key')
            ->get()
            ->groupBy('group');

        return view('admin.settings.index', compact('groups', 'settings'));
    }

    /**
     * Return setting data as JSON for AJAX editing.
     */
    public function edit(SiteSetting $setting, Request $request)
    {
        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->json([
                'id'    => $setting->id,
                'key'   => $setting->key,
                'group' => $setting->group,
                'type'  => $setting->type,
                'value' => $setting->value,
            ]);
        }

        // Fallback to index with modal pre-opened (non-AJAX)
        $groups = SiteSetting::select('group')->distinct()->orderBy('group')->pluck('group');
        $settings = SiteSetting::orderBy('group')->orderBy('key')->get()->groupBy('group');
        return view('admin.settings.index', compact('groups', 'settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string|max:191|unique:site_settings,key',
            'group' => 'required|string|max:100',
            'type' => 'required|in:text,textarea,image,json,toggle,number',
            'value' => 'nullable',
        ]);

        SiteSetting::create($request->all());

        return back()->with('success', 'Setting created successfully.');
    }

    public function update(Request $request, SiteSetting $setting)
    {
        $request->validate([
            'group' => 'required|string|max:100',
            'type' => 'required|in:text,textarea,image,json,toggle,number',
            'value' => 'nullable',
        ]);

        $setting->update($request->only(['group', 'type', 'value']));

        return back()->with('success', 'Setting updated successfully.');
    }

    public function destroy(SiteSetting $setting)
    {
        $setting->delete();
        return back()->with('success', 'Setting deleted successfully.');
    }
}