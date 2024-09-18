<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use App\Models\Semester;

//use App\Models\CheckOutItem;
use App\Models\AdminCheckout;
use App\Models\RequirementItemConfirmation;

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

    Log::info($request);
    $hostelId = $request->query('hostel_id');
    $gender = $request->query('gender');
    $course = $request->query('course');
    $checkinCheckout = $request->query('checkin_checkout');
    $semesterId = $request->query('semester_id');

    // Initialize the query variables
    $queryRequirementItemConfirmation = RequirementItemConfirmation::query();
    $queryAdminCheckout = AdminCheckout::query();

    if ($checkinCheckout === 'checkin') {
        // Apply semester filter if provided
        if ($semesterId) {
            $queryRequirementItemConfirmation->where('semester_id', $semesterId);
        }

        // Apply other filters if provided
        if ($hostelId) {
            $queryRequirementItemConfirmation->where('block_name', $hostelId);
        }

        if ($gender && $gender !== 'all') {
            $queryRequirementItemConfirmation->where('gender', $gender);
        }




        if ($course && $course !== 'all') {
            $queryRequirementItemConfirmation->where('course_name', $course);
        }

        // Get the data for checkin with related users
        $data = $queryRequirementItemConfirmation->with('user')->get();

        // Prepare data for the PDF view
        $block = $hostelId;
        $semester = Semester::find($semesterId); // Get the semester by ID

        $pdfData = [
            'checkinCheckout' => $checkinCheckout,
            'users' => $data,
            'block' => $block,
            'semester' => $semester, // Pass the semester data
            'date' => now()->format('Y-m-d'), // Current date or format as needed
        ];

        // Generate the file name using the block name and date
        $blockName = $block ? $block : 'report';
        $date = now()->format('Y-m-d');
        $fileName = $blockName . '_report_' . $checkinCheckout . '_' . $date . '.pdf';

        // Load the view and generate the PDF
        $pdf = Pdf::loadView('admin.pdf.reportnew', $pdfData);

        // Return the PDF to be viewed in the browser
        return $pdf->download($fileName);
    }

    elseif ($checkinCheckout === 'checkout') {
        // Apply semester filter if provided
        if ($semesterId) {
            $queryAdminCheckout->where('semester_id', $semesterId);
        }

        // Apply other filters if provided
        if ($hostelId) {
            $queryAdminCheckout->where('block_name', $hostelId);
        }

        if ($course && $course !== 'all') {
            $queryAdminCheckout->where('course_name', $course);
        }


        if ($gender && $gender !== 'all') {
            $queryAdminCheckout->where('gender', $gender);
        }

        // Get the data for checkout
        $users = $queryAdminCheckout->with('user')->get()->groupBy('user_id');





        // Prepare data for the PDF view
        $block = $hostelId;
        $semester = Semester::find($semesterId); // Get the semester by ID

        $pdfData = [
            'checkinCheckout' => $checkinCheckout,
            'users' => $users,
            'block' => $block,
            'semester' => $semester, // Pass the semester data
            'date' => now()->format('Y-m-d'), // Current date or format as needed
        ];

        // Generate the file name using the block name and date
        $blockName = $block ? $block : 'report';
        $date = now()->format('Y-m-d');
        $fileName = $blockName . '_report_' . $checkinCheckout . '_' . $date . '.pdf';

        // Load the view and generate the PDF
        $pdf = Pdf::loadView('admin.pdf.reportnew', $pdfData);

        // Return the PDF to be viewed in the browser
        return $pdf->download($fileName);
    }
}














