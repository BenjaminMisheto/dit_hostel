<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log; // Import Log facade
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Block;
use App\Models\Floor;
use App\Models\Room;
use App\Models\Bed;
use App\Models\Publish;
use App\Models\SliderData;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class AjaxController extends Controller
{
    public function saveData(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'registration_number' => 'required|string',
                'student_name' => 'required|string',
                'payment_status' => 'required|string',
            ]);

            // Check if the user is authenticated
            if (!auth()->check()) {
                return response()->json(['message' => 'User is not authenticated'], 401);
            }

            // Update the record for the authenticated user
            $affectedRows = User::where('id', auth()->id())
                ->update([
                    'registration_number' => $validatedData['registration_number'],
                    'payment_status' => $validatedData['payment_status'],
                ]);

            // Return the affected rows count and the updated record
            return response()->json(['message' => 'Data saved successfully']);

        } catch (QueryException $e) {
            // Log the error message and return a 500 status code
            Log::error('QueryException occurred while saving data: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred while saving data'], 500);
        } catch (\Exception $e) {
            // Log the error message and return a 500 status code
            Log::error('Exception occurred while saving data: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred while saving data'], 500);
        }
    }

    public function hostel_sent(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'data' => 'required|integer',
            ]);

            // Check if the user is authenticated
            if (!auth()->check()) {
                return response()->json(['message' => 'User is not authenticated'], 401);
            }

            // Update the record for the authenticated user
            $affectedRows = User::where('id', auth()->id())
                ->update([
                    'hostel' => $validatedData['data'],
                ]);

            // Return the affected rows count and the updated record
            return response()->json(['message' => 'Data saved successfully']);

        } catch (QueryException $e) {
            // Log the error message and return a 500 status code
            Log::error('QueryException occurred while saving data: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred while saving data'], 500);
        } catch (\Exception $e) {
            // Log the error message and return a 500 status code
            Log::error('Exception occurred while saving data: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred while saving data'], 500);
        }
    }

    public function room_select(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'data' => 'required|string',
            ]);

            // Check if the user is authenticated
            if (!auth()->check()) {
                return response()->json(['message' => 'User is not authenticated'], 401);
            }

            // Update the record for the authenticated user
            $affectedRows = User::where('id', auth()->id())
                ->update([
                    'room' => $validatedData['data'],
                ]);

            // Return the affected rows count and the updated record
            return response()->json(['message' => 'Data saved successfully']);

        } catch (QueryException $e) {
            // Log the error message and return a 500 status code
            Log::error('QueryException occurred while saving data: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred while saving data'], 500);
        } catch (\Exception $e) {
            // Log the error message and return a 500 status code
            Log::error('Exception occurred while saving data: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred while saving data'], 500);
        }
    }



        public function admin_login(Request $request)
    {


        // Validate the input data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check if the input data fails validation
        if ($validator->fails()) {
            return redirect()->route('admin')
                ->withErrors($validator)
                ->withInput();
        }

        Auth::guard('web')->logout();

    // Attempt admin login
    if (Auth::guard('admin')->attempt($request->only('email', 'password'), $request->has('remember'))) {
        return redirect()->route('admin.dashboard');
    }


    return redirect()->route('admin')
    ->withErrors(['error' => 'These credentials do not match our records.'])
    ->withInput($request->only('email', 'remember'));

    }


    public function hostel_create(Request $request)
{
    // Validate the incoming request
    $validated = $request->validate([
        'blockName' => 'required|string',
        'blocklocation' => 'required|string',
        'blockManager' => 'required|string',
        'numFloors' => 'required|integer|min:1',
        'blockPrice' => 'required|numeric',
        'eligibility' => 'required|array',
        'floors' => 'required|array',
        'floors.*.number_of_rooms' => 'required|integer|min:1',
        'floors.*.rooms' => 'required|array',
        'floors.*.rooms.*.bedCount' => 'required|integer|min:1',
        'floors.*.gender' => 'nullable|array',
        'floors.*.eligibility' => 'nullable|array',
        'image' => 'nullable|string' // Validate that image is a base64 string
    ]);

    // Check if the user is authenticated
    if (!auth('admin')->check()) {
        return response()->json(['message' => 'User is not authenticated'], 401);
    }

    try {
        // Create the block
        $block = Block::create([
            'name' => $request->blockName,
            'location' => $request->blocklocation,
            'manager' => $request->blockManager,
            'number_of_floors' => $request->numFloors,
            'price' => $request->blockPrice,
        ]);

        // Handle image upload if present and valid
        if ($request->has('image') && !empty($request->input('image'))) {
            $imageData = $request->input('image');
            // Validate image data format
            if (preg_match('/^data:image\/(png|jpg|jpeg);base64,/', $imageData)) {
                // Remove the data URL scheme part
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
                $imageData = base64_decode($imageData);
                if ($imageData !== false) {
                    // Store the base64 string in the database
                    $block->image_data = $request->input('image');
                    $block->save();
                }
            }
        }

        // Retrieve the maximum existing floor number in the block
        $maxFloorNumber = $block->floors()->max('floor_number') ?: 0;
        $nextFloorNumber = $maxFloorNumber + 1;

        // Initialize room counter
        $roomCounter = 1;

        // Create and save floors associated with the block
        foreach ($request->floors as $floorData) {
            // Encode gender and eligibility as JSON if present
            $gender = isset($floorData['gender']) ? json_encode($floorData['gender']) : null;
            $eligibility = isset($floorData['eligibility']) ? json_encode($floorData['eligibility']) : null;

            // Create a new floor with a sequential floor number
            $floor = new Floor([
                'floor_number' => $nextFloorNumber, // Assign the next sequential floor number
                'number_of_rooms' => $floorData['number_of_rooms'],
                'gender' => $gender,
                'eligibility' => $eligibility,
            ]);

            $block->floors()->save($floor);

            // Increment the next floor number for the next floor
            $nextFloorNumber++;

            // Process rooms for the current floor
            foreach ($floorData['rooms'] as $roomData) {
                // Check if gender is set and is an array
                $genderArray = $floorData['gender'] ?? []; // Default to an empty array if not set

                // Check if the array contains more than one element
                if (count($genderArray) > 1) {
                    $genderString = null;
                } else {
                    // Convert the array to a string if it contains only one element
                    $genderString = implode(', ', $genderArray);
                }

                // Create a room for the current floor
                $room = new Room([
                    'room_number' => $roomCounter,
                    'gender' => $genderString, // Use the processed gender string
                ]);

                // Save the room to the current floor
                $floor->rooms()->save($room);

                // Create beds for this room
                for ($i = 1; $i <= $roomData['bedCount']; $i++) {
                    Bed::create([
                        'room_id' => $room->id,
                        'bed_number' => $i,
                    ]);
                }

                $roomCounter++; // Increment room number
            }
        }

        // Return a success response
        return response()->json([
            'success' => true,
            'message' => 'Block, floor, room, and bed details saved successfully!',
            'block' => $block,
            'floors' => $block->floors,
        ]);

    } catch (\Exception $e) {
        // Log the error details to the Laravel log file
        \Log::error('Error occurred while saving block, floor, room, and bed details:', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);

        // Return an error response
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while saving the block, floor, room, and bed details. Please try again.',
            'error' => $e->getMessage(),
        ], 500);
    }
}





