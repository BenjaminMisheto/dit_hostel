<?php
namespace App\Http\Controllers;

use App\Models\ElligableStudent; // Import the model
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use App\Models\User;
use App\Models\Bed;
use App\Models\RequirementItemConfirmation;
use Illuminate\Support\Facades\Log;
use App\Models\AdminCheckout;



class ElligableStudentController extends Controller
{


    public function elligable(Request $request)
{
    // Get the start and end parameters from the request
    $start = $request->input('start', 1);
    $end = $request->input('end', 10);

    // Calculate the number of records to skip
    $skip = $start - 1;

    // Fetch eligible students with pagination
    $elligableStudents = ElligableStudent::skip($skip)->take($end - $start + 1)->get();

    // Create a paginator instance with the skipped records
    $currentPage = ceil($start / 10); // Current page based on start record
    $perPage = 10; // Number of items per page
    $totalRecords = ElligableStudent::count(); // Total number of records

    $paginatedStudents = new \Illuminate\Pagination\LengthAwarePaginator(
        $elligableStudents,
        $totalRecords, // Total number of records
        $perPage,
        $currentPage,
        ['path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath()]
    );



    // Return the paginated list of eligible students as a view
    return view('admin.elligable', compact('paginatedStudents'));
}


public function checkin(Request $request)
    {
        // Get the start and end parameters from the request
        $start = $request->input('start', 1);
        $end = $request->input('end', 10);

        // Calculate the number of records to skip
        $skip = $start - 1;

   // Fetch eligible users with pagination
$eligibleUsers = User::whereNotNull('payment_status')
    ->whereHas('bed.room.floor.block', function($query) {
        $query->where('semester_id', session('semester_id'));
    })
    ->orderBy('checkin', 'desc') // Order by 'checkin' in descending order
    ->skip($skip)
    ->take($end - $start + 1)
    ->get();





        // Create a paginator instance with the skipped records
        $currentPage = ceil($start / 10); // Current page based on start record
        $perPage = 10; // Number of items per page
        $totalRecords = User::whereNotNull('payment_status')
    ->whereHas('bed.room.floor.block', function($query) {
        $query->where('semester_id', session('semester_id'));
    })
    ->count(); // Total number of records


        $paginatedUsers = new \Illuminate\Pagination\LengthAwarePaginator(
            $eligibleUsers,
            $totalRecords, // Total number of records
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath()]
        );



        return view('admin.checkin', compact('paginatedUsers'));
    }




public function checkout(Request $request)
    {
      // Get the start and end parameters from the request
      $start = $request->input('start', 1);
        $end = $request->input('end', 10);

        // Calculate the number of records to skip
        $skip = $start - 1;

 // Fetch eligible users with pagination
$eligibleUsers = User::whereNotNull('payment_status')
    ->whereHas('bed.room.floor.block', function($query) {
        $query->where('semester_id', session('semester_id'));
    })
    ->where('checkin', 2)
    ->orderBy('id', 'desc') // Order by 'id' in descending order
    ->skip($skip)
    ->take($end - $start + 1)
    ->get();



        // Create a paginator instance with the skipped records
        $currentPage = ceil($start / 10); // Current page based on start record
        $perPage = 10; // Number of items per page
        $totalRecords = User::whereNotNull('payment_status')
    ->where('checkin', 1)
    ->whereHas('bed.room.floor.block', function($query) {
        $query->where('semester_id', session('semester_id'));
    })
    ->count(); // Total number of records


        $paginatedUsers = new \Illuminate\Pagination\LengthAwarePaginator(
            $eligibleUsers,
            $totalRecords, // Total number of records
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath()]
        );