public function exportPDFPrintcheck(Request $request)
{
    $hostelId = $request->query('hostel_id');
    $gender = $request->query('gender');
    $course = $request->query('course');
    $checkinCheckout = $request->query('checkin_checkout');
    $semesterId = $request->query('semester_id');

    // Initialize the query variables
    $queryRequirementItemConfirmation = RequirementItemConfirmation::query();
    $queryAdminCheckout = AdminCheckout::query();

    if ($checkinCheckout === 'checkin') {
        // Apply semester filter if provided
        if ($semesterId) {
            $queryRequirementItemConfirmation->where('semester_id', $semesterId);
        }

        // Apply other filters if provided
        if ($hostelId) {
            $queryRequirementItemConfirmation->where('block_name', $hostelId);
        }

        if ($course && $course !== 'all') {
            $queryRequirementItemConfirmation->where('course_name', $course);
        }

        // Get the data for checkin with related users
        $data = $queryRequirementItemConfirmation->with('user')->get();

        // Prepare data for the PDF view
        $block = $hostelId;
        $semester = Semester::find($semesterId); // Get the semester by ID

        $pdfData = [
            'checkinCheckout' => $checkinCheckout,
            'users' => $data,
            'block' => $block,
            'semester' => $semester, // Pass the semester data
            'date' => now()->format('Y-m-d'), // Current date or format as needed
        ];

        // Generate the file name using the block name and date
        $blockName = $block ? $block : 'report';
        $date = now()->format('Y-m-d');
        $fileName = $blockName . '_report_' . $checkinCheckout . '_' . $date . '.pdf';

        // Load the view and generate the PDF
        $pdf = Pdf::loadView('admin.pdf.reportnew', $pdfData);

        // Return the PDF to be viewed in the browser
        return $pdf->stream($fileName);
    }

    elseif ($checkinCheckout === 'checkout') {
        // Apply semester filter if provided
        if ($semesterId) {
            $queryAdminCheckout->where('semester_id', $semesterId);
        }

        // Apply other filters if provided
        if ($hostelId) {
            $queryAdminCheckout->where('block_name', $hostelId);
        }

        if ($course && $course !== 'all') {
            $queryAdminCheckout->where('course_name', $course);
        }

        // Get the data for checkout
        $users = $queryAdminCheckout->with('user')->get()->groupBy('user_id');

        Log::info( $users);



        // Prepare data for the PDF view
        $block = $hostelId;
        $semester = Semester::find($semesterId); // Get the semester by ID

        $pdfData = [
            'checkinCheckout' => $checkinCheckout,
            'users' => $users,
            'block' => $block,
            'semester' => $semester, // Pass the semester data
            'date' => now()->format('Y-m-d'), // Current date or format as needed
        ];

        // Generate the file name using the block name and date
        $blockName = $block ? $block : 'report';
        $date = now()->format('Y-m-d');
        $fileName = $blockName . '_report_' . $checkinCheckout . '_' . $date . '.pdf';

        // Load the view and generate the PDF
        $pdf = Pdf::loadView('admin.pdf.reportnew', $pdfData);

        // Return the PDF to be viewed in the browser
        return $pdf->stream($fileName);
    }
}
public function exportExcelnew(Request $request)
{
    $hostelId = $request->query('hostel_id');
    $course = $request->query('course');
    $checkinCheckout = $request->query('checkin_checkout');
    $semesterId = $request->query('semester_id');

    $queryRequirementItemConfirmation = RequirementItemConfirmation::query();
    $queryAdminCheckout = AdminCheckout::query();

    // Fetch the semester name for use in the export
    $semester = $semesterId ? Semester::find($semesterId)->name : 'Not Available';

    if ($checkinCheckout === 'checkin') {
        // Apply filters
        if ($semesterId) {
            $queryRequirementItemConfirmation->where('semester_id', $semesterId);
        }

        if ($hostelId) {
            $queryRequirementItemConfirmation->where('block_name', $hostelId);
        }

        if ($course && $course !== 'all') {
            $queryRequirementItemConfirmation->where('course_name', $course);
        }

        // Get the data for checkin
        $data = $queryRequirementItemConfirmation->with('user')->get()->toArray();

        // Generate Excel file
        $fileName = ($hostelId ? $hostelId : 'report') . '_report_' . $checkinCheckout . '_' . now()->format('Y-m-d') . '.xlsx';
        return Excel::download(new FilteredUsersExport($data, $checkinCheckout, $semester), $fileName);
    }
    elseif ($checkinCheckout === 'checkout') {
        // Apply filters
        if ($semesterId) {
            $queryAdminCheckout->where('semester_id', $semesterId);
        }

        if ($hostelId) {
            $queryAdminCheckout->where('block_name', $hostelId);
        }

        if ($course && $course !== 'all') {
            $queryAdminCheckout->where('course_name', $course);
        }

        // Get the data for checkout
        $data = $queryAdminCheckout->with('user')->get()->groupBy('user_id')->toArray();

        // Generate Excel file
        $fileName = ($hostelId ? $hostelId : 'report') . '_report_' . $checkinCheckout . '_' . now()->format('Y-m-d') . '.xlsx';
        return Excel::download(new FilteredUsersExport($data, $checkinCheckout, $semester), $fileName);
    }
}










