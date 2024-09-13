<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Publish;
use App\Models\Semester;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;


class ApplicationController extends Controller
{
    /**jjjjjjjjjjjjjjjjjjj
     * Display a listing of the resource.*/
     public function index(Request $request)
{
    // Get the start and end parameters from the request, defaulting to 1 and 100 respectively
    $start = $request->input('start', 1);
    $end = $request->input('end', 100);

    // Calculate the number of records to skip
    $skip = max(0, $start - 1);

    // Define the number of items per page
    $perPage = max(1, $end - $start + 1);

    // Fetch users with related bed, room, floor, and block details
    $users = User::with('bed.room.floor.block')
                ->where('application', 1)
                ->orderBy('id', 'desc')
                ->skip($skip)
                ->take($perPage)
                ->get();

    // Get the total number of records matching the query
    $totalRecords = User::where('application', 1)->count();

    // Calculate the current page based on the start value
    $currentPage = (int) ceil($start / $perPage);

    // Create a paginator instance for the users
    $paginatedStudents = new \Illuminate\Pagination\LengthAwarePaginator(
        $users,
        $totalRecords,
        $perPage,
        $currentPage,
        ['path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath()]
    );

    // Fetch total user count for each block
    $blockUserCounts = User::with('bed.room.floor.block')
        ->where('application', 1)
        ->get()
        ->groupBy('bed.room.floor.block.id')
        ->map(function($group) {
            return $group->count(); // Count of users in each block
        });

    // Group users by block, filter out users with incomplete relationships, and count users per block
    $blocks = $users->filter(function($user) {
        return $user->bed && $user->bed->room && $user->bed->room->floor && $user->bed->room->floor->block;
    })->groupBy(function($user) {
        return $user->bed->room->floor->block->id; // Group by block ID
    })->map(function($group) use ($blockUserCounts) {
        $blockId = $group->first()->bed->room->floor->block->id;
        return [
            'name' => $group->first()->bed->room->floor->block->name, // Block name
            'user_count' => $blockUserCounts[$blockId] ?? 0, // Total count of users in this block
            'users' => $group
        ];
    })->sortBy('name'); // Sort blocks by name

    // Fetch the latest publish status
    $publishStatus = Publish::latest()->value('status') ?? false;

    return view('admin.application', [
        'blocks' => $blocks,
        'publishStatus' => $publishStatus,
        'paginatedStudents' => $paginatedStudents,
    ]);
}

public function search(Request $request)
{
    $query = $request->input('query', '');

    // Fetch users matching the search query with related details
    $users = User::with('bed.room.floor.block')
                ->where('application', 1)
                ->where(function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('registration_number', 'like', "%{$query}%");
                })
                ->orderBy('id', 'desc')
                ->get();

    if ($users->isEmpty()) {
        // Return a "No applications found" message with an action button
        $html = '<p class="text-danger">No applications found.</p>';
        $html .= '<button class="btn btn-sm btn-toggle btn-lightgray" onclick="handleNoResultsAction()">
                    No Results Action
                  </button>'; // Adjust the button text and action as needed
        return response()->make($html, 200, ['Content-Type' => 'text/html']);
    }

    // Group users by block
    $groupedUsers = $users->groupBy(function($user) {
        return $user->bed->room->floor->block->id; // Group by block ID
    });

    // Generate HTML for the search results
    $html = '';
    foreach ($groupedUsers as $blockId => $group) {
        $blockName = $group->first()->bed->room->floor->block->name;
        $html .= '<h5>' . $blockName . '</h5>';
        $html .= '<div class="table-responsive">';
        $html .= '<table class="table table-striped table-fixed">';
        $html .= '<thead>
                    <tr>
                        <th scope="col">#</th> <!-- Index column -->
                        <th scope="col">Img</th>
                        <th scope="col">Name</th>
                        <th scope="col">Reg No</th>
                             <th scope="col">Course</th>
                        <th scope="col">Floor</th>
                        <th scope="col">Room</th>
                        <th scope="col">Bed</th>
                        <th scope="col">Payment</th>

                        <th scope="col">View</th>
                      <th scope="col">Actions</th>
                    </tr>
                  </thead>';
        $html .= '<tbody>';
        $index = 1; // Initialize index for each block
        foreach ($group as $user) {
            $avatar = $user->profile_photo_path;
            $name = $user->name;
            $regNo = $user->registration_number;
            $course = $user->course;
            $floor = optional($user->bed->floor)->floor_number ?? 'N/A';
            $room = optional($user->bed->room)->room_number ?? 'N/A';
            $bed = $user->bed->bed_number ?? 'N/A';

            // Check if the user's payment status or expiration date affects the display
            if (Carbon::now()->greaterThan($user->expiration_date) && empty($user->payment_status)) {
                $paymentStatus = 'Expired';
                $paymentClass = 'text-danger';
            } else {
                $paymentStatus = $user->payment_status ? 'Paid' : 'Not Paid';
                $paymentClass = $user->payment_status ? 'text-success' : 'text-warning';
            }

            $bedId = $user->bed->id;
            $userId = $user->id;
            $status = $user->status;

            $html .= '<tr>
                        <td>' . $index++ . '</td> <!-- Index column -->
                        <td><img class="avatar rounded-circle" src="' . $avatar . '" alt="Image Description"></td>
                        <td>' . $name . '</td>
                        <td>' . $regNo . '</td>
                        <td>' . $course . '</td>
                        <td>' . $floor . '</td>
                        <td>' . $room . '</td>
                        <td>' . $bed . '</td>
                        <td class="' . $paymentClass . '">
                            ' . $paymentStatus . '
                        </td>
                        <td>
                            <button class="btn btn-sm shadow-sm" onclick="floorAction(\'bed\', ' . $bedId . ')">
                                <i class="gd-arrow-top-right"></i>
                            </button>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-toggle ' . ($status === 'approved' ? 'btn-lightgreen' : 'btn-lightred') . '" data-user-id="' . $userId . '" data-status="' . $status . '" onclick="toggleStatus(this)">
                                ' . ($status === 'approved' ? 'Yes' : 'No') . '
                            </button>
                        </td>
                      </tr>';
        }
        $html .= '</tbody></table></div>';
    }

    return response()->make($html, 200, ['Content-Type' => 'text/html']);
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

    public function updateStatus(Request $request, $id)
{
    // Validate the request
    $request->validate([
        'status' => 'required|string|in:approved,disapproved',
    ]);

    // Find the user by ID
    $user = User::find($id);

    if ($user) {
        // Get the current time and the expiration date
        $now = Carbon::now();
        $expirationDate = Carbon::parse($user->expiration_date);

        // Check if both payment_status and control_number are not empty
        if (!empty($user->payment_status) && !empty($user->Control_Number)) {
            // Return error response if both payment_status and control_number are set
            return response()->json([
                'success' => false,
                'message' => 'Student has already paid. You cannot change the status now.'
            ], 400);
        }

        // Check if control_number is not empty but payment_status is empty
        if (!empty($user->Control_Number)) {
            if ($now->lt($expirationDate)) {
                // If control_number is set but payment_status is not set and the expiration date has not passed
                return response()->json([
                    'success' => false,
                    'message' => 'Student has generated a control number. You cannot change the status until the application expires.'
                ], 400);
            }
            // If expiration date has passed, allow status change
        }

        // Update the user's status
        $user->status = $request->status;
        $user->afterpublish = 0;
        $user->save();

        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully'
        ]);
    } else {
        // Return error response if user not found
        return response()->json([
            'success' => false,
            'message' => 'User not found'
        ], 404);
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

    // Get the current time
    $now = Carbon::now();

    // Fetch users with their payment_status and Control_Number
    $users = User::whereIn('id', $userIds)->get();

    foreach ($users as $user) {
        // Check if both payment_status and Control_Number are not empty
        if (!empty($user->payment_status) && !empty($user->Control_Number)) {
            // Skip updating this user
            continue;
        }

        // Check if Control_Number is not empty but payment_status is empty
        if (!empty($user->Control_Number)) {
            $expirationDate = Carbon::parse($user->expiration_date);
            if ($now->lt($expirationDate)) {
                // Skip updating this user
                continue;
            }
        }

        // Update the user's status to 'approved'
        $user->status = 'approved';
        $user->afterpublish = 0;
        $user->save();
    }

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

    // Get the current time
    $now = Carbon::now();

    // Fetch users with their payment_status and Control_Number
    $users = User::whereIn('id', $userIds)->get();

    foreach ($users as $user) {
        // Check if both payment_status and Control_Number are not empty
        if (!empty($user->payment_status) && !empty($user->Control_Number)) {
            // Skip updating this user
            continue;
        }

        // Check if Control_Number is not empty but payment_status is empty
        if (!empty($user->Control_Number)) {
            $expirationDate = Carbon::parse($user->expiration_date);
            if ($now->lt($expirationDate)) {
                // Skip updating this user
                continue;
            }
        }

        // Update the user's status to 'disapproved'
        $user->status = 'disapproved';
        $user->afterpublish = 0;
        $user->save();
    }

    return response()->json(['success' => true, 'message' => 'Applied No to selected users.']);
}



public function showSemester()
{
    // Retrieve the current semester format from the database
    $semester = Semester::first(); // Assuming you have a single record or adjust as needed

    // Set a default format if none exists
    $semesterFormat = $semester ? $semester->name : 'year_range'; // Adjust default format if needed

    // Pass the format to the view
    return view('admin.semester', compact('semesterFormat'));
}



    public function updateSemesterFormat(Request $request)
{
    // Validate incoming request
    $request->validate([
        'name' => 'required|string',
        // No need for start_date and end_date validation
    ]);

    // Get the selected format and map it to a name
    $name = $request->input('name');


    // Create or update the semester with the determined name
    Semester::updateOrCreate(
        ['id' => 1], // Assuming you have a single record or adjust as needed
        ['name' => $name]
    );

    return response()->json(['message' => 'Semester format updated successfully']);
}


}
