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
        $menu = Menu::where('name', $slug)
            ->where('is_active', 1)
            ->firstOrFail();

        if (!empty($menu->url)) {
            return redirect($menu->url);
        }

        if ($menu->page_id) {
            $page = Page::with('images')->findOrFail($menu->page_id);

            $content = $page->content;

            // DOMDocument kullanarak HTML içeriğini parse et
            libxml_use_internal_errors(true); // Hataları gizle
            $dom = new \DOMDocument();
            $dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));

            // Body içindeki tüm elementleri al
            $body = $dom->getElementsByTagName('body')->item(0);
            $children = [];
            foreach ($body->childNodes as $child) {
                $children[] = $dom->saveHTML($child);
            }

            // İçeriği iki parçaya böl
            $half = ceil(count($children) / 2);
            $part1 = implode('', array_slice($children, 0, $half));
            $part2 = implode('', array_slice($children, $half));

            // Resimleri al
            $image1 = $page->images->first();
            $image2 = $page->images->skip(1)->first();

            return view('front.defaultPage.index', compact('part1', 'part2', 'page', 'image1', 'image2'));
        }


        return redirect('/');
    }
}
