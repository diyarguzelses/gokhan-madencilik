<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Page;

class DefaultPageController extends Controller
{
    public function index(){
        return view('front.defaultPage.index');
    }

    public function handleMenu($slug)
    {

        $menu = Menu::where('name', $slug)->where('is_active', 1)->firstOrFail();



        if (!empty($menu->url)) {
            return redirect($menu->url);
        }

        if ($menu->page_id) {
            $page = Page::where('id', $menu->page_id)->firstOrFail();

            $content = $page->content;
            $words = explode(' ', $content);
            $half = ceil(count($words) / 2);

            $part1 = implode(' ', array_slice($words, 0, $half));
            $part2 = implode(' ', array_slice($words, $half));

            return view('front.defaultPage.index', compact('part1', 'part2','page'));
        }

        return redirect('/');
    }
}
