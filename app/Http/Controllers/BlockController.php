<?php

namespace App\Http\Controllers;

use App\Models\Block;
use Illuminate\Http\Request;
use App\Models\semester;

class BlockController extends Controller
{
    public function index()
{
    // Fetch blocks with their related floors, filtered by the current semester and ordered by 'created_at' in descending order
    $blocks = Block::with('floors')

                   ->orderBy('created_at', 'desc')
                   ->get();

    // Initialize an array to hold gender data for each block
    $blockGenders = [];

    // Loop through each block to aggregate gender data
    foreach ($blocks as $block) {
        $blockGenders[$block->id] = []; // Initialize array for current block

        // Loop through each floor in the block
        foreach ($block->floors as $floor) {
            $genders = json_decode($floor->gender, true);

            // Merge and remove duplicates for the current block
            $blockGenders[$block->id] = array_unique(array_merge($blockGenders[$block->id], $genders));
        }
    }

    // Pass the blocks and blockGenders data to the view
    return view('admin.hostel', compact('blocks', 'blockGenders'));
}








    public function destroy(Block $block)
{
    // Check if the user is authenticated
    if (!auth('admin')->check()) {
        return response()->json(['message' => 'User is not authenticated'], 401);
    }

    try {
        // Delete the block and all associated floors, rooms, and beds
        $block->floors()->each(function ($floor) {
            $floor->rooms()->each(function ($room) {
                $room->beds()->delete(); // Delete beds first
                $room->delete(); // Then delete the room
            });
            $floor->delete(); // Finally, delete the floor
        });

        $block->delete(); // Delete the block

        // Return a success response
        return response()->json([
            'success' => true,
            'message' => 'Block, floors, rooms, and beds deleted successfully!',
        ]);

    } catch (\Exception $e) {
        // Log the error details to the Laravel log file
        \Log::error('Error occurred while deleting block, floors, rooms, and beds:', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);

        // Return an error response
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while deleting the block. Please try again.',
            'error' => $e->getMessage(),
        ], 500);
    }
}

public function update(Request $request, Block $block)
    {
        // Validate the request data
        $request->validate([
            'blockName' => 'required|string|max:255',
            'blocklocation' => 'required|string|max:255',
            'blockManager' => 'nullable|string|max:255',
            'blockPrice' => 'required|numeric',
            'image' => 'nullable|string', // assuming base64 string is passed
        ]);

        // Update the block with the validated data
        $block->name = $request->input('blockName');
        $block->location = $request->input('blocklocation');
        $block->manager = $request->input('blockManager');
        $block->price = $request->input('blockPrice');

        if ($request->input('image')) {
            // Handle image storage, if needed
            $block->image_data = $request->input('image');
        }

        $block->save();

        return response()->json(['success' => true, 'message' => 'Block updated successfully']);
    }



public function updateStatus(Request $request, $id)
{
    $block = Block::findOrFail($id);
    $block->status = $request->input('status');
    $block->save();

    return response()->json(['success' => true]);
}



}
