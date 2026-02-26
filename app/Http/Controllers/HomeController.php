<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $event = Event::with('categories')->where('is_active', true)->latest('event_date')->first();
        $categories = $event ? $event->categories : collect();

        return view('public.home', compact('event', 'categories'));
    }

    public function gallery()
    {
        return view('public.gallery');
    }

    public function results()
    {
        $event = Event::where('is_active', true)->latest('event_date')->first();
        return view('public.results', compact('event'));
    }
}