public function updatePublishStatus(Request $request)
{
    // Try to find the Publish record by ID
    $publish = Publish::find(1); // Adjust this ID as needed

    if (!$publish) {
        // If not found, create a new record
        $publish = new Publish();
    }

    // Update the status
    $publish->status = $request->input('status');
    $publish->save();

    // Fetch the expiration days from the publishes table
    $publish = Publish::first(); // Adjust if needed to fetch the specific record
    if ($publish) {
        // Convert the stored expiration_date to an integer representing the number of days
        $daysToAdd = (int) $publish->expiration_date;

        // Calculate the new expiration date by adding the days to the current time
        $newExpirationDate = Carbon::now()->addDays($daysToAdd);

        // Update the expiration_date for all users
        User::query()->update(['expiration_date' => $newExpirationDate]);
    } else {
        return response()->json(['message' => 'Publish record not found.'], 404);
    }

    return response()->json(['success' => true, 'message' => 'Publish status and user expiration dates updated successfully.']);
}



    public function getProfile()
    {


        // Assuming you want to get the logged-in user's profile
        $user = Auth::user();

        // Check if the user exists
        if ($user) {
            // Pass the user data to the view
            return view('user.profile', ['user' => $user]);
        }

        // If user is not found, you can redirect or show an error
        return redirect()->route('home')->with('error', 'User not found.');
    }


    public function getdash()
    {
        // Assuming you want to get the logged-in user's profile
        $user = Auth::user();

        $publishes = Publish::all();
        // Check if the user exists
        if ($user) {
            // Pass the user data to the view
            return view('dashboard', compact('user', 'publishes'));
        }

        // If user is not found, you can redirect or show an error
        return redirect()->route('home')->with('error', 'User not found.');
    }


    public function gethostel()
{
    // Fetch the current authenticated user
    $user = auth()->user();

    // Fetch blocks with their related floors, ordered by 'created_at' in descending order
    $blocks = Block::with('floors')
        ->where('status', '1')
        ->orderBy('created_at', 'desc')
        ->get();

    // Convert user gender to lowercase for case-insensitive comparison
    $userGenderLower = strtolower($user->gender);

    // Filter blocks to include only those where at least one floor's gender matches the user's gender
    $blocks = $blocks->filter(function ($block) use ($userGenderLower) {
        return $block->floors->contains(function ($floor) use ($userGenderLower) {
            // Decode the gender JSON field
            $genderArray = json_decode($floor->gender, true);

            // Ensure genderArray is an array and perform case-insensitive comparison
            if (is_array($genderArray)) {
                $genderArrayLower = array_map('strtolower', $genderArray);
                return in_array($userGenderLower, $genderArrayLower);
            }

            return false;
        });
    });

        // Fetch the publish settings from the database
        $publishSettings = Publish::first();

// Retrieve publish settings with default values
$openDate = $publishSettings ? $publishSettings->open_date : null;
$deadlineDate = $publishSettings ? $publishSettings->deadline : null;

// Pass the filtered blocks, user data, and publish settings to the view
return view('user.hostel', compact('blocks', 'user', 'openDate', 'deadlineDate'));

}




