<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\ElligableStudent;
use Illuminate\Support\Facades\DB;
use App\Models\User;


class SearchController extends Controller
{
    public function search(Request $request)
{
    // Validate the input
    $request->validate([
        'query' => 'required|string|min:1' // Validate as string and minimum length of 1 character
    ]);

    $query = $request->input('query');

    // Determine if the query is a number or name
    $isNumeric = is_numeric($query);

    try {
        if ($isNumeric) {
            // Search by registration number
            $results = ElligableStudent::where('registration_number', 'like', '%' . $query . '%')->get();
        } else {
            // Search by name
            $results = ElligableStudent::where('student_name', 'like', '%' . $query . '%')->get();
        }

        // Check if any matching records were found
        if ($results->isEmpty()) {
            return response()->json(['message' => 'No matching records found'], 404);
        }

        // Return the results as JSON
        return response()->json(['results' => $results]);
    } catch (\Exception $e) {
        // Handle database query errors
        return response()->json(['message' => 'Error querying database'], 500);
    }
}


public function updateProfile(Request $request)
{
    // Log the incoming request data
    Log::info('Incoming updateProfile request data:', $request->all());

    // Validate the request data
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'profile_photo_path' => 'nullable|string|max:255',
        'registration_number' => 'required|integer',
        'sponsorship' => 'required|string',
        'phone' => 'required|string',
        'gender' => 'required|string',
        'nationality' => 'required|string',
        'course' => 'required|string',
        //'payment_status' => 'nullable|string',
    ]);

    // Find the user by name
    $user = User::where('name', $request->name)->first();

    if ($user) {
        // Add the confirmation column to the validated data
        $validated['confirmation'] = 1;

        // Update the user's profile
        $user->update($validated);

        Log::info('User profile updated successfully:', ['user_id' => $user->id]);
        return response()->json(['success' => true]);
    }

    Log::warning('User not found for registration number:', ['registration_number' => $request->registration_number]);
    return response()->json(['success' => false, 'message' => 'User not found.']);
}

}
