<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use App\Models\RaceResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle, 1000, ',');

        if (!$header) {
            return redirect()->back()->with('error', 'Invalid CSV file.');
        }

        $count = 0;
        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            $row = array_combine($header, $data);
            
            $bib = $row['bib_number'] ?? $row['bib'] ?? null;
            if (!$bib) continue;

            $participant = Participant::where('bib_number', $bib)->first();
            
            if ($participant) {
                RaceResult::updateOrCreate(
                    ['participant_id' => $participant->id],
                    [
                        'bib_number' => $bib,
                        'gun_time' => $row['gun_time'] ?? null,
                        'net_time' => $row['net_time'] ?? null,
                        'status' => $row['status'] ?? 'FINISHED',
                        'distance_km' => $participant->category->distance_km,
                        'gender' => $participant->gender,
                        'age_category' => $participant->age >= 40 ? 'Master' : 'Open',
                    ]
                );
                $count++;
            }
        }
        fclose($handle);

        return redirect()->back()->with('success', "Imported {$count} results.");
    }

    public function process()
    {
        DB::transaction(function () {
            // Reset podiums first
            RaceResult::query()->update(['is_podium' => false, 'rank_overall' => null, 'rank_category' => null, 'rank_group' => null]);

            // 1. Overall Ranking
            RaceResult::where('status', 'FINISHED')
                ->whereNotNull('gun_time')
                ->orderBy('gun_time')
                ->get()
                ->each(function (RaceResult $result, $index) {
                    $result->update(['rank_overall' => $index + 1]);
                });

            // 2. Category (Distance) Ranking
            $distances = RaceResult::distinct()->pluck('distance_km');
            foreach ($distances as $distance) {
                RaceResult::where('distance_km', $distance)
                    ->where('status', 'FINISHED')
                    ->whereNotNull('gun_time')
                    ->orderBy('gun_time')
                    ->get()
                    ->each(function (RaceResult $result, $index) {
                        $result->update(['rank_category' => $index + 1]);
                    });
            }

            // 3. Group Ranking (Distance + Age Category + Gender) & Podium
            $groups = RaceResult::select('distance_km', 'age_category', 'gender')
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
        });

        return redirect()->back()->with('success', 'Rankings and podiums calculated successfully.');
    }

    public function clear()
    {
        RaceResult::truncate();
        return redirect()->back()->with('success', 'All results cleared.');
    }
}
