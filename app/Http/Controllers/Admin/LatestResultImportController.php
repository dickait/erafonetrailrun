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
     * Import latest race results from public/results/10k-female-master.csv.
     */
    public function importLatest()
    {
        $csvPath = public_path('results-csv/10k-female-master.csv');

        if (!file_exists($csvPath)) {
            return redirect()->back()->with('error', 'CSV file not found at public/results-csv/10k-female-master.csv');
        }

        // Truncate existing race results before importing new file to ensure fresh overwrite
        RaceResult::truncate();

        $handle = fopen($csvPath, 'r');
        if ($handle === false) {
            return redirect()->back()->with('error', 'Failed to open CSV file.');
        }

        $header = fgetcsv($handle, 1000, ',');

        if (!$header) {
            fclose($handle);
            return redirect()->back()->with('error', 'Invalid CSV header.');
        }

        // Clean headers: strip whitespace
        $header = array_map('trim', $header);

        $count = 0;
        $skipped = 0;

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

                // Parse Rank Overall
                $rankOverall = null;
                if (isset($row['Pl.'])) {
                    $rankOverall = (int)rtrim($row['Pl.'], '.');
                }

                // Update or Create RaceResult record
                RaceResult::updateOrCreate(
                    ['participant_id' => $participant->id],
                    [
                        'bib_number' => $bib,
                        'gun_time' => $row['Finish Time'] ?? null,
                        'net_time' => null, // No net time, only gun time represented by finish time
                        'distance_km' => $distance,
                        'gender' => $gender,
                        'age_category' => $ageCategory,
                        'rank_overall' => $rankOverall,
                        'status' => 'FINISHED',
                    ]
                );

                $count++;
            } else {
                $skipped++;
            }
        }

        fclose($handle);

        // Auto-calculate rankings and podiums after import
        (new \App\Http\Controllers\Admin\RaceResultController)->calculateRankingsAndPodiums();

        return redirect()->back()->with('success', "Imported {$count} results from latest CSV (Skipped/Not Found: {$skipped}).");
    }
}
