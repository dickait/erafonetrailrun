<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RaceResult;
use Illuminate\Support\Facades\Cache;

class ResultsController extends Controller
{
    /**
     * Calculate running pace in min/km format.
     */
    public static function calculatePace($timeStr, $distance)
    {
        if (!$timeStr || !$distance || $distance <= 0) {
            return '-';
        }

        // Parse HH:MM:SS or MM:SS
        $parts = explode(':', $timeStr);
        $seconds = 0;
        if (count($parts) === 3) {
            $seconds = ((int)$parts[0] * 3600) + ((int)$parts[1] * 60) + (int)$parts[2];
        } elseif (count($parts) === 2) {
            $seconds = ((int)$parts[0] * 60) + (int)$parts[1];
        } else {
            return '-';
        }

        $secondsPerKm = $seconds / $distance;
        $paceMinutes = floor($secondsPerKm / 60);
        $paceSeconds = round($secondsPerKm % 60);

        return sprintf('%d:%02d', $paceMinutes, $paceSeconds);
    }

    public function index()
    {
        // Cache the entire results for 60 minutes
        $data = Cache::remember('race_results_public_data', 3600, function () {
            $allResults = RaceResult::with('participant:id,full_name,community')
                ->where('status', 'FINISHED')
                ->orderBy('rank_overall')
                ->get();

            $processedResults = collect();
            
            // Group by distance to calculate ranks and paces per distance
            $byDistance = $allResults->groupBy(function ($r) {
                return (string) (int) $r->distance_km;
            });

            foreach ($byDistance as $distanceVal => $resultsInDistance) {
                // Ensure results are sorted by gun_time
                $sorted = $resultsInDistance->sortBy(function ($r) {
                    return $r->gun_time ?: '99:99:99';
                })->values();

                $genderCounters = [
                    'male' => 0,
                    'female' => 0,
                ];

                foreach ($sorted as $index => $result) {
                    $gender = strtolower($result->gender);
                    if ($gender !== 'male' && $gender !== 'female') {
                        $gender = 'male';
                    }

                    $genderCounters[$gender]++;
                    $result->gender_pos = $genderCounters[$gender];

                    if (!$result->rank_category) {
                        $result->rank_category = $index + 1;
                    }

                    // Calculate pace
                    $timeForPace = $result->net_time ?: $result->gun_time;
                    $result->pace = self::calculatePace($timeForPace, (float)$result->distance_km);

                    $processedResults->push($result);
                }
            }

            // Group by distance for tabs
            $groupedResults = [
                'all' => $processedResults->sortBy('rank_overall')->values(),
                '5' => $processedResults->filter(fn($r) => (int)$r->distance_km === 5)->sortBy('rank_category')->values(),
                '10' => $processedResults->filter(fn($r) => (int)$r->distance_km === 10)->sortBy('rank_category')->values(),
                '15' => $processedResults->filter(fn($r) => (int)$r->distance_km === 15)->sortBy('rank_category')->values(),
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
