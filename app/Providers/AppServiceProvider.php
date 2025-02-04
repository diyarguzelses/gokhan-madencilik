<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Menu;
use Illuminate\Support\Facades\View;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (Schema::hasTable('settings')) {
            $settings = DB::table('settings')->pluck('value', 'key')->toArray();
            config(['app.settings' => $settings]);
        }
        if (Schema::hasTable('menus')) {
            $menus = Menu::where('is_active', 1)->whereNull('parent_id')->with('children')->orderBy('order')->get();
            View::share('menus', $menus);
        }
        if (Schema::hasTable('menus')) {
            $footer_menu = Menu::where('is_active', 1)
                ->whereNull('parent_id')
                ->whereHas('children')
                ->with('children')
                ->orderBy('order')
                ->get();

            View::share('footer_menu', $footer_menu);
        }    }
}