public function loadRoom($blockId)
{
    // Fetch the block with floors, rooms, and beds
    $block = Block::with('floors.rooms.beds')->find($blockId);

    // Fetch the authenticated user
    $user = auth()->user(); // or use User::find($userId) if passing user ID

    // Fetch the algorithm and settings from the Publish model
    $publish = Publish::first(); // Assuming there is only one row in 'publishes'

    $algorithmSetting = $publish ? $publish->algorithm : false;
    $reservedBedEnabled = $publish ? $publish->reserved_bed : false;
    $maintenanceBedEnabled = $publish ? $publish->maintenance_bed : false;

    // Initialize reasons (using an associative array to ensure uniqueness)
    $reasons = [];

    // Filter floors based on user's gender and course
    $filteredFloors = $block->floors->filter(function($floor) use ($user, &$reasons) {
        $userGenderLower = strtolower($user->gender);
        $genderArray = is_array($floor->gender) ? $floor->gender : json_decode($floor->gender, true);
        $genderArrayLower = array_map('strtolower', $genderArray);

        $userCourse = strtolower($user->course);
        $eligibilityArray = is_array($floor->eligibility) ? $floor->eligibility : json_decode($floor->eligibility, true);
        $eligibilityArrayLower = array_map('strtolower', $eligibilityArray);

        $genderMatch = in_array($userGenderLower, $genderArrayLower);
        $courseMatch = in_array($userCourse, $eligibilityArrayLower);

        // Add reasons independently based on criteria
        if (!$genderMatch) {
            $reasons['gender'] = 'Your gender does not match the eligibility criteria for this block.';
        }
        if (!$courseMatch) {
            $reasons['course'] = 'Your course does not match the eligibility criteria for this block.';
        }

        return $genderMatch && $courseMatch;
    });

    // If algorithm is enabled, check additional criteria
    if ($algorithmSetting) {
        // Fetch criteria from slider_data where block_id == current block id
        $criteriaRecords = SliderData::where('block_id', $blockId)
            ->whereIn('floor_id', $block->floors->pluck('id'))
            ->where('status', 1)
            ->get();

        // Extract criteria courses from the fetched records
        $criteriaCourses = $criteriaRecords->pluck('criteria')->flatten()->map('strtolower')->unique()->toArray();

        // Check if user's course matches any of the criteria
        $userCourseLower = strtolower($user->course);
        $courseMatchInCriteria = in_array($userCourseLower, $criteriaCourses);

        // If course does not match criteria, add a reason and return an empty result
        if (!$courseMatchInCriteria) {
            $reasons['algorithm'] = 'The algorithm settings are restricting the available floors based on criteria.';
            $filteredFloors = collect(); // Empty collection
        }
    }

    // Filter rooms and beds within the filtered floors
    $filteredFloors = $filteredFloors->filter(function($floor) use ($reservedBedEnabled, $maintenanceBedEnabled, &$reasons) {
        $floor->rooms = $floor->rooms->filter(function($room) use ($reservedBedEnabled, $maintenanceBedEnabled) {
            $room->beds = $room->beds->filter(function($bed) use ($reservedBedEnabled, $maintenanceBedEnabled) {
                if ($bed->status === 'activate') {
                    return true;
                }
                if ($reservedBedEnabled && $bed->status === 'reserve') {
                    return true;
                }
                if ($maintenanceBedEnabled && $bed->status === 'under_maintenance') {
                    return true;
                }
                return false;
            });

            if ($room->beds->isEmpty()) {
                \Log::error('Room ' . $room->id . ' has no available beds.');
            }

            return $room->beds->isNotEmpty();
        });

        if ($floor->rooms->isEmpty()) {
            $reasons['rooms'] = 'There are no available rooms in this floor that meet the criteria.';
        }

        return $floor->rooms->isNotEmpty();
    });

    if ($filteredFloors->isEmpty()) {
        if ($algorithmSetting) {
            $reasons['algorithm'] = 'The algorithm settings are restricting the available floors based on criteria.';
        }
        // if (!$reservedBedEnabled) {
        //     $reasons['reserved_beds'] = 'Reserved beds are currently not available for selection.';
        // }
        // if (!$maintenanceBedEnabled) {
        //     $reasons['maintenance_beds'] = 'Beds under maintenance are currently not available for selection.';
        // }
    }

    \Log::error('Algorithm Setting: ' . ($algorithmSetting ? 'enabled' : 'disabled'));

    // Convert reasons array to a list of values
    $reasons = array_values($reasons);

    // Pass the block, filtered floors, user data, and reasons to the view
    return view('user.room', compact('block', 'filteredFloors', 'user', 'reasons'));
}


















