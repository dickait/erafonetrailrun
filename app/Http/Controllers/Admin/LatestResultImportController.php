<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use App\Models\RaceResult;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LatestResultImportController extends Controller
{
    /**
     * Import latest race results from public/results-csv/*.csv.
     */
    public function importLatest()
    {
        $csvFiles = glob(public_path('results-csv/*.csv'));

        if (empty($csvFiles)) {
            return redirect()->back()->with('error', 'No CSV files found in public/results-csv/');
        }

        // Truncate existing race results before importing new files to ensure fresh overwrite
        RaceResult::truncate();

        $count = 0;
        $skipped = 0;
        $filesImported = 0;

        foreach ($csvFiles as $csvPath) {
            $handle = fopen($csvPath, 'r');
            if ($handle === false) {
                continue;
            }

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

                $bib = $row['Bib'] ?? null;
                if (!$bib) {
                    $skipped++;
                    continue;
                }

                // Find matching participant by bib_number
                $participant = Participant::where('bib_number', $bib)->first();

                // If not found, try searching by name
                $name = $row['Name'] ?? '';
                if (!$participant && $name) {
                    $participant = Participant::where('full_name', $name)->first();
                    if ($participant) {
                        $participant->update(['bib_number' => $bib]);
                    }
                }

                // If still not found, dynamically create participant for testing/demo completeness
                if (!$participant) {
                    $event = Event::first();
                    $category = Category::first();
                    
                    // Try to find a category that matches the distance in CSV (e.g. 15K or 10K)
                    $categoryDistance = $row['Category Distance'] ?? '';
                    if (preg_match('/(\d+(?:\.\d+)?)\s*K/i', $categoryDistance, $matches)) {
                        $distVal = (float)$matches[1];
                        $matchedCategory = Category::where('distance_km', $distVal)->first();
                        if ($matchedCategory) {
                            $category = $matchedCategory;
                        }
                    }

                    if ($event && $category) {
                        $genderInput = strtolower($row['Gender'] ?? '');
                        $gender = ($genderInput === 'f' || $genderInput === 'female') ? 'female' : 'male';
                        
                        // Create dummy email
                        $emailLocal = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $name)) . '_' . $bib . '@example.com';
                        
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
                    // Parse Gender
                    $genderInput = strtolower($row['Gender'] ?? '');
                    $gender = ($genderInput === 'f' || $genderInput === 'female') ? 'female' : 'male';

                    // Parse Distance
                    $distance = null;
                    $categoryDistance = $row['Category Distance'] ?? '';
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
                            'bib_number' => $bib,
                            'gun_time' => $gunTime,
                            'net_time' => null, // No net time, only gun time represented by finish time
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
        (new \App\Http\Controllers\Admin\RaceResultController)->calculateRankingsAndPodiums();

        return redirect()->back()->with('success', "Imported {$count} results from {$filesImported} latest CSV file(s) (Skipped/Not Found: {$skipped}).");
    }
}
