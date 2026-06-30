<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use App\Models\RaceResult;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class RaceResultController extends Controller
{
    public function index()
    {
        $results = RaceResult::with('participant.category')->orderBy('bib_number')->paginate(100);
        return view('admin.results.index', compact('results'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_files' => 'required|array',
            'csv_files.*' => 'required|file|mimes:csv,txt',
        ]);

        // Truncate existing race results before importing new files to ensure fresh overwrite
        RaceResult::truncate();

        $count = 0;
        $skipped = 0;
        $filesImported = 0;

        foreach ($request->file('csv_files') as $file) {
            // Prevent ValueError: Path must not be empty on some environments
            $path = $file->getRealPath() ?: $file->getPathname();
            $handle = fopen($path, 'r');
            $header = fgetcsv($handle, 1000, ',');

            if (!$header) {
                fclose($handle);
                continue;
            }

            // Clean headers: strip BOM and whitespace
            $header = array_map(function($h) {
                return trim(preg_replace('/^[\x{FEFF}\x{200B}]+/u', '', $h));
            }, $header);

            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                // Combine headers and row data. Ensure matching lengths
                if (count($header) !== count($data)) {
                    $skipped++;
                    continue;
                }
                $row = array_combine($header, $data);
                
                // Clean row data
                $row = array_map('trim', $row);

                $name = $row['Name'] ?? $row['name'] ?? $row['full_name'] ?? null;
                $bib = $row['Bib'] ?? $row['bib'] ?? $row['bib_number'] ?? null;

                if (!$name && !$bib) {
                    $skipped++;
                    continue;
                }

                // Find matching participant by name or bib_number
                $participant = null;
                if ($name) {
                    $participant = Participant::where('full_name', $name)->first();
                }
                if (!$participant && $bib) {
                    $participant = Participant::where('bib_number', $bib)->first();
                }

                // If still not found, dynamically create participant for testing/demo completeness
                if (!$participant && $name) {
                    $event = Event::first();
                    $category = Category::first();
                    
                    $categoryDistance = $row['Category Distance'] ?? $row['category'] ?? '';
                    if (preg_match('/(\d+(?:\.\d+)?)\s*K/i', $categoryDistance, $matches)) {
                        $distVal = (float)$matches[1];
                        $matchedCategory = Category::where('distance_km', $distVal)->first();
                        if ($matchedCategory) {
                            $category = $matchedCategory;
                        }
                    }

                    if ($event && $category) {
                        $genderInput = strtolower($row['Gender'] ?? $row['gender'] ?? '');
                        $gender = ($genderInput === 'f' || $genderInput === 'female') ? 'female' : 'male';
                        
                        // Create dummy email
                        $emailLocal = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $name)) . '_' . ($bib ?? rand(1000, 9999)) . '@example.com';
                        
                        $participant = Participant::create([
                            'event_id' => $event->id,
                            'category_id' => $category->id,
                            'user_id' => null,
                            'full_name' => $name,
                            'email' => $emailLocal,
                            'phone' => '08123456789',
                            'gender' => $gender,
                            'date_of_birth' => '1995-01-01',
                            'payment_status' => 'paid',
                            'bib_number' => $bib,
                        ]);
                    }
                }

                if ($participant) {
                    // Update participant's bib_number to match the CSV if needed
                    if ($bib && $participant->bib_number !== $bib) {
                        $participant->update(['bib_number' => $bib]);
                    }

                    // Parse Gender
                    $genderInput = strtolower($row['Gender'] ?? $row['gender'] ?? '');
                    $gender = ($genderInput === 'f' || $genderInput === 'female') ? 'female' : 'male';

                    // Parse Distance
                    $distance = null;
                    $categoryDistance = $row['Category Distance'] ?? $row['category'] ?? '';
                    if (preg_match('/(\d+(?:\.\d+)?)\s*K/i', $categoryDistance, $matches)) {
                        $distance = (float)$matches[1];
                    }
                    if (!$distance && $participant->category) {
                        $distance = $participant->category->distance_km;
                    }

                    // Parse Age Category
                    $ageCategory = 'Open';
                    if (stripos($categoryDistance, 'Master') !== false) {
                        $ageCategory = 'Master';
                    } elseif (stripos($categoryDistance, 'Family') !== false) {
                        $ageCategory = 'Family';
                    } elseif ($participant->age >= 40) {
                        $ageCategory = 'Master';
                    }

                    // Clean and parse Pl. (Rank) and status
                    $plVal = trim($row['Pl.'] ?? $row['pl'] ?? $row['overall'] ?? $row['rank_overall'] ?? '');
                    $finishTimeRaw = trim($row['Finish Time'] ?? $row['gun_time'] ?? $row['finish_time'] ?? '');

                    $normalizeTime = function($timeStr) {
                        if (empty($timeStr)) {
                            return null;
                        }
                        $timeStr = trim($timeStr);
                        $parts = explode(':', $timeStr);
                        $count = count($parts);
                        if ($count === 2) {
                            $minutes = (int)$parts[0];
                            $seconds = (int)$parts[1];
                            return sprintf("00:%02d:%02d", $minutes, $seconds);
                        } elseif ($count === 3) {
                            $hours = (int)$parts[0];
                            $minutes = (int)$parts[1];
                            $seconds = (int)$parts[2];
                            return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
                        }
                        return $timeStr;
                    };

                    $status = 'FINISHED';
                    $gunTime = $finishTimeRaw !== '' ? $normalizeTime($finishTimeRaw) : null;
                    $rankOverall = null;

                    if ($plVal === 'DNS') {
                        $status = 'DNS';
                        $gunTime = null;
                    } elseif ($plVal === 'DNF') {
                        $status = 'DNF';
                        $gunTime = null;
                    } elseif ($gunTime === null) {
                        $status = 'DNS';
                    } else {
                        $cleanPl = rtrim($plVal, '.');
                        if (is_numeric($cleanPl)) {
                            $rankOverall = (int)$cleanPl;
                        }
                    }

                    // Update or Create RaceResult record
                    RaceResult::updateOrCreate(
                        ['participant_id' => $participant->id],
                        [
                            'bib_number' => $bib ?? $participant->bib_number,
                            'gun_time' => $gunTime,
                            'net_time' => $row['net_time'] ?? null, // Default to null if not provided
                            'distance_km' => $distance,
                            'gender' => $gender,
                            'age_category' => $ageCategory,
                            'rank_overall' => $rankOverall,
                            'status' => $status,
                        ]
                    );

                    $count++;
                } else {
                    $skipped++;
                }
            }

            fclose($handle);
            $filesImported++;
        }

        // Auto-calculate rankings and podiums after import
        $this->calculateRankingsAndPodiums();

        return redirect()->back()->with('success', "Imported {$count} results from {$filesImported} CSV file(s) and updated rankings (Skipped: {$skipped}).");
    }

    public function process()
    {
        $this->calculateRankingsAndPodiums();
        return redirect()->back()->with('success', 'Rankings and podiums calculated successfully.');
    }

    public function calculateRankingsAndPodiums()
    {
        DB::transaction(function () {
            // Reset podiums first
            RaceResult::query()->update(['is_podium' => false, 'rank_overall' => null, 'rank_category' => null, 'rank_group' => null]);

            // Normalize 5K age category to 'Family'
            RaceResult::where('distance_km', 5)->update(['age_category' => 'Family']);

            // Calculate Overall Rank and Category Rank (both grouped per distance category)
            $distances = RaceResult::distinct()->pluck('distance_km');
            foreach ($distances as $distance) {
                RaceResult::where('distance_km', $distance)
                    ->where('status', 'FINISHED')
                    ->whereNotNull('gun_time')
                    ->orderBy('gun_time')
                    ->get()
                    ->each(function (RaceResult $result, $index) {
                        $rank = $index + 1;
                        $result->update([
                            'rank_overall' => $rank,
                            'rank_category' => $rank
                        ]);
                    });
            }

            // 3. Group Ranking (Distance + Age Category + Gender) & Podium (excluding 5K)
            $groups = RaceResult::select('distance_km', 'age_category', 'gender')
                ->where('distance_km', '!=', 5)
                ->distinct()
                ->get();

            foreach ($groups as $group) {
                if (!$group->distance_km || !$group->age_category || !$group->gender) continue;

                RaceResult::where('distance_km', $group->distance_km)
                    ->where('age_category', $group->age_category)
                    ->where('gender', $group->gender)
                    ->where('status', 'FINISHED')
                    ->whereNotNull('gun_time')
                    ->orderBy('gun_time')
                    ->get()
                    ->each(function (RaceResult $result, $index) use ($group) {
                        $rank = $index + 1;
                        $isPodium = in_array($group->distance_km, [10, 15]) && $rank <= 3;
                        $result->update([
                            'rank_group' => $rank,
                            'is_podium' => $isPodium
                        ]);
                    });
            }

            // For 5K Family: set rank_group identical to rank_overall
            RaceResult::where('distance_km', 5)
                ->where('status', 'FINISHED')
                ->get()
                ->each(function (RaceResult $result) {
                    $result->update([
                        'rank_group' => $result->rank_overall
                    ]);
                });
        });

        Cache::forget('race_results_public_data');
    }

    public function clear()
    {
        RaceResult::truncate();
        Cache::forget('race_results_public_data');
        return redirect()->back()->with('success', 'All results cleared.');
    }
}