    // Return the paginated list of eligible students as a view
    return view('admin.checkout', compact('paginatedUsers'));

    }

    public function updateCheckinStatus(Request $request, $userId)
{
    // Log the request for debugging
    Log::info('Update Check-in Status Request:', $request->all());

    // Find the user by ID
    $user = User::findOrFail($userId);
    $status = $request->input('status');

    // Log the status and user ID
    Log::info("Updating user {$userId} with status {$status}");

    // Update the check-in status
    if ($status === 'checked-in') {
        $user->checkin = 2;
    } elseif ($status === 'check-in') {
        $user->checkin = 1;
    } else {
        $user->checkin = 2; // Default to 'check-in'
    }

    // Save the user
    $user->save();

    // Log success
    Log::info("User {$userId} check-in status updated to {$user->checkin}");

    // Return success response
    return response()->json(['success' => true, 'message' => 'Check-in status updated successfully']);
}







    public function searchCheckin(Request $request)
{
    $query = $request->input('query', '');

    $students = User::where(function($queryBuilder) use ($query) {
                            $queryBuilder->where('name', 'like', "%{$query}%")
                                         ->orWhere('registration_number', 'like', "%{$query}%");
                        })

                        ->whereNotNull('payment_status')
                        ->get();

    // Generate HTML for the search results
    if ($students->isEmpty()) {
        $html = '<p>No students found.</p>';
    } else {
        $html = '<div class="table-responsive">';
        $html .= '<table class="table table-striped">';
        $html .= '<thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Reg No</th>
                        <th>course</th>
                        <th>Floor</th>
                        <th>Room</th>
                        <th>Bed</th>
                        <th>Payment</th>
                        <th>Action</th>
                    </tr>
                  </thead>';
        $html .= '<tbody>';


            foreach ($students as $index => $student) {
    $profilePhoto = $student->profile_photo_path
        ? '<img src="' . $student->profile_photo_path . '" alt="Student Image" style="width: 40px; height: auto;" class="rounded rounded-circle">'
        : 'N/A';

    $course = $student->course ?? 'N/A';
    $floorNumber = optional($student->bed->floor)->floor_number ?? 'N/A';
    $roomNumber = optional($student->bed->room)->room_number ?? 'N/A';
    $bedNumber = optional($student->bed)->bed_number ?? 'N/A';
    $paymentStatus = !empty($student->payment_status) ? 'Paid' : 'Not Paid';

    $checkinButton = '';
    if ($student->checkin === 2) {
        $checkinButton = '<button class="btn btn-sm btn-toggle btn-lightgreen" data-user-id="' . $student->id . '" data-status="checked-in" onclick="toggleStatus(this)">Checked-In</button>';
    } elseif ($student->checkin === 1) {
        $checkinButton = '<button class="btn btn-sm btn-toggle btn-lightred" data-user-id="' . $student->id . '" data-status="check-in" onclick="toggleStatus(this)">Check-In</button>';

    } else {
        $checkinButton = '<button class="btn btn-sm btn-toggle alert-warning"  disabled><i class="gd-time"></i> Pending</button>';

    }

    $html .= '<tr>'
        . '<td>' . ($index + 1) . '</td>'
        . '<td>' . $profilePhoto . '</td>'
        . '<td>' . $student->name . '</td>'
        . '<td>' . $student->registration_number . '</td>'
        . '<td>' . $course . '</td>'
        . '<td>' . $floorNumber . '</td>'
        . '<td>' . $roomNumber . '</td>'
        . '<td>' . $bedNumber . '</td>'
        . '<td>' . $paymentStatus . '</td>'
        . '<td>' . $checkinButton . '</td>'
        . '</tr>';
}



        $html .= '</tbody></table></div>';
    }

    return response()->make($html, 200, ['Content-Type' => 'text/html']);
}






