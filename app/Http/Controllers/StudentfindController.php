<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\ElligableStudent;
use App\Models\User;
use App\Models\Bed;
use App\Models\SliderData;
use App\Models\Publish;
use Carbon\Carbon;

class StudentfindController extends Controller
{
    public function search(Request $request) // Method name updated to 'search'
    {
        $query = $request->input('query');
        $students = ElligableStudent::where('student_name', 'like', "%{$query}%")
            ->orWhere('registration_number', 'like', "%{$query}%")
            ->get();

        return response()->json($students);
    }




    public function add(Request $request)
{
    try {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string',
            'image' => 'nullable|string',
            'email' => 'required|email',
            'registration_number' => 'required|integer',
            'sponsorship' => 'required|string',
            'phone' => 'nullable|string',
            'gender' => 'nullable|string',
            'nationality' => 'nullable|string',
            'course' => 'nullable|string',
            'block_id' => 'required|integer',
            'floor_id' => 'required|integer',
            'room_id' => 'required|integer',
            'bed_id' => 'required|integer|exists:beds,id',
        ]);

        // Check if the user already exists by registration number
        $user = User::where('email', $request->email)->first();



        if ($user) {
            // Update existing user's bed information
            $user->update([
                'block_id' => $request->block_id,
                'floor_id' => $request->floor_id,
                'room_id' => $request->room_id,
                'bed_id' => $request->bed_id,
                'application' => 1,
                'status' => 'disapproved',
                'afterpublish'=> 1,
            ]);

                 // Check if the bed is already occupied
        $bed = Bed::find($request->bed_id);
        $bed->user_id = $user->id;
        $bed->save();

// Retrieve the user course
$userCourse = $user->course;

Log::error($userCourse);

// Perform the update if a row with the given criteria is found
$row = SliderData::where('criteria', $userCourse)
    ->where('status', '!=', 0)
    ->where('floor_id', request()->floor_id)
    ->where('block_id', request()->block_id)
    ->first();

if ($row) {
    // Update the status of the row
    $row->update(['status' => 0]);
}


    // Fetch the expiration days from the publishes table
    $publish = Publish::first(); // Adjust if needed to fetch the specific record
    if ($publish) {
        // Convert the stored expiration_date to an integer representing the number of days
        $daysToAdd = (int) $publish->expiration_date;

        // Calculate the new expiration date by adding the days to the current time
        $newExpirationDate = Carbon::now()->addDays($daysToAdd);

        // Set the user's expiration_date to the calculated date
        $user->expiration_date = $newExpirationDate;
       // $user->save();
    } else {
        return response()->json(['message' => 'Publish record not found.'], 404);
    }






            return response()->json([
                'success' => true,
                'message' => 'Student information updated successfully!',
                'user' => ['name' => $user->name],
            ]);
        }

        // Check if the bed is already occupied
        $bed = Bed::find($request->bed_id);

        // Create a new user record
        $user = User::create([
            'name' => $request->name,
            'profile_photo_path' => $request->image,
            'registration_number' => $request->registration_number,
            'sponsorship' => $request->sponsorship,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'nationality' => $request->nationality,
            'course' => $request->course,
            'block_id' => $request->block_id,
            'floor_id' => $request->floor_id,
            'room_id' => $request->room_id,
            'bed_id' => $request->bed_id,
            'email' => $request->email,
            'application' => 1,
            'status' => 'disapproved',
            'afterpublish'=> 1,
            'confirmation' => 1,
            'semester_id' => session('semester_id'),

        ]);

        // Assign the user to the bed
        $bed->user_id = $user->id;
        $bed->save();


// Retrieve the user course
$userCourse = $user->course;

// Perform the update if a row with the given criteria is found
$row = SliderData::where('criteria', $userCourse)
    ->where('status', '!=', 0)
    ->where('floor_id', request()->floor_id)
    ->where('block_id', request()->block_id)
    ->first();

if ($row) {
    // Update the status of the row
    $row->update(['status' => 0]);
}

    // Fetch the expiration days from the publishes table
    $publish = Publish::first(); // Adjust if needed to fetch the specific record
    if ($publish) {
        // Convert the stored expiration_date to an integer representing the number of days
        $daysToAdd = (int) $publish->expiration_date;

        // Calculate the new expiration date by adding the days to the current time
        $newExpirationDate = Carbon::now()->addDays($daysToAdd);

        // Set the user's expiration_date to the calculated date
        $user->expiration_date = $newExpirationDate;
        $user->save();
    } else {
        return response()->json(['message' => 'Publish record not found.'], 404);
    }


        return response()->json([
            'success' => true,
            'message' => 'Student added successfully!',
            'user' => ['name' => $user->name],
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'errors' => $e->errors(),
        ], 422);
    } catch (\Exception $e) {
        Log::error('An error occurred:', ['exception' => $e->getMessage()]);
        return response()->json([
            'success' => false,
            'message' => 'An error occurred. Please try again.',
        ], 500);
    }
}












public function remove($bedId)
{
    Log::info('Removing bed with ID: ' . $bedId);

    $bed = Bed::find($bedId);
    if (!$bed || !$bed->user_id) {
        return response()->json(['success' => false, 'message' => 'Bed or assigned student not found.'], 404);
    }

    $user = User::find($bed->user_id);
    if (!$user) {
        return response()->json(['success' => false, 'message' => 'Student not found.'], 404);
    }
    // Check if the student has already completed payment
    if (!is_null($user->payment_status)) {
        return response()->json([
            'success' => false,
            'message' => 'Student cannot be removed because the payment has already been completed.'
        ], 400);
    }



// Check if the user has a control number and expiration date is not null and in the future
if (!is_null($user->Control_Number) && !is_null($user->expiration_date) && $user->expiration_date->isFuture()) {
    $hoursRemaining = $user->expiration_date->diffInHours(now());
    return response()->json([
        'success' => false,
        'message' => "Student cannot be removed because a control number has been generated. Please wait until the expiration time expires. Hours remaining: $hoursRemaining."
    ], 400);
}




    // Retrieve associated room, floor, and block
    $room = $bed->room;
    $floor = $room->floor;
    $block = $floor->block;
    if (!$floor || !$block) {
        return response()->json(['success' => false, 'message' => 'Block or floor not found.'], 404);
    }

    $blockId = $block->id;
    $floorId = $floor->id;

    Log::info("Block ID: $blockId, Floor ID: $floorId");

    // Retrieve the user course
    $userCourse = $user->course;

    $row = SliderData::where('criteria', $userCourse)
        ->where('floor_id', $floorId)
        ->where('block_id', $blockId)
        ->where('status', '!=', 1)
        ->first();

    if ($row) {
        $row->update(['status' => 1]);
    }

    try {
        $bed->update(['user_id' => null]);

        // Clear user fields
        $user->update([
            'application' => 0,
            'status' => 'disapproved',
            'block_id' => null,
            'room_id' => null,
            'floor_id' => null,
            'bed_id' => null,
            'checkin' => 0,
            'checkout' => 0,
            'counter' => 0,
        ]);

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Failed to update records: ' . $e->getMessage()], 500);
    }

    return response()->json([
        'success' => true,
        'message' => 'Student data cleared and bed updated successfully!',
        'user_course' => $userCourse
    ]);
}


}
