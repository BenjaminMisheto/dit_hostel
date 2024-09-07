<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use Illuminate\Support\Facades\Log;


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

}



