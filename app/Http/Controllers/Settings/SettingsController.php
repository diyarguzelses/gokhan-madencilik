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
                'button_color' => 'required|string|max:7',
                'background_color' => 'required|string|max:7',
                'text_color' => 'required|string|max:7',
            ]);

            $this->saveSetting('navbar_color', $request->navbar_color);
            $this->saveSetting('button_color', $request->button_color);
            $this->saveSetting('background_color', $request->background_color);
            $this->saveSetting('text_color', $request->text_color);

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
    public function personnelUpdate(Request $request)
    {
        $request->validate([
            'personnel_count' => 'required|integer|min:0',
        ]);

        $key = 'personnel_count';
        $value = $request->input('personnel_count');

        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        return response()->json(['success' => true]);
    }

}