public function updateBedSelection(Request $request)
{
    // Validate the incoming data
    $validatedData = $request->validate([
        'bed_id' => 'required|integer',
        'room_id' => 'required|integer',
        'floor_id' => 'required|integer',
        'block_id' => 'required|integer',
    ]);

    // Get the currently authenticated user
    $user = auth()->user();

    try {
        // Find the selected bed, room, floor, and block
        $bed = Bed::find($validatedData['bed_id']);
        $room = Room::find($validatedData['room_id']);
        $floor = Floor::find($validatedData['floor_id']);
        $block = Block::find($validatedData['block_id']);

        // Check if the entities exist and are in a valid state
        if (!$bed || !$room || !$floor || !$block) {
            return response()->json(['error' => 'Invalid selection.'], 400);
        }

        // Validate bed status
        if ($bed->status === 'under_maintenance' || $bed->status === 'reserve' || $bed->user_id) {
            return response()->json(['error' => 'Selected bed is not available.'], 400);
        }

        // Validate that the room, floor, and block are associated correctly
        if ($room->floor_id !== $floor->id || $floor->block_id !== $block->id) {
            return response()->json(['error' => 'Selection does not match the room, floor, and block association.'], 400);
        }

        // Update the user with the selected bed details
        $user->update([
            'bed_id' => $validatedData['bed_id'],
            'room_id' => $validatedData['room_id'],
            'floor_id' => $validatedData['floor_id'],
            'block_id' => $validatedData['block_id'],
        ]);

        return response()->json(['message' => 'Bed selection updated successfully.']);
    } catch (\Exception $e) {
        // Log the error message for debugging
        \Log::error('Error updating bed selection: ' . $e->getMessage());

        return response()->json(['error' => 'An error occurred while updating your selection. Please try again.'], 500);
    }
}


