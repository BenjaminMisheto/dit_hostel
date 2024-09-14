<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\Floor;
use App\Models\Room;
use App\Models\Bed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class FloorController extends Controller
{
    public function add($id)
    {

        // Logic for adding a floor
        return view('admin.flooradd', ['id' => $id]);
    }



    public function edit($id)
{
    // Find the floor by its ID
    $floor = Floor::findOrFail($id);

    // Get the block associated with this floor
    $blockId = $floor->block_id;

    // Assuming eligibility is stored as JSON
    $eligibilityOptions = json_decode($floor->eligibility, true);


    // Pass the block ID to the view along with the floor and eligibility options
    return view('admin.floorupdate', compact('floor', 'eligibilityOptions', 'blockId'));
}


    public function delete($id)
    {
        // Fetch the floor by ID
        $floor = Floor::findOrFail($id);



        // Pass the floor data to the view
        return view('admin.floordelete', compact('floor'));
    }



    public function updateFloor(Request $request, $id)
{
    // Log the incoming request data for debugging
    \Log::info('Incoming request data:', $request->all());

    // Normalize gender values to lowercase
    foreach ($request->input('rooms', []) as &$roomData) {
        if (isset($roomData['gender'])) {
            $roomData['gender'] = strtolower($roomData['gender']);
        }
    }

    // Validate the incoming request data
    $validatedData = $request->validate([
        'floor_number' => 'required|string|max:255',
        'number_of_rooms' => 'required|integer',
        'rooms.*.number_of_beds' => 'nullable|integer|min:0',
        'rooms.*.room_number' => 'required',
        'rooms.*.gender' => 'nullable|in:male,female',
        'gender' => 'array',
        'gender.*' => 'in:male,female',
        'eligibility' => 'array',
        'eligibility.*' => 'in:D1,D2,D3,B1,B2,B3,B4',
        'removed_rooms.*' => 'integer|exists:rooms,id',
    ]);

    // Log the validated data
    \Log::info('Validated data:', $validatedData);

    // Find the floor by ID
    $floor = Floor::findOrFail($id);

    // Update the floor details
    $floor->floor_number = $validatedData['floor_number'];
    $floor->number_of_rooms = $validatedData['number_of_rooms'];
    $floor->gender = json_encode($request->input('gender', []));
    $floor->eligibility = json_encode($request->input('eligibility', []));
    $floor->save();

    // Handle removed rooms and their beds
    $removedRooms = $request->input('removed_rooms', []);
    if (!empty($removedRooms)) {
        Room::whereIn('id', $removedRooms)->each(function ($room) {
            // Delete beds only if they are not assigned to any user
            $room->beds()->whereNull('user_id')->delete();
            $room->delete();
        });
    }

    // Track existing room IDs
    $existingRoomIds = [];

    // Handle rooms update and creation
    foreach ($request->input('rooms', []) as $roomKey => $roomData) {
        $isNewRoom = strpos($roomKey, 'new-') === 0;
        $roomNumber = $roomData['room_number'];
        $roomId = $isNewRoom ? null : (int)$roomKey;

        if ($isNewRoom) {
            $room = new Room();
            $room->floor_id = $floor->id;
            $room->room_number = $roomNumber;
            $room->gender = isset($roomData['gender']) ? strtolower($roomData['gender']) : null; // Handle gender
            $room->save();
            $roomId = $room->id;
        } else {
            $room = $floor->rooms()->find($roomId);
            if (!$room) {
                \Log::error("Room with ID $roomId not found for updating.");
                continue;
            }
            $room->room_number = $roomNumber;
            $room->gender = isset($roomData['gender']) ? strtolower($roomData['gender']) : $room->gender; // Update gender
            $room->save();
        }

        $existingRoomIds[] = $roomId;

        $currentBedCount = $room->beds()->count();
        $newBedCount = (int) $roomData['number_of_beds'];

        if ($newBedCount > $currentBedCount) {
            for ($i = $currentBedCount + 1; $i <= $newBedCount; $i++) {
                Bed::create([
                    'room_id' => $roomId,
                    'bed_number' => $i,
                ]);
            }
        } elseif ($newBedCount < $currentBedCount) {
            $room->beds()
                 ->whereNull('user_id') // Only delete beds that are not assigned to any user
                 ->orderByDesc('bed_number')
                 ->take($currentBedCount - $newBedCount)
                 ->delete();
        }

        $room->save();
    }

    // Remove rooms that are not in the request
    $floor->rooms()->whereNotIn('id', $existingRoomIds)->each(function($room) {
        // Delete beds only if they are not assigned to any user
        $room->beds()->whereNull('user_id')->delete();
        $room->delete();
    });

    return response()->json([
        'success' => true,
        'message' => 'Floor updated successfully.',
    ]);
}






public function destroy($id)
{
    try {
        // Find the floor or throw a ModelNotFoundException
        $floor = Floor::findOrFail($id);

        // Delete all associated rooms and their beds
        $floor->rooms->each(function($room) {
            // Deleting beds associated with the room
            $room->beds()->delete();
            // Deleting the room
            $room->delete();
        });

        // Delete the floor itself
        $floor->delete();

        return response()->json([
            'success' => true,
            'message' => 'Floor and associated rooms and beds deleted successfully.',
        ]);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        \Log::error('Failed to delete floor: Floor not found');
        return response()->json([
            'success' => false,
            'message' => 'Floor not found.',
        ], 404);
    } catch (\Exception $e) {
        \Log::error('Failed to delete floor: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while deleting the floor.',
        ], 500);
    }
}


public function create(Request $request, $blockId)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'floorName' => 'required|string|max:255',
            'numRooms' => 'required|integer|min:1',
            'gender' => 'required|array',
            'eligibility' => 'required|array',
            'rooms' => 'required|array',
            'rooms.*.roomNumber' => 'required|string',
            'rooms.*.bedCount' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $block = Block::find($blockId);

        if (!$block) {
            return response()->json([
                'success' => false,
                'message' => 'Block not found.',
            ], 404);
        }

        // Create a new floor
        $floor = Floor::create([
            'floor_number' => $request->floorName, // Assuming floorName as floor_number
            'number_of_rooms' => $request->numRooms,
            'gender' => json_encode($request->gender), // Save gender as JSON
            'eligibility' => json_encode($request->eligibility), // Save eligibility as JSON
            'block_id' => $blockId,
        ]);

// Assuming $request->gender is already an array
$genderArray = $request->gender;

// Check if the array contains more than one element
if (count($genderArray) > 1) {
    $genderString = null;
} else {
    // Convert the array to a string if it contains only one element
    $genderString = implode(', ', $genderArray);
}

// Create rooms and beds
foreach ($request->rooms as $roomData) {
    $room = Room::create([
        'room_number' => $roomData['roomNumber'],
        'floor_id' => $floor->id,
        'gender' => $genderString,
    ]);

    // Create beds for each room
    for ($i = 1; $i <= $roomData['bedCount']; $i++) {
        Bed::create([
            'room_id' => $room->id,
            'bed_number' => $i,
        ]);
    }
}



        return response()->json([
            'success' => true,
            'message' => 'Floor and rooms created successfully.',
        ]);
    }



}


