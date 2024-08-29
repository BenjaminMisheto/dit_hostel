<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Block;
use App\Models\Bed;

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
    $totalOccupiedBeds = Bed::whereNotNull('user_id')
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

    // Pass the block, totalBeds, totalOccupiedBeds, totalOpenBeds, totalReservedBeds, totalUnderMaintenanceBeds, blockGenders, blockEligibility, and occupancyPercentage to the view
    return view('admin.room', compact('block', 'totalBeds', 'totalOccupiedBeds', 'totalOpenBeds', 'totalReservedBeds', 'totalUnderMaintenanceBeds', 'blockGenders', 'blockEligibility', 'occupancyPercentage'));
}






    public function showBed($bedId)
    {
        // Eager load the related data: room, floor, and block
        $bed = Bed::with('room.floor.block')->findOrFail($bedId);

        // Return the view with the bed data
        return view('admin.bed', compact('bed'));
    }
}
