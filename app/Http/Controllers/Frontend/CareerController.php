<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Career;

class CareerController extends Controller
{
    public function index()
    {
        $menu = Career::get()->first();
        $page=$menu;
        $content = $menu->content;
        $words = explode(' ', $content);
        $half = ceil(count($words) / 2);

        $part1 = implode(' ', array_slice($words, 0, $half));
        $part2 = implode(' ', array_slice($words, $half));

        return view('front.career.index', compact('part1', 'part2','page' ));

    }
}
