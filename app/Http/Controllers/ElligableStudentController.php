<?php
namespace App\Http\Controllers;

use App\Models\ElligableStudent; // Import the model
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;


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


}
