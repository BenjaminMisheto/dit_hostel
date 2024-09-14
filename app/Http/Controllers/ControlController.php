<?php

namespace App\Http\Controllers;

use App\Models\Block;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\SliderData;
use Illuminate\Support\Facades\Log; // Import Log facade


class ControlController extends Controller
{

    public function control()
{
    // Fetch blocks with floors, rooms, beds, and status
    $blocks = Block::with(['floors.rooms.beds'])
    ->where('semester_id', session('semester_id'))
    ->get();


    // Function to generate a random light color for gradients
    function randomLightColor() {
        $r = mt_rand(100, 255);
        $g = mt_rand(200, 255);
        $b = mt_rand(200, 255);

        return sprintf('#%02X%02X%02X', $r, $g, $b);
    }

    // Prepare block data with floor, bed, and status information
    $blockData = $blocks->map(function ($block) {
        $totalBedsByFloor = [];
        $floorData = [];

        foreach ($block->floors as $floor) {
            $floorId = $floor->id;
            $floorBeds = 0;
            $bedIds = [];

            foreach ($floor->rooms as $room) {
                $activeBeds = $room->beds->where('status', 'activate')->whereNull('user_id');
                $floorBeds += $activeBeds->count();
                $bedIds = array_merge($bedIds, $activeBeds->pluck('id')->toArray());
            }

            $totalBedsByFloor[$floorId] = [
                'count' => $floorBeds,
                'bed_ids' => $bedIds
            ];

            $floorEligibility = json_decode($floor->eligibility, true);

            $floorData[$floorId] = [
                'name' => $floor->floor_number,
                'criteria' => is_array($floorEligibility) ? $floorEligibility : [],
                'totalBeds' => $floorBeds,
                'bed_ids' => $bedIds,
            ];
        }

        $gradientStart = randomLightColor();
        $gradientEnd = randomLightColor();

        return [
            'id' => $block->id,
            'name' => $block->name,
            'status' => $block->status, // Include the block's status
            'totalBedsByFloor' => $totalBedsByFloor,
            'gradient_start' => $gradientStart,
            'gradient_end' => $gradientEnd,
            'floors' => $floorData,
        ];
    });

    // Fetch slider data where status = 1 and group by block_id and floor_id
    $sliderData = SliderData::where('status', 1)
        ->get()
        ->groupBy('block_id')
        ->map(function ($items) {
            return $items->groupBy('floor_id')->map(function ($items) {
                // Count bed_ids per criteria
                return $items->groupBy('criteria')->map(function ($criteriaItems) {
                    return $criteriaItems->count(); // Count of bed_ids for each criteria
                })->toArray();
            })->toArray();
        });

    // Combine block data with slider data
    $blockData = $blockData->map(function ($block) use ($sliderData) {
        $blockId = $block['id'];
        $block['sliderData'] = $sliderData[$blockId] ?? [];
        return $block;
    });

    // Check for discrepancies
    $discrepancies = $blockData->map(function ($block) use ($sliderData) {
        $blockId = $block['id'];
        $sliderFloorData = $sliderData[$blockId] ?? [];
        $discrepanciesFound = [];

        foreach ($block['totalBedsByFloor'] as $floorId => $data) {
            $sliderFloorValues = $sliderFloorData[$floorId] ?? [];
            $sliderTotalBeds = array_sum($sliderFloorValues);

            if ($sliderTotalBeds != $data['count']) {
                $discrepanciesFound[$floorId] = [
                    'expected' => $data['count'],
                    'actual' => $sliderTotalBeds,
                    'bed_ids' => $data['bed_ids']
                ];
            }
        }

        return [
            'blockId' => $blockId,
            'discrepancies' => $discrepanciesFound,
        ];
    })->filter(function ($discrepancy) {
        return !empty($discrepancy['discrepancies']);
    });



    // Return the view with combined data, discrepancies status, and block statuses
    return view('admin.control', [

        'blocks' => $blockData,
        'discrepancies' => $discrepancies,
        'discrepanciesFound' => $discrepancies->isNotEmpty(),
    ]);
}

public function store(Request $request)
{
    $request->validate([
        'data' => 'required|array',
        'data.*' => 'array',
        'data.*.*' => 'array',
        'data.*.*.sliderData' => 'array',
        'data.*.*.bedIdsData' => 'array',
        'data.*.*.sliderData.*' => 'required|integer|min:0',
        'data.*.*.bedIdsData.*' => 'array'
    ]);

    // Log the received data for debugging
   // \Log::info('Received request data: ' . json_encode($request->all()));

    // Iterate over the slider data and update or create records
    foreach ($request->input('data') as $blockId => $floors) {
        foreach ($floors as $floorId => $data) {
            try {
                // First, delete existing records for the block and floor
                SliderData::where('block_id', $blockId)
                    ->where('floor_id', $floorId)
                    ->delete();

               // \Log::info("Deleted existing records for block $blockId, floor $floorId.");
            } catch (\Exception $e) {
               // \Log::error("Failed to delete records for block $blockId, floor $floorId: " . $e->getMessage());
            }

            $sliderData = $data['sliderData'] ?? [];
            $bedIdsData = $data['bedIdsData'] ?? [];

            foreach ($sliderData as $criteria => $value) {
                // Retrieve corresponding bed IDs for this criteria
                $bedIds = $bedIdsData[$criteria] ?? [];

                // Ensure bed IDs are an array and contain values
                if (is_array($bedIds) && !empty($bedIds)) {
                    foreach ($bedIds as $bedId) {
                        try {
                            // Save each criteria and bed_id pair in separate rows
                            SliderData::updateOrCreate(
                                [
                                    'block_id' => $blockId,
                                    'floor_id' => $floorId,
                                    'criteria' => $criteria,
                                    'bed_id' => $bedId,
                                ],
                                []
                            );

                         //   \Log::info("Inserted or updated record for block $blockId, floor $floorId, criteria $criteria, bed_id $bedId.");
                        } catch (\Exception $e) {
                            \Log::error("Failed to insert or update record for block $blockId, floor $floorId, criteria $criteria, bed_id $bedId: " . $e->getMessage());
                        }
                    }
                } else {
                    \Log::warning("No bed IDs found for criteria $criteria on block $blockId, floor $floorId.");
                }
            }
        }
    }

    return response()->json(['message' => 'Slider data saved successfully.']);
}


// public function store(Request $request)
// {
//     $request->validate([
//         'data' => 'required|array',
//         'data.*' => 'array',
//         'data.*.*' => 'array',
//         'data.*.*.sliderData' => 'array',
//         'data.*.*.bedIdsData' => 'array',
//         'data.*.*.sliderData.*' => 'required|integer|min:0',
//         'data.*.*.bedIdsData.*' => 'array'
//     ]);

//     // Log the received data for debugging
//     \Log::info('Received request data: ' . json_encode($request->all()));

//     // Iterate over the slider data and update or create records
//     foreach ($request->input('data') as $blockId => $floors) {
//         foreach ($floors as $floorId => $data) {
//             $sliderData = $data['sliderData'] ?? [];
//             $bedIdsData = $data['bedIdsData'] ?? [];

//             foreach ($sliderData as $criteria => $value) {
//                 // Retrieve corresponding bed IDs for this criteria
//                 $bedIds = $bedIdsData[$criteria] ?? [];

//                 // Log the criteria and bed IDs being processed
//                 \Log::info("Processing criteria: $criteria for block: $blockId, floor: $floorId");
//                 \Log::info("Bed IDs for criteria $criteria: " . json_encode($bedIds));

//                 // Ensure bed IDs are an array and contain values
//                 if (is_array($bedIds) && !empty($bedIds)) {
//                     foreach ($bedIds as $bedId) {
//                         // Log each bed ID being inserted
//                         \Log::info("Inserting into SliderData - Block ID: $blockId, Floor ID: $floorId, Criteria: $criteria, Bed ID: $bedId");

//                         // Save each criteria and bed_id pair in separate rows
//                         SliderData::updateOrCreate(
//                             [
//                                 'block_id' => $blockId,
//                                 'floor_id' => $floorId,
//                                 'criteria' => $criteria,
//                                 'bed_id' => $bedId, // Ensure bed_id is provided
//                             ],
//                             [
//                                 // No additional fields needed if we're only saving bed_id
//                             ]
//                         );
//                     }
//                 } else {
//                     // Log an error if bed IDs are missing for the criteria
//                     \Log::error("Missing bed IDs for criteria $criteria on block $blockId, floor $floorId");
//                 }
//             }
//         }
//     }

//     return response()->json(['message' => 'Slider data saved successfully.']);
// }


}
