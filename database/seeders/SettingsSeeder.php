<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $defaultSettings = [
            ['key' => 'navbar_color', 'value' => '#1e3c72'],      // Navbar varsayılan rengi
            ['key' => 'sidebar_color', 'value' => '#2a5298'],    // Sidebar varsayılan rengi
            ['key' => 'button_color', 'value' => '#ff7e5f'],     // Buton varsayılan rengi
            ['key' => 'background_color', 'value' => '#ffffff'], // Arka plan varsayılan rengi
            ['key' => 'nav_text_color', 'value' => '#ffffff'], //  nav text varsayılan rengi
            ['key' => 'text_color', 'value' => '#ffffff'], // text plan varsayılan rengi
        ];

        foreach ($defaultSettings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], ['value' => $setting['value']]);
        }
    }
}
