<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
//use App\Models\CheckOutItem;
use App\Models\AdminCheckout;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use Illuminate\Support\Facades\Log;
use App\Exports\FilteredUsersExport;
use App\Exports\MaintenanceReportExport;




class ReportController extends Controller
{

    public function exportExcel(Request $request)
{
    $hostelId = $request->query('hostel_id');
    $floorId = $request->query('floor_id');
    $roomId = $request->query('room_id');
    $gender = $request->query('gender');
    $paymentStatus = $request->query('payment');
    $course = $request->query('course');

    // Filter users based on hostel, floor, room, and additional criteria
    $query = User::query();

    // Apply filters if provided
    if ($hostelId) {
        $query->where('block_id', $hostelId);
    }
    if ($floorId && $floorId !== 'all') {
        $query->where('floor_id', $floorId);
    }
    if ($roomId && $roomId !== 'all') {
        $query->where('room_id', $roomId);
    }
    if ($gender && $gender !== 'all') {
        $query->where('gender', $gender);
    }
    if ($paymentStatus) {
        if ($paymentStatus === 'paid') {
            $query->whereNotNull('payment_status');
        } elseif ($paymentStatus === 'not_paid') {
            $query->whereNull('payment_status');
        }
    }
    if ($course && $course !== 'all') {
        $query->where('course', $course);
    }

    $users = $query->with('block', 'floor', 'room')->get(); // Ensure related data is loaded

    // Get block details for the report header
    $block = $users->first()->block ?? null;

    // Generate the file name using the block name and date
    $blockName = $block ? $block->name : 'report';
    $date = now()->format('Y-m-d');
    $fileName = $blockName . '_users_' . $date . '.xlsx';

    // Pass the users to the Excel export class and download
    return Excel::download(new UsersExport($users), $fileName);
}



public function exportPDF(Request $request)
{
    $hostelId = $request->query('hostel_id');
    $floorId = $request->query('floor_id');
    $roomId = $request->query('room_id');
    $gender = $request->query('gender');
    $paymentStatus = $request->query('payment');
    $course = $request->query('course');

    Log::info('Payment Status Filter Applied: ' .$paymentStatus); // Log the payment status


    // Initialize query for filtering users
    $query = User::query();

    // Apply filters if provided
    if ($hostelId) {
        $query->where('block_id', $hostelId);
    }
    if ($floorId && $floorId !== 'all') {
        $query->where('floor_id', $floorId);
    }
    if ($roomId && $roomId !== 'all') {
        $query->where('room_id', $roomId);
    }
    if ($gender && $gender !== 'all') {
        $query->where('gender', $gender);
    }

    if ($paymentStatus) {

        if ($paymentStatus === 'paid') {
            $query->whereNotNull('payment_status');
            Log::info('Filtering users with non-null payment_status');
        } elseif ($paymentStatus === 'not_paid') {
            $query->whereNull('payment_status');
            Log::info('Filtering users with null payment_status');
        }
    }
    if ($course && $course !== 'all') {
        $query->where('course', $course);
    }

    // Fetch the filtered users
    $users = $query->with(['block', 'floor', 'room'])->get();

    // Get block details for the report header
    $block = $users->first()->block ?? null;

    // Prepare data for the PDF view
    $data = [
        'users' => $users,
        'block' => $block,
        'date' => now()->format('Y-m-d'), // Current date or format as needed
    ];

    // Generate the file name using the block name and date
    $blockName = $block ? $block->name : 'report';
    $date = now()->format('Y-m-d');
    $fileName = $blockName . '_report_' . $date . '.pdf';

    // Load the view and generate the PDF
    $pdf = Pdf::loadView('admin.pdf.report', $data);

    // Return the PDF to be viewed in the browser
    return $pdf->download($fileName);
}







public function exportPDFPrint(Request $request)
{
    $hostelId = $request->query('hostel_id');
    $floorId = $request->query('floor_id');
    $roomId = $request->query('room_id');
    $gender = $request->query('gender');
    $paymentStatus = $request->query('payment');

    $course = $request->query('course');

    // Initialize query for filtering users
    $query = User::query();

    // Apply filters if provided
    if ($hostelId) {
        $query->where('block_id', $hostelId);
    }
    if ($floorId && $floorId !== 'all') {
        $query->where('floor_id', $floorId);
    }
    if ($roomId && $roomId !== 'all') {
        $query->where('room_id', $roomId);
    }
    if ($gender && $gender !== 'all') {
        $query->where('gender', $gender);
    }
    if ($paymentStatus) {
        if ($paymentStatus === 'paid') {
            $query->whereNotNull('payment_status');
        } elseif ($paymentStatus === 'not_paid') {
            $query->whereNull('payment_status');
        }
    }
    if ($course && $course !== 'all') {
        $query->where('course', $course);
    }

    // Fetch the filtered users
    $users = $query->with(['block', 'floor', 'room'])->get();

    // Get block details for the report header
    $block = $users->first()->block ?? null;

    // Prepare data for the PDF view
    $data = [
        'users' => $users,
        'block' => $block,
        'date' => now()->format('Y-m-d'), // Current date or format as needed
    ];

    // Generate the file name using the block name and date
    $blockName = $block ? $block->name : 'report';
    $date = now()->format('Y-m-d');
    $fileName = $blockName . '_report_' . $date . '.pdf';

    // Load the view and generate the PDF
    $pdf = Pdf::loadView('admin.pdf.report', $data);

    // Return the PDF to be viewed in the browser
    return $pdf->stream('report.pdf');





}

















public function exportPDFPrintnew(Request $request)
{
    $hostelId = $request->query('hostel_id');
    $gender = $request->query('gender');
    $course = $request->query('course');
    $checkinCheckout = $request->query('checkin_checkout'); // New parameter for Check-in/Check-out status

    // Initialize query for filtering users
    $query = User::query();

    // Apply filters if provided
    if ($hostelId) {
        $query->where('block_id', $hostelId);
    }


    if ($gender && $gender !== 'all') {
        $query->where('gender', $gender);
    }


    if ($course && $course !== 'all') {
        $query->where('course', $course);
    }

    // Apply check-in/check-out filter
    if ($checkinCheckout === 'checkin') {
        // Check-in
        $query->where('checkin', 2); // Assuming `checkin_checkout` is used to determine check-in
        Log::info('Filtering users with check-in status');
    }

    elseif ($checkinCheckout === 'checkout-good') {
    $query->where('checkout', 1)
          ->whereDoesntHave('adminCheckouts', function ($q) {
              $q->where('condition', '!=', 'Good'); // Ensure no condition is different from "Good"
          });
}



    elseif ($checkinCheckout === 'checkout') {
        // Check-out
        $query->where('checkout', 1); // Assuming `checkin_checkout` is used to determine check-out
        Log::info('Filtering users with check-out status');
    }


    elseif ($checkinCheckout === 'checkout-bad') {
    $query->where('checkout', 1)
          ->whereHas('adminCheckouts', function ($q) {
              $q->where('condition', '!=', 'Good');
          });
}




    // Fetch the filtered users
    $users = $query->with(['block'])->get();

    // Log the number of users found and their details for debugging
    Log::info('Number of users found: ' . $users->count());
    foreach ($users as $user) {
        Log::info('User: ' . $user->name . ', Check-in/Check-out Status: ' . $user->checkin_checkout);
    }

    // Get block details for the report header
    $block = $users->first()->block ?? null;

    // Prepare data for the PDF view
    $data = [
        'checkinCheckout'=>$checkinCheckout,
        'users' => $users,
        'block' => $block,
        'date' => now()->format('Y-m-d'), // Current date or format as needed
    ];


    // Generate the file name using the block name and date
    $blockName = $block ? $block->name : 'report';
    $date = now()->format('Y-m-d');
    $fileName = $blockName . '_report_' . $checkinCheckout . '_' . $date . '.pdf';

    // Load the view and generate the PDF
    $pdf = Pdf::loadView('admin.pdf.reportnew', $data);

    // Return the PDF to be viewed in the browser
    return $pdf->download($fileName);
}




public function exportPDFPrintcheck(Request $request)
{
    $hostelId = $request->query('hostel_id');
    $gender = $request->query('gender');
    $course = $request->query('course');
    $checkinCheckout = $request->query('checkin_checkout'); // New parameter for Check-in/Check-out status

    // Initialize query for filtering users
    $query = User::query();

    // Apply filters if provided
    if ($hostelId) {
        $query->where('block_id', $hostelId);
    }


    if ($gender && $gender !== 'all') {
        $query->where('gender', $gender);
    }


    if ($course && $course !== 'all') {
        $query->where('course', $course);
    }

    // Apply check-in/check-out filter
    if ($checkinCheckout === 'checkin') {
        // Check-in
        $query->where('checkin', 2); // Assuming `checkin_checkout` is used to determine check-in
        Log::info('Filtering users with check-in status');
    }

    elseif ($checkinCheckout === 'checkout-good') {
    $query->where('checkout', 1)
          ->whereDoesntHave('adminCheckouts', function ($q) {
              $q->where('condition', '!=', 'Good'); // Ensure no condition is different from "Good"
          });
}




    elseif ($checkinCheckout === 'checkout') {
        // Check-out
        $query->where('checkout', 1); // Assuming `checkin_checkout` is used to determine check-out
        Log::info('Filtering users with check-out status');
    }
    elseif ($checkinCheckout === 'checkout-bad') {
    $query->where('checkout', 1)
          ->whereHas('adminCheckouts', function ($q) {
              $q->where('condition', '!=', 'Good');
          });
}



    // Fetch the filtered users
    $users = $query->with(['block'])->get();

    // Log the number of users found and their details for debugging
    Log::info('Number of users found: ' . $users->count());
    foreach ($users as $user) {
        Log::info('User: ' . $user->name . ', Check-in/Check-out Status: ' . $user->checkin_checkout);
    }

    // Get block details for the report header
    $block = $users->first()->block ?? null;

    // Prepare data for the PDF view
    $data = [
        'checkinCheckout'=>$checkinCheckout,
        'users' => $users,
        'block' => $block,
        'date' => now()->format('Y-m-d'), // Current date or format as needed
    ];


    // Generate the file name using the block name and date
    $blockName = $block ? $block->name : 'report';
    $date = now()->format('Y-m-d');
    $fileName = $blockName . '_report_' . $checkinCheckout . '_' . $date . '.pdf';

    // Load the view and generate the PDF
    $pdf = Pdf::loadView('admin.pdf.reportnew', $data);

    // Return the PDF to be viewed in the browser
    return $pdf->stream($fileName);
}





public function exportExcelnew(Request $request)
{
    $hostelId = $request->query('hostel_id');
    $gender = $request->query('gender');
    $course = $request->query('course');
    $checkinCheckout = $request->query('checkin_checkout');

    // Initialize query for filtering users
    $query = User::query();

    // Apply filters if provided
    if ($hostelId) {
        $query->where('block_id', $hostelId);
    }
    if ($gender && $gender !== 'all') {
        $query->where('gender', $gender);
    }
    if ($course && $course !== 'all') {
        $query->where('course', $course);
    }
    if ($checkinCheckout === 'checkin') {
        $query->where('checkin', 2);
    } elseif ($checkinCheckout === 'checkout') {
        $query->where('checkout', 1);
    }
    elseif ($checkinCheckout === 'checkout-bad') {
    $query->where('checkout', 1)
          ->whereHas('adminCheckouts', function ($q) {
              $q->where('condition', '!=', 'Good');
          });
}


    // Fetch the filtered users
    $users = $query->with(['block', 'requirementItemConfirmation', 'adminCheckouts'])->get();

    // Prepare data for the Excel export
    $exportData = [];
    foreach ($users as $user) {
        if ($checkinCheckout === 'checkin') {
            if ($user->requirementItemConfirmation) {
                $checkoutItems = json_decode($user->requirementItemConfirmation->checkout_items_names, true);
                if ($checkoutItems) {
                    foreach ($checkoutItems as $item) {
                        $exportData[] = [
                            $user->name,
                            $user->registration_number,
                            $user->block->name ?? 'Not Available',
                            $user->course,
                            $user->gender,
                            $item['name'] ?? 'Not Available',
                            $item['condition'] ?? 'Not Available'
                        ];
                    }
                } else {
                    $exportData[] = [
                        $user->name,
                        $user->registration_number,
                        $user->block->name ?? 'Not Available',
                        $user->course,
                        $user->gender,
                        'Not Available',
                        'Not Available'
                    ];
                }
            } else {
                $exportData[] = [
                    $user->name,
                    $user->registration_number,
                    $user->block->name ?? 'Not Available',
                    $user->course,
                    $user->gender,
                    'Not Available',
                    'Not Available'
                ];
            }
        } else { // check-out
            if ($user->adminCheckouts->isNotEmpty()) {
                foreach ($user->adminCheckouts as $adminCheckout) {
                    $exportData[] = [
                        $user->name,
                        $user->registration_number,
                        $user->block->name ?? 'Not Available',
                        $user->course,
                        $user->gender,
                        $adminCheckout->name,
                        $adminCheckout->condition
                    ];
                }
            } else {
                $exportData[] = [
                    $user->name,
                    $user->registration_number,
                    $user->block->name ?? 'Not Available',
                    $user->course,
                    $user->gender,
                    'Not Available',
                    'Not Available'
                ];
            }
        }
    }

    // Generate the file name using the block name and date
    $blockName = $users->first()->block->name ?? 'report';
    $date = now()->format('Y-m-d');
    $fileName = $blockName . '_report_' . $checkinCheckout . '_' . $date . '.xlsx';

    // Pass the formatted data to the Excel export class
    return Excel::download(new FilteredUsersExport($exportData), $fileName);
}



public function exportPDFPrintnewmaintanace(Request $request)
{
    try {
        $hostelId = $request->query('block_id');
        $floorId = $request->query('floor_id');
        $roomId = $request->query('room_id');

        // Initialize query for filtering users
        $query = User::query();

        // Apply filters if provided
        if ($hostelId) {
            $query->where('block_id', $hostelId);
        }
        if ($floorId && $floorId !== 'all') {
            $query->where('floor_id', $floorId);
        }
        if ($roomId && $roomId !== 'all') {
            $query->where('room_id', $roomId);
        }

        // Fetch the filtered users
        $users = $query->with(['block', 'floor', 'room'])->get();

        // Get block details for the report header
        $block = $users->first()->block ?? null;

        // Initialize query for filtering AdminCheckout
        $adminCheckoutQuery = AdminCheckout::query();

        // Apply filters to AdminCheckout if provided
        if ($hostelId) {
            $adminCheckoutQuery->whereHas('user', function ($q) use ($hostelId) {
                $q->where('block_id', $hostelId);
            });
        }
        if ($floorId && $floorId !== 'all') {
            $adminCheckoutQuery->whereHas('user', function ($q) use ($floorId) {
                $q->where('floor_id', $floorId);
            });
        }
        if ($roomId && $roomId !== 'all') {
            $adminCheckoutQuery->whereHas('user', function ($q) use ($roomId) {
                $q->where('room_id', $roomId);
            });
        }

        // Fetch the filtered AdminCheckout items
        $adminCheckouts = $adminCheckoutQuery->with(['user', 'user.block', 'user.floor', 'user.room'])->get();

        // Prepare data for the PDF view
        $data = [
            'users' => $users,
            'adminCheckouts' => $adminCheckouts,
            'block' => $block,
            'date' => now()->format('Y-m-d'), // Current date or format as needed
        ];

        // Generate the file name using the block name and date
        $blockName = $block ? $block->name : 'report';
        $date = now()->format('Y-m-d');
        $fileName = $blockName . '_report_Maintenance_' . $date . '.pdf';

        // Load the view and generate the PDF
        $pdf = Pdf::loadView('admin.pdf.report_maintenance', $data);

        // Return the PDF to be viewed in the browser
        return $pdf->download($fileName);
    } catch (\Exception $e) {
        // Log the error
        Log::error('PDF Generation Error: ' . $e->getMessage());

        // Return a JSON response with error message
        return response()->json(['error' => 'Failed to generate PDF.'], 500);
    }
}




public function exportPDFPrintnewmaintanaceprint(Request $request)
{
    try {
        $hostelId = $request->query('block_id');
        $floorId = $request->query('floor_id');
        $roomId = $request->query('room_id');

        // Initialize query for filtering users
        $query = User::query();

        // Apply filters if provided
        if ($hostelId) {
            $query->where('block_id', $hostelId);
        }
        if ($floorId && $floorId !== 'all') {
            $query->where('floor_id', $floorId);
        }
        if ($roomId && $roomId !== 'all') {
            $query->where('room_id', $roomId);
        }

        // Fetch the filtered users
        $users = $query->with(['block', 'floor', 'room'])->get();

        // Get block details for the report header
        $block = $users->first()->block ?? null;

        // Initialize query for filtering AdminCheckout
        $adminCheckoutQuery = AdminCheckout::query();

        // Apply filters to AdminCheckout if provided
        if ($hostelId) {
            $adminCheckoutQuery->whereHas('user', function ($q) use ($hostelId) {
                $q->where('block_id', $hostelId);
            });
        }
        if ($floorId && $floorId !== 'all') {
            $adminCheckoutQuery->whereHas('user', function ($q) use ($floorId) {
                $q->where('floor_id', $floorId);
            });
        }
        if ($roomId && $roomId !== 'all') {
            $adminCheckoutQuery->whereHas('user', function ($q) use ($roomId) {
                $q->where('room_id', $roomId);
            });
        }

        // Fetch the filtered AdminCheckout items
        $adminCheckouts = $adminCheckoutQuery->with(['user', 'user.block', 'user.floor', 'user.room'])->get();

        // Prepare data for the PDF view
        $data = [
            'users' => $users,
            'adminCheckouts' => $adminCheckouts,
            'block' => $block,
            'date' => now()->format('Y-m-d'), // Current date or format as needed
        ];

        // Generate the file name using the block name and date
        $blockName = $block ? $block->name : 'report';
        $date = now()->format('Y-m-d');
        $fileName = $blockName . '_report_Maintenance_' . $date . '.pdf';

        // Load the view and generate the PDF
        $pdf = Pdf::loadView('admin.pdf.report_maintenance', $data);

        // Return the PDF to be viewed in the browser
        return $pdf->stream($fileName);
    } catch (\Exception $e) {
        // Log the error
        Log::error('PDF Generation Error: ' . $e->getMessage());

        // Return a JSON response with error message
        return response()->json(['error' => 'Failed to generate PDF.'], 500);
    }
}


public function exportPDFPrintnewmaintanaceprintexel(Request $request)
{
    try {
        $hostelId = $request->query('block_id');
        $floorId = $request->query('floor_id');
        $roomId = $request->query('room_id');

        // Initialize query for filtering users
        $query = User::query();

        // Apply filters if provided
        if ($hostelId) {
            $query->where('block_id', $hostelId);
        }
        if ($floorId && $floorId !== 'all') {
            $query->where('floor_id', $floorId);
        }
        if ($roomId && $roomId !== 'all') {
            $query->where('room_id', $roomId);
        }

        // Fetch the filtered users
        $users = $query->with(['block', 'floor', 'room'])->get();

        // Get block details for the report header
        $block = $users->first()->block ?? null;

        // Initialize query for filtering AdminCheckout
        $adminCheckoutQuery = AdminCheckout::query();

        // Apply filters to AdminCheckout if provided
        if ($hostelId) {
            $adminCheckoutQuery->whereHas('user', function ($q) use ($hostelId) {
                $q->where('block_id', $hostelId);
            });
        }
        if ($floorId && $floorId !== 'all') {
            $adminCheckoutQuery->whereHas('user', function ($q) use ($floorId) {
                $q->where('floor_id', $floorId);
            });
        }
        if ($roomId && $roomId !== 'all') {
            $adminCheckoutQuery->whereHas('user', function ($q) use ($roomId) {
                $q->where('room_id', $roomId);
            });
        }

        // Fetch the filtered AdminCheckout items
        $adminCheckouts = $adminCheckoutQuery->with(['user', 'user.block', 'user.floor', 'user.room'])->get();

        // Export to Excel
        return Excel::download(new MaintenanceReportExport($users, $adminCheckouts, $block), 'report_Maintenance_' . now()->format('Y-m-d') . '.xlsx');
    } catch (\Exception $e) {
        // Log the error
        Log::error('Excel Export Error: ' . $e->getMessage());

        // Return a JSON response with error message
        return response()->json(['error' => 'Failed to generate Excel report.'], 500);
    }
}

}



