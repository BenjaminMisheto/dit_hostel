<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Block;
use App\Models\Bed;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use App\Models\CheckOutItem;
use App\Models\Requirement;

use App\Models\RequirementItemConfirmation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class RoomController extends Controller
{

    /**
     * Display the specified block's rooms.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
     public function show($id)
{
    // Retrieve the block with its floors and rooms
    $block = Block::with('floors.rooms.beds')->find($id);

    // Check if the block exists
    if (!$block) {
        abort(404, 'Block not found.');
    }

    // Calculate the total number of beds
    $totalBeds = $block->floors->reduce(function ($carry, $floor) {
        return $carry + $floor->rooms->reduce(function ($carry, $room) {
            return $carry + $room->beds->count();
        }, 0);
    }, 0);




// Calculate the number of beds with different statuses
$totalOccupiedBeds = Bed::whereHas('user', function($query) {
        $query->where('semester_id', session('semester_id'));
    })
    ->whereNotNull('user_id') // Fixed the missing '->' here
    ->whereHas('room.floor.block', function ($query) use ($id) {
        $query->where('id', $id);
    })
    ->count();




    $totalOpenBeds = Bed::where('status', 'activate')
        ->whereHas('room.floor.block', function ($query) use ($id) {
            $query->where('id', $id);
        })
        ->count();

    $totalReservedBeds = Bed::where('status', 'reserve')
        ->whereHas('room.floor.block', function ($query) use ($id) {
            $query->where('id', $id);
        })
        ->count();

    $totalUnderMaintenanceBeds = Bed::where('status', 'under_maintenance')
        ->whereHas('room.floor.block', function ($query) use ($id) {
            $query->where('id', $id);
        })
        ->count();

    // Calculate the percentage of occupied beds
    $occupancyPercentage = $totalBeds > 0 ? ($totalOccupiedBeds / $totalBeds) * 100 : 0;

    // Initialize arrays to collect gender and eligibility data for the entire block
    $blockGenders = [];
    $blockEligibility = [];

    // Loop through each floor to aggregate gender and eligibility data
    foreach ($block->floors as $floor) {
        $genders = json_decode($floor->gender, true);
        $eligibility = json_decode($floor->eligibility, true);

        // Merge and remove duplicates
        $blockGenders = array_unique(array_merge($blockGenders, $genders));
        $blockEligibility = array_unique(array_merge($blockEligibility, $eligibility));
    }

    // Retrieve check-out items and requirements
    $checkOutItems = CheckOutItem::where('block_id', $id)->get();
    $requirements = Requirement::where('block_id', $id)->get();

    // Pass data to the view
    return view('admin.room', compact(
        'block',
        'totalBeds',
        'totalOccupiedBeds',
        'totalOpenBeds',
        'totalReservedBeds',
        'totalUnderMaintenanceBeds',
        'blockGenders',
        'blockEligibility',
        'occupancyPercentage',
        'checkOutItems',
        'requirements'
    ));
}




public function showroomitem($id)
{
// Fetch the room with users, ordered by 'id' in descending order
$room = Room::with(['users' => function($query) {
    $query->where('semester_id', session('semester_id')) // Correct placement of the where clause
          ->orderBy('id', 'desc');  // Order users by 'id' in descending order
}])->findOrFail($id);


    // Fetch the block related to the room and its related entities (floors, rooms, beds)
    // Since Room belongs to Floor and Floor belongs to Block, we can use the relationships
    $block = $room->floor->block()->with('floors.rooms.beds')->first();

    // Fetch checkout items for the block
    $checkOutItems = CheckOutItem::where('room_id', $id)->get();

    // Pass the room, block, and checkout items to the view
    return view('admin.roomitem', compact('room', 'checkOutItems', 'block'));
}






public function showBed($bedId)
{
    // Retrieve the status from the query string
    $status = request()->query('status', 0);

    // Eager load the related data: room, floor, block
    $bed = Bed::with(['room.floor.block'])->findOrFail($bedId);

    // Load the user associated with the bed using user_id
    $user = User::find($bed->user_id);

    // Log the bed and user information for debugging
    Log::info($bed);


    // Return the view with the bed data, status, and user data
    return view('admin.bed', compact('bed', 'status', 'user'));
}







public function saveCheckOutItems(Request $request)
{
    $requirements = $request->input('requirements');
    $blockId = $request->input('block_id');

    // Check if block_id is provided
    if (!$blockId) {
        return response()->json(['success' => false, 'message' => 'Block ID is required.']);
    }

    // Validate that at least one requirement must exist
    if (empty($requirements)) {
        return response()->json(['success' => false, 'message' => 'At least one requirement must exist.']);
    }

    // Save or update requirements
    $existingRequirements = Requirement::where('block_id', $blockId)->get()->keyBy('name');

    foreach ($requirements as $requirement) {
        Requirement::updateOrCreate(
            ['name' => $requirement['name'], 'block_id' => $blockId],
            ['quantity' => $requirement['quantity']]
        );

        // Remove requirement from existing requirements array
        $existingRequirements->forget($requirement['name']);
    }

    // Delete requirements that are not in the request
    Requirement::where('block_id', $blockId)
        ->whereIn('name', $existingRequirements->keys())
        ->delete();

    return response()->json(['success' => true]);
}





public function saveCheckOutItemsroom(Request $request)
{
    // Log the entire request for debugging
    Log::info('Save Check-Out Items Request:', $request->all());

    $items = $request->input('items');
    $blockId = $request->input('block_id');
    $floorId = $request->input('floor_id'); // Floor ID
    $roomId = $request->input('room_id'); // Room ID

    // Check if block_id, floor_id, and room_id are provided
    if (!$blockId || !$floorId || !$roomId) {
        return response()->json(['success' => false, 'message' => 'Block ID, Floor ID, and Room ID are required.']);
    }

    // Validate that at least one item must exist
    if (empty($items)) {
        return response()->json(['success' => false, 'message' => 'At least one check-out item must exist.']);
    }

    // Save or update check-out items based on room_id
    $existingItems = CheckOutItem::where('room_id', $roomId)->get()->keyBy('name');

    foreach ($items as $item) {
        CheckOutItem::updateOrCreate(
            [
                'name' => $item['name'],
                'room_id' => $roomId, // Focus on room_id
            ],
            [
                'condition' => $item['condition'],
                'block_id' => $blockId,
                'floor_id' => $floorId, // Save floor_id
                'room_id' => $roomId    // Save room_id
            ]
        );

        // Remove item from existing items array
        $existingItems->forget($item['name']);
    }

    // Delete items that are not in the request for the given room_id
    CheckOutItem::where('room_id', $roomId)
        ->whereIn('name', $existingItems->keys())
        ->delete();

    return response()->json(['success' => true]);
}






public function confirmapplication(Request $request)
{
    // Validate the request
    $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
        'block_id' => 'required|exists:blocks,id',
    ]);

    // Retrieve the user and update check-in status
    $user = User::find($validated['user_id']);
    $user->checkin = 1;
    $user->save();

    // Retrieve requirements and check-out items for the given block
    $requirements = Requirement::where('block_id', $validated['block_id'])->get();
    $checkOutItems = CheckOutItem::where('room_id', $user->room_id)->get();

    // Retrieve the semester_id from the user
    $semesterId = $user->semester_id;

    // Check if a confirmation record already exists for the user and semester
    $confirmation = RequirementItemConfirmation::where('user_id', $validated['user_id'])
        ->where('semester_id', $semesterId)
        ->first();

    if ($confirmation) {
        // Update existing confirmation record
        $confirmation->items_to_bring_names = $requirements->map(function($req) {
            return [
                'name' => $req->name,
                'quantity' => $req->quantity,
                'status' => $req->status,
            ];
        })->toJson();

        $confirmation->checkout_items_names = $checkOutItems->map(function($item) {
            return [
                'name' => $item->name,
                'condition' => $item->condition,
            ];
        })->toJson();

        $confirmation->save();
    } else {
        // Create a new confirmation record
        $confirmation = new RequirementItemConfirmation();
        $confirmation->user_id = $validated['user_id'];
        $confirmation->semester_id = $semesterId;
        $confirmation->block_name = $user->block ? $user->block->name : 'N/A';
        $confirmation->floor_name = $user->floor ? $user->floor->floor_number : 'N/A';
        $confirmation->room_name = $user->room ? $user->room->room_number : 'N/A';
        $confirmation->bed_name = $user->bed ? $user->bed->bed_number : 'N/A';
        $confirmation->course_name = $user->course;
        $confirmation->gender = $user->gender;
        $confirmation->items_to_bring_names = $requirements->map(function($req) {
            return [
                'name' => $req->name,
                'quantity' => $req->quantity,
                'status' => $req->status,
            ];
        })->toJson();

        $confirmation->checkout_items_names = $checkOutItems->map(function($item) {
            return [
                'name' => $item->name,
                'condition' => $item->condition,
            ];
        })->toJson();

        $confirmation->save();
    }

    return response()->json(['message' => 'Confirmation saved successfully'], 200);
}

}
