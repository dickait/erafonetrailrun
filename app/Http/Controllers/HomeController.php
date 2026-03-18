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

    public function raceCourse()
    {
        $event = Event::with('categories')->where('is_active', true)->latest('event_date')->first();
        $categories = $event ? $event->categories : collect();

        return view('public.race-course', compact('event', 'categories'));
    }

    public function gallery()
    {
        $directory = storage_path('app/public/last-event-photos');
        $photos = [];
        
        if (file_exists($directory)) {
            $files = array_diff(scandir($directory), ['.', '..']);
            foreach ($files as $file) {
                if (preg_match('/\.(webp|jpg|jpeg|png)$/i', $file)) {
                    $photos[] = $file;
                }
            }
            shuffle($photos);
        }

        return view('public.gallery', compact('photos'));
    }

    public function results()
    {
        $event = Event::where('is_active', true)->latest('event_date')->first();
        return view('public.results', compact('event'));
    }
}