public function getUserInfo()
{




    // Retrieve the authenticated user
    $user = auth()->user();

    // Return the view with user information
    return view('user.finish', ['user' => $user]);
}

public function getUserInfoResult()
{
    // Retrieve the authenticated user
    $user = auth()->user();

    // Convert expiration_date to ISO 8601 format
    $expirationDate = Carbon::parse($user->expiration_date)->toIso8601String();
    $formattedExpirationDate = Carbon::parse($user->expiration_date)->format('F j, Y');
    // Retrieve all records from the publishes table
    $publishes = Publish::all();

    // Pass the user, publishes, and expirationDate to the 'user.result' view
    return view('user.result', compact('user', 'publishes', 'expirationDate','formattedExpirationDate'));
}


public function updateExpirationapp(Request $request)
{
    // Validate the request
    $request->validate([
        'user_id' => 'required|integer|exists:users,id',
    ]);

    \Log::error("uvbjlkbliufebvlfblui");

    // Find the user and update the fields
    $user = User::find($request->user_id);

    if (!$user) {
        return response()->json(['success' => false, 'message' => 'User not found.'], 404);
    }

    // Get the bed associated with the user
    $bed = $user->bed; // Assuming the relationship is defined in the User model as 'bed'

    // Initialize the floor and block ID variables
    $floorId = null;
    $blockId = null;

    if ($bed) {
        // Get the room associated with the bed
        $room = $bed->room;

        if ($room) {
            // Get the floor associated with the room
            $floor = $room->floor;

            if ($floor) {
                $floorId = $floor->id;
                $blockId = $floor->block->id; // Assuming the block is associated with the floor
            }
        }

        // Update the bed to set user_id to null
        $bed->user_id = null;
        $bed->save();
    }

    // Retrieve the current value of the counter
    $currentCounter = $user->counter;

// Increment the counter value
$newCounter = $currentCounter + 1;

    // Update user fields
    $user->update([
        'expiration_date' => null,
        'payment_status' => null,
        'Control_Number' => null,
        'block_id' => null,
        'room_id' => null,
        'floor_id' => null,
        'bed_id' => null,
        'application' => 0,
        'status' => 'disapproved',
        'counter' => $newCounter
    ]);

    $userCourse = $user->course;

    // Perform the update if a row with the given criteria is found
    $row = SliderData::where('criteria', $userCourse)
        ->where('floor_id', $floorId)
        ->where('block_id', $blockId)
        ->where('status', '!=', 1)
        ->first();

    if ($row) {
        // Update the status of the row
        $row->update(['status' => 1]);
    }

    return response()->json([
        'success' => true,
        'message' => 'Your application has been successfully reset. You may now reapply.'
    ]);
}



