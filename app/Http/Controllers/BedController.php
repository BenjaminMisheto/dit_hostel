<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Bed;
use App\Models\SliderData;





class BedController extends Controller
{
    public function update(Request $request, $id)
{
    // Validate the incoming request
    $validatedData = $request->validate([
        'bed_id' => 'required|integer|exists:beds,id',
        'bed_number' => 'required|integer',
        'room_number' => 'required|string',
        'floor_name' => 'required|string',
        'block_name' => 'required|string',
        'name' => 'required|string',
        'registration_number' => 'required|integer',
        'sponsorship' => 'nullable|string',
        'phone' => 'nullable|string',
        'gender' => 'nullable|string',
        'nationality' => 'nullable|string',
        'course' => 'nullable|string',
        'status' => 'required|string',
    ]);

    // Update or create user
    $user = User::updateOrCreate(
        ['email' => $request->input('email')],
        [
            'name' => $request->input('name'),
            'registration_number' => $request->input('registration_number'),
            'sponsorship' => $request->input('sponsorship'),
            'phone' => $request->input('phone'),
            'gender' => $request->input('gender'),
            'nationality' => $request->input('nationality'),
            'course' => $request->input('course'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')), // Ensure to hash the password
        ]
    );

    // Update bed status
    $bed = Bed::find($id); // Fetch the bed by the ID passed in the route

    if ($bed) {
        $bed->update([
            'bed_number' => $request->input('bed_number'), // Update other details if needed
            'status' => $request->input('status')
        ]);


        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false, 'message' => 'Bed not found.']);
}





public function updateBed(Request $request, $id) {
    $request->validate([
        'bed_number' => 'required|string|max:255',
        'bed_status' => 'required|string|in:under_maintenance,reserve,activate',
    ]);

    // Find the bed by ID
    $bed = Bed::find($id);

    if ($bed) {
        // Update the bed attributes
        $bed->bed_number = $request->bed_number;
        $bed->status = $request->bed_status;
        $bed->save();


        if ($request->bed_status =='under_maintenance' or  $request->bed_status =='reserve') {
          // Update SliderData status
          SliderData::where('bed_id', $id)->update(['status' => '0']);
        }
        else {
            SliderData::where('bed_id', $id)->update(['status' => '1']);
        }





        // Prepare a message based on the bed status
        $statusMessages = [
            'under_maintenance' => 'Bed is now under maintenance.',
            'reserve' => 'Bed reserved successfully.',
            'activate' => 'Bed is now available to the student.'
        ];

        $message = $statusMessages[$request->bed_status] ?? 'Bed updated successfully!';

        return response()->json(['success' => true, 'message' => $message]);
    } else {
        return response()->json(['success' => false, 'message' => 'Bed not found.'], 404);
    }
}





public function destroy($id)
    {
        try {


            // Find the bed by its ID
            $bed = Bed::findOrFail($id);

            // Check if the bed is assigned to a student
            if ($bed->user()->exists()) {
                // Return a JSON response indicating the bed is occupied
                return response()->json([
                    'success' => false,
                    'message' => 'The bed is currently occupied by a student. Please reassign the student to another bed or remove the assignment before proceeding with the deletion.'
                ], 400);
            }

            // Delete the bed
            $bed->delete();

            // Return a success response
            return response()->json([
                'success' => true,
                'message' => 'Bed deleted successfully.'
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Handle case where the bed ID was not found

            return response()->json([
                'success' => false,
                'message' => 'The specified bed could not be found.'
            ], 404);
        } catch (\Exception $e) {
            // Handle other exceptions

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the bed. Please try again later.'
            ], 500);
        }
    }
}
