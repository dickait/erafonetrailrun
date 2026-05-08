<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RaceResult;
use Illuminate\Support\Facades\Cache;

class ResultsController extends Controller
{
    public function index()
    {
        // Cache the entire results for 60 minutes
        $data = Cache::remember('race_results_public_data', 3600, function () {
            $allResults = RaceResult::with('participant:id,full_name')
                ->where('status', 'FINISHED')
                ->orderBy('rank_overall')
                ->get();

            // Group by distance for tabs
            $groupedResults = [
                '5' => $allResults->where('distance_km', 5)->values(),
                '10' => $allResults->where('distance_km', 10)->values(),
                '15' => $allResults->where('distance_km', 15)->values(),
            ];

            // Podium data
            $podiums = RaceResult::with('participant:id,full_name')
                ->where('is_podium', true)
                ->orderBy('distance_km', 'desc')
                ->orderBy('age_category')
                ->orderBy('gender')
                ->orderBy('rank_group')
                ->get()
                ->groupBy(['distance_km', 'age_category', 'gender']);

            return [
                'groupedResults' => $groupedResults,
                'podiums' => $podiums,
                'totalCount' => $allResults->count()
            ];
        });

        return view('public.results.index', [
            'groupedResults' => $data['groupedResults'],
            'podiums' => $data['podiums'],
            'totalCount' => $data['totalCount']
        ]);
    }
}