public function confirmApplication(Request $request)
{
    // Validate that the application value is provided and is an integer
    $request->validate([
        'application' => 'required|integer'
    ]);

    // Get the authenticated user
    $user = auth()->user();

    // Update the user's application status
    $user->update([
        'application' => $request->application
    ]);

    // Update the specific bed with the user's user_id
    if ($user->bed_id) {
        // Find the bed
        $bed = Bed::find($user->bed_id);
        if ($bed) {
            // Update the bed's user_id
            $bed->update(['user_id' => $user->id]);

            // Retrieve the associated room, floor, and block
            $room = $bed->room;
            $floor = $room->floor;
            $block = $floor->block;

            // Ensure the floor and block exist
            if ($floor && $block) {
                $floorId = $floor->id;
                $blockId = $block->id;

                // Log the floor ID and block ID for debugging
                Log::info("Floor ID: $floorId, Block ID: $blockId");

                // Perform the update on the SliderData
                $row = SliderData::where('criteria', $user->course)
                    ->where('status', '!=', 0)
                    ->where('floor_id', $floorId)
                    ->where('block_id', $blockId)
                    ->first();

                if ($row) {
                    // Update the status of the row
                    $row->update(['status' => 0]);
                }
            } else {
                return response()->json(['message' => 'Floor or Block not found.'], 404);
            }
        } else {
            return response()->json(['message' => 'Bed not found.'], 404);
        }
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

    return response()->json(['message' => 'Application confirmed successfully.']);
}



public function setting()
{
    // Fetch settings from the database
    $settings = Publish::first();

    // Retrieve the stored expiration_days, defaulting to 1 if not set
    $expirationDays = $settings && $settings->expiration_date ? $settings->expiration_date : 1;

    // Retrieve the deadline and open date, defaulting to null if not set
    $deadlineDate = $settings ? $settings->deadline : null;
    $openDate = $settings ? $settings->open_date : null;

    // Pass settings data, expirationDays, deadlineDate, and openDate to the view
    return view('admin.setting', compact('settings', 'expirationDays', 'deadlineDate', 'openDate'));
}





    public function updateSetting(Request $request)
{
    $request->validate([
        'setting' => 'required|string',
        'status' => 'required|boolean',
    ]);

    $settingName = $request->input('setting');
    $status = $request->input('status');

    // Check which setting is being updated and update the appropriate column
    $columnName = '';

    switch ($settingName) {
        case 'algorithm':
            $columnName = 'algorithm';
            break;
        case 'reserved_bed':
            $columnName = 'reserved_bed';
            break;
        case 'maintenance_bed':
            $columnName = 'maintenance_bed';
            break;
        default:
            return response()->json(['message' => 'Invalid setting'], 400);
    }

    // Update the setting in the `publishes` table
    $updated = DB::table('publishes')->update([$columnName => $status]);

    if ($updated) {
        return response()->json(['message' => ucfirst($settingName) . ' setting updated successfully']);
    } else {
        return response()->json(['message' => 'Failed to update ' . $settingName . ' setting'], 500);
    }
}

public function updateExpirationDate(Request $request)
{
    // Validate the request to ensure 'days' is a positive integer
    $request->validate([
        'days' => 'required|integer|min:1', // Ensure it's a positive integer
    ]);

    // Retrieve or create the record in the 'publishes' table
    $settings = Publish::first();

    if (!$settings) {
        // Create a new record if none exists
        $settings = new Publish();
    }

    // Directly store the number of days in the 'expiration_date' field
    $settings->expiration_date = $request->days;
    $settings->save();

    // Return a success response
    return response()->json(['message' => 'Number of days updated successfully.']);
}





public function updateDates(Request $request)
{
    // Validate the request
    $request->validate([
        'deadline' => 'required|date',
        'open_date' => 'required|date',
    ]);

    // Fetch settings from the database
    $settings = Publish::first();

    if ($settings) {
        // Update the settings
        $settings->deadline = $request->deadline;
        $settings->open_date = $request->open_date;
        $settings->save();

        // Return success response
        return response()->json(['success' => true, 'message' => 'Dates updated successfully']);
    } else {
        // Return error response if settings not found
        return response()->json(['success' => false, 'message' => 'Settings not found'], 404);
    }
}






public function updatePaymentStatus(Request $request)
{
    $user = User::find($request->user_id);
    if ($user) {
        $user->payment_status = $request->payment_status;
        $user->save();

        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false], 400);
}
public function updateControlNumber(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'control_number' => 'required|string',
        ]);

        // Find the user by ID
        $user = User::find($request->user_id);

        if ($user) {
            // Update the control number
            $user->control_number = $request->control_number;
            $user->save();

            return response()->json(['message' => 'Control number updated successfully'], 200);
        }

        return response()->json(['message' => 'User not found'], 404);
    }



    public function getExpirationDate()
    {
        // Fetch the authenticated user
        $user = auth()->user();

        // Convert the expiration_date to ISO 8601 format
        $expirationDate = Carbon::parse($user->expiration_date)->toIso8601String();

        // Return the expiration date as JSON
        return response()->json([
            'expirationDate' => $expirationDate
        ]);
    }
}
