<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Publish;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index()
{
    // Fetch all users with their bed, room, floor, and block
    $users = User::with('bed.room.floor.block')->where('application',1)->get();

    // Group users by block
    $blocks = $users->filter(function($user) {
        // Ensure bed, room, floor, and block exist
        return $user->bed && $user->bed->room && $user->bed->room->floor && $user->bed->room->floor->block;
    })->groupBy(function($user) {
        return $user->bed->room->floor->block->id; // Group by block ID
    })->map(function($group) {
        return [
            'name' => $group->first()->bed->room->floor->block->name, // Block name
            'users' => $group
        ];
    })->sortBy(function($block) {
        return $block['name']; // Sort by block name, or use block ID if preferred
    });

    // Fetch the current publish status
    $publishStatus = Publish::latest()->value('status') ?? false;

    return view('admin.application', [
        'blocks' => $blocks,
        'publishStatus' => $publishStatus
    ]);
}




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function updateStatus(Request $request, $id) {
    // Validate the request
    $request->validate([
        'status' => 'required|string|in:approved,disapproved',
    ]);

    // Find the user by ID
    $user = User::find($id);

    if ($user) {
        // Update the user's status
        $user->status = $request->status;
        $user->save();

        // Return success response
        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    } else {
        // Return error response if user not found
        return response()->json(['success' => false, 'message' => 'User not found'], 404);
    }
}
public function applyYes(Request $request)
    {
        $userIds = $request->input('user_ids');

        // Validate that user_ids is an array and contains only integers
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'integer|exists:users,id',
        ]);

        // Update the status of selected users to 'approved'
        User::whereIn('id', $userIds)->update(['status' => 'approved']);

        return response()->json(['success' => true, 'message' => 'Applied Yes to selected users.']);
    }

    public function applyNo(Request $request)
    {
        $userIds = $request->input('user_ids');

        // Validate that user_ids is an array and contains only integers
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'integer|exists:users,id',
        ]);

        // Update the status of selected users to 'disapproved'
        User::whereIn('id', $userIds)->update(['status' => 'disapproved']);

        return response()->json(['success' => true, 'message' => 'Applied No to selected users.']);
    }
}
