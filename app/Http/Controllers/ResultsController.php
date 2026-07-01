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
                ->whereIn('status', ['FINISHED', 'DNF', 'DNS'])
                ->get();

            $processedResults = collect();
            
            // Group by distance to calculate ranks and paces per distance
            $byDistance = $allResults->groupBy(function ($r) {
                return (string) (int) $r->distance_km;
            });

            foreach ($byDistance as $distanceVal => $resultsInDistance) {
                // Ensure results are sorted: FINISHED (by gun_time) -> DNF -> DNS
                $sorted = $resultsInDistance->sort(function ($a, $b) {
                    $statusOrder = ['FINISHED' => 1, 'DNF' => 2, 'DNS' => 3];
                    $aStatus = $statusOrder[$a->status] ?? 1;
                    $bStatus = $statusOrder[$b->status] ?? 1;
                    
                    if ($aStatus !== $bStatus) {
                        return $aStatus <=> $bStatus;
                    }
                    
                    if ($a->status === 'FINISHED') {
                        $aTime = $a->gun_time ?: '99:99:99';
                        $bTime = $b->gun_time ?: '99:99:99';
                        return strcmp($aTime, $bTime);
                    }
                    
                    return $a->bib_number <=> $b->bib_number;
                })->values();

                $genderCounters = [
                    'male' => 0,
                    'female' => 0,
                ];

                $finishersCount = 0;
                foreach ($sorted as $result) {
                    if ($result->status === 'FINISHED') {
                        $finishersCount++;
                        $gender = strtolower($result->gender);
                        if ($gender !== 'male' && $gender !== 'female') {
                            $gender = 'male';
                        }

                        $genderCounters[$gender]++;
                        $result->gender_pos = $genderCounters[$gender];

                        if (!$result->rank_category) {
                            $result->rank_category = $finishersCount;
                        }

                        // Calculate pace
                        $timeForPace = $result->net_time ?: $result->gun_time;
                        $result->pace = self::calculatePace($timeForPace, (float)$result->distance_km);
                    } else {
                        $result->gender_pos = null;
                        $result->rank_category = null;
                        $result->rank_overall = null;
                        $result->rank_group = null;
                        $result->pace = '-';
                    }

                    $processedResults->push($result);
                }
            }

            // Custom sort function for tabs
            $sortResults = function ($collection) {
                return $collection->sort(function ($a, $b) {
                    $statusOrder = ['FINISHED' => 1, 'DNF' => 2, 'DNS' => 3];
                    $aStatus = $statusOrder[$a->status] ?? 1;
                    $bStatus = $statusOrder[$b->status] ?? 1;
                    if ($aStatus !== $bStatus) {
                        return $aStatus <=> $bStatus;
                    }
                    if ($a->status === 'FINISHED') {
                        $aRank = $a->rank_category ?: $a->rank_overall ?: 999999;
                        $bRank = $b->rank_category ?: $b->rank_overall ?: 999999;
                        return $aRank <=> $bRank;
                    }
                    return $a->bib_number <=> $b->bib_number;
                })->values();
            };

            // Group by distance for tabs
            $groupedResults = [
                'all' => $processedResults->sort(function ($a, $b) {
                    $statusOrder = ['FINISHED' => 1, 'DNF' => 2, 'DNS' => 3];
                    $aStatus = $statusOrder[$a->status] ?? 1;
                    $bStatus = $statusOrder[$b->status] ?? 1;
                    if ($aStatus !== $bStatus) {
                        return $aStatus <=> $bStatus;
                    }
                    if ($a->status === 'FINISHED') {
                        return ($a->rank_overall ?: 999999) <=> ($b->rank_overall ?: 999999);
                    }
                    return $a->bib_number <=> $b->bib_number;
                })->values(),
                '5' => $sortResults($processedResults->filter(fn($r) => (int)$r->distance_km === 5)),
                '10' => $sortResults($processedResults->filter(fn($r) => (int)$r->distance_km === 10)),
                '15' => $sortResults($processedResults->filter(fn($r) => (int)$r->distance_km === 15)),
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
                'totalCount' => $allResults->where('status', 'FINISHED')->count()
            ];
        });

        return view('public.results.index', [
            'groupedResults' => $data['groupedResults'],
            'podiums' => $data['podiums'],
            'totalCount' => $data['totalCount']
        ]);
    }
}
