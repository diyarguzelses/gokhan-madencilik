<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Career;

class CareerController extends Controller
{
    public function index()
    {
        $menu = Career::get()->first();
        $page = $menu;
        $content = $menu->content;

        // DOMDocument kullanarak HTML'i parse et
        libxml_use_internal_errors(true); // Hataları gizle
        $dom = new \DOMDocument();
        $dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));

        // Body içindeki tüm elementleri al
        $body = $dom->getElementsByTagName('body')->item(0);
        $children = [];
        foreach ($body->childNodes as $child) {
            $children[] = $dom->saveHTML($child);
        }

        // İçeriği ikiye böl
        $half = ceil(count($children) / 2);
        $part1 = implode('', array_slice($children, 0, $half));
        $part2 = implode('', array_slice($children, $half));

        return view('front.career.index', compact('part1', 'part2', 'page'));
    }

}