public function searchCheckout(Request $request)
{
    $query = $request->input('query', '');

    $students = User::where(function($queryBuilder) use ($query) {
                            $queryBuilder->where('name', 'like', "%{$query}%")
                                         ->orWhere('registration_number', 'like', "%{$query}%");
                        })
                        ->whereNotNull('payment_status')
                        ->where('checkin', 2)
                        ->get();


    // Generate HTML for the search results
    if ($students->isEmpty()) {
        $html = '<p>No students found.</p>';
    } else {
        $html = '<div class="table-responsive">';
        $html .= '<table class="table table-striped">';
        $html .= '<thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Reg No</th>
                        <th>course</th>
                        <th>Floor</th>
                        <th>Room</th>
                        <th>Bed</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                  </thead>';
        $html .= '<tbody>';

            foreach ($students as $index => $student) {
    $html .= '<tr>'
           . '<td>' . ($index + 1) . '</td>'
           . '<td>' . ($student->profile_photo_path ? '<img src="' . $student->profile_photo_path . '" alt="Student Image" style="width: 40px; height: auto;" class="rounded rounded-circle">' : 'N/A') . '</td>'
           . '<td>' . $student->name . '</td>'
           . '<td>' . $student->registration_number . '</td>'
           . '<td>' . ($student->course ?? 'N/A') . '</td>'
           . '<td>' . (optional($student->bed->floor)->floor_number ?? 'N/A') . '</td>'
           . '<td>' . (optional($student->bed->room)->room_number ?? 'N/A') . '</td>'
           . '<td>' . (optional($student->bed)->bed_number ?? 'N/A') . '</td>'
           . '<td>' . (!empty($student->payment_status) ? 'Paid' : 'Not Paid') . '</td>';

    // Handling checkout status
    if ($student->checkout === 1) {
        $html .= '<td><span class="text-success">Check-Out</span></td>';
    } else {
        $html .= '<td><span class="text-danger">Waiting</span></td>';
    }

    $html .= '<td>'
           . '<button class="btn btn-sm shadow-sm" onclick="checkoutAction(' . $student->bed->id . ')">'
           . '<i class="gd-arrow-top-right"></i>'
           . '</button>'
           . '</td>'
           . '</tr>';
}




        $html .= '</tbody></table></div>';
    }

    return response()->make($html, 200, ['Content-Type' => 'text/html']);
}
public function out($bedId)
{
    // Fetch the bed and associated user details
    $bed = Bed::with('user')->findOrFail($bedId);

    // Get the associated user, if any
    $user = $bed->user;

    // Fetch check-out items related to this user, if any
    $adminCheckouts = AdminCheckout::where('user_id', $user->id)
    ->where('semester_id',$user->semester_id)
    ->get();

    // Determine the confirmation items to use
    if ($adminCheckouts->isNotEmpty()) {
        // Map admin check-outs to the confirmationItems format
        $confirmationItems = $adminCheckouts->map(function ($checkout) {
            return [
                'name' => $checkout->name,
                'condition' => $checkout->condition,
            ];
        });
    } else {
        // If no admin check-outs, fetch from RequirementItemConfirmation
        $confirmation = RequirementItemConfirmation::where('user_id', $user->id)
        ->where('semester_id',$user->semester_id)->first();
        $confirmationItems = $confirmation ? json_decode($confirmation->checkout_items_names, true) : [];
    }



    // Pass the data to the view
    return view('admin.out', [

        'bed' => $bed,
        'user' => $user,
        'confirmationItems' => $confirmationItems,
        'checkoutCount' => $adminCheckouts->count() // Pass the count of admin check-outs
    ]);
}

public function studentout(Request $request)
{
    // Validate the input data
    $request->validate([
        'user_id' => 'required|exists:users,id', // Validate user_id
        'items' => 'required|array',
        'items.*.name' => 'required|string',
        'items.*.condition' => 'required|string|in:Good,Bad,None', // Allow 'None' as a valid condition
    ]);

    try {
        // Get the user based on user_id
        $user = User::findOrFail($request->input('user_id'));

        // Set the user's checkout column to 1
        $user->update(['checkout' => 1]);

        // Loop through each item in the request
        foreach ($request->input('items') as $item) {
            // Use updateOrCreate to either update an existing record or create a new one
            AdminCheckout::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'semester_id' => $user->semester_id,
                     'block_name' => $user->block ? $user->block->name : 'N/A',
                     'floor_name' => $user->floor ? $user->floor->floor_number : 'N/A',
                     'room_name' => $user->room ? $user->room->room_number : 'N/A',
                     'bed_name' => $user->bed ? $user->bed->bed_number : 'N/A',
                     'course_name' => $user->course,
                    'name' => $item['name'], // Find by item name
                ],
                [
                    'condition' => $item['condition'], // Update the condition
                ]
            );
        }

        // Return success response
        return response()->json(['success' => true], 200);
    } catch (\Exception $e) {
        // Log the error message for debugging
        Log::error('Error storing check-out items: ' . $e->getMessage());

        // Return error response
        return response()->json([
            'success' => false,
            'message' => 'An error occurred. Please try again later.'
        ], 500);
    }
}

}