public function exportPDFPrintnewmaintanace(Request $request)
{
    try {
        $hostelId = $request->query('block_id');
        $floorId = $request->query('floor_id');
        $roomId = $request->query('room_id');
        $semesterId =  $request->query('semester_id');

        // Initialize query for filtering users
        $query = AdminCheckout::query();

        // Apply filters if provided
        if ($semesterId) {
            $query->where('semester_id', $semesterId);
        }
        if ($hostelId) {
            $query->where('block_name', $hostelId);
        }
        if ($floorId && $floorId !== 'all') {
            $query->where('floor_name', $floorId);
        }
        if ($roomId && $roomId !== 'all') {
            $query->where('room_name', $roomId);
        }


        // Fetch the filtered users
        $users = $query->with(['user'])->get();


        // Prepare data for the PDF view
        $data = [
            'users' => $users,
            'block' => $hostelId,
            'date' => now()->format('Y-m-d'), // Current date or format as needed
        ];

        // Generate the file name using the block name and date
        $blockName = $hostelId ? $hostelId : 'report';
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
        $semesterId =  $request->query('semester_id');

        // Initialize query for filtering users
        $query = AdminCheckout::query();

        // Apply filters if provided
        if ($semesterId) {
            $query->where('semester_id', $semesterId);
        }
        if ($hostelId) {
            $query->where('block_name', $hostelId);
        }
        if ($floorId && $floorId !== 'all') {
            $query->where('floor_name', $floorId);
        }
        if ($roomId && $roomId !== 'all') {
            $query->where('room_name', $roomId);
        }


        // Fetch the filtered users
        $users = $query->with(['user'])->get();


        // Prepare data for the PDF view
        $data = [
            'users' => $users,
            'block' => $hostelId,
            'date' => now()->format('Y-m-d'), // Current date or format as needed
        ];

        // Generate the file name using the block name and date
        $blockName = $hostelId ? $hostelId : 'report';
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
        $semesterId =  $request->query('semester_id');

        // Initialize query for filtering users
        $query = AdminCheckout::query();

        // Apply filters if provided
        if ($semesterId) {
            $query->where('semester_id', $semesterId);
        }
        if ($hostelId) {
            $query->where('block_name', $hostelId);
        }
        if ($floorId && $floorId !== 'all') {
            $query->where('floor_name', $floorId);
        }
        if ($roomId && $roomId !== 'all') {
            $query->where('room_name', $roomId);
        }

        // Fetch the filtered users
        $users = $query->with(['user'])->get();

        // Generate the file name using the block name and date
        $blockName = $hostelId ? $hostelId : 'report';
        $date = now()->format('Y-m-d');
        $fileName = $blockName . '_report_Maintenance_' . $date . '.xlsx';

        // Return the Excel export using the FilteredUsersExport class
        return Excel::download(new MaintenanceReportExport($users, 'maintenance', $semesterId), $fileName);

    } catch (\Exception $e) {
        // Log the error
        Log::error('Excel Generation Error: ' . $e->getMessage());

        // Return a JSON response with error message
        return response()->json(['error' => 'Failed to generate Excel report.'], 500);
    }
}

}



