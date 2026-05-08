<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RaceResult;
use Illuminate\Http\Request;

class ResultsController extends Controller
{
    public function index(Request $request)
    {
        $query = RaceResult::with(['participant.category'])
            ->where('status', 'FINISHED');

        if ($request->has('search') && $request->search != '') {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('bib_number', 'like', "%{$search}%")
                  ->orWhereHas('participant', function ($pq) use ($search) {
                      $pq->where('full_name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('distance')) {
            $query->where('distance_km', $request->get('distance'));
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->get('gender'));
        }

        if ($request->filled('age_category')) {
            $query->where('age_category', $request->get('age_category'));
        }

        $results = $query->orderBy('rank_overall')->paginate(50)->withQueryString();

        // Get podiums for showcase (Top 3 for 10k and 15k)
        $podiums = RaceResult::where('is_podium', true)
            ->whereIn('distance_km', [10, 15])
            ->with(['participant.category'])
            ->orderBy('distance_km')
            ->orderBy('age_category')
            ->orderBy('gender')
            ->orderBy('rank_group')
            ->get()
            ->groupBy(['distance_km', 'age_category', 'gender']);

        return view('public.results.index', compact('results', 'podiums'));
    }
}
