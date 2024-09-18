<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminCheckout;
use Illuminate\Support\Facades\Auth; // For user authentication

class HistoryController extends Controller
{
    public function index()
{
    // Retrieve the checkout records for the logged-in user
    $adminCheckouts = AdminCheckout::where('user_id', Auth::id())
                               ->orderBy('created_at', 'desc') // Order by the `created_at` field in descending order
                               ->get()
                               ->groupBy('semester_id');

    // Pass the data to the view
    return view('user.history', ['adminCheckouts' => $adminCheckouts]);
}
}
