<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {

            $request->validate([
                'navbar_color' => 'required|string|max:7',
                'sidebar_color' => 'required|string|max:7',
                'button_color' => 'required|string|max:7',
                'background_color' => 'required|string|max:7',
                'navbar_text_color' => 'required|string|max:7',
                'text_color' => 'required|string|max:7',
            ]);

            $this->saveSetting('navbar_color', $request->navbar_color);
            $this->saveSetting('sidebar_color', $request->sidebar_color);
            $this->saveSetting('button_color', $request->button_color);
            $this->saveSetting('background_color', $request->background_color);
            $this->saveSetting('text_color', $request->text_color);
            $this->saveSetting('navbar_text_color', $request->navbar_text_color);

            return response()->json(['success' => true], 200);

    }

    private function saveSetting($key, $value)
    {
        $setting = Setting::firstOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        if (!$setting->wasRecentlyCreated && $setting->value !== $value) {
            $setting->value = $value;
            $setting->save();
        }
    }
}
