<?php

namespace App\Http\Controllers;

use App\Models\ViewCount;
use App\Models\VisitorCount;
use App\Models\User;
use App\Models\Bed;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function showAdminDashboard()
    {
        $currentMonth = strtolower(Carbon::now()->format('F'));
        $currentYear = Carbon::now()->year;

        // Handle view counts
        $view = ViewCount::firstOrCreate(['id' => 1]);

        // Check if the month has changed and reset monthly views if necessary
        $lastUpdateMonth = strtolower($view->updated_at->format('F'));
        if ($lastUpdateMonth !== $currentMonth) {
            $view->monthly_views = 0;
            $view->save();
        }

        // Increment the total views and the views for the current month
        $view->increment('total_views');
        $view->increment('monthly_views');
        $view->increment("views_{$currentMonth}");

        // Log the updated view count details
        Log::info('View count updated', [
            'total_views' => $view->total_views,
            'monthly_views' => $view->monthly_views,
            "views_{$currentMonth}" => $view->{"views_{$currentMonth}"},
        ]);

        // Handle visitor tracking
        $this->trackVisitors();

        // Get visitor data
        $visitorCount = VisitorCount::firstOrCreate(['id' => 1]);

        // Fetch user data
        $totalStudents = User::count();
        $newStudentsCount = User::whereMonth('created_at', Carbon::now()->month)
                                ->whereYear('created_at', $currentYear)
                                ->count();

        // Get the number of users for each month of the current year
        $monthlyStudentApplications = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyStudentApplications[$i] = User::whereMonth('created_at', $i)
                                                 ->whereYear('created_at', $currentYear)
                                                 ->count();
        }

        // Get total number of beds
        $totalBeds = Bed::count();
        $totalOccupiedBeds = Bed::whereNotNull('user_id')->count();
        $totalOpenBeds = Bed::where('status', 'activate')->count();
        $totalunder_maintenanceBeds = Bed::where('status', 'under_maintenance')->count();
        $totalreserveBeds = Bed::where('status', 'reserve')->count();

        // Calculate male and female student counts
        $maleStudents = User::where('gender', 'male')->count();
        $femaleStudents = User::where('gender', 'female')->count();

        // Calculate percentages
        $male_percentage = $totalStudents > 0 ? round(($maleStudents / $totalStudents) * 100, 2) : 0;
        $female_percentage = $totalStudents > 0 ? round(($femaleStudents  / $totalStudents) * 100, 2) : 0;

        // Fetch the 10 most recent applications
        $recentApplications = User::latest()->take(5)->get();

        // Pass the view count, visitor, user, and recent applications data to the view
        return view('admin.dashboard', [
            'total_views' => $view->total_views,
            'monthly_views' => $view->monthly_views,
            'current_month_views' => $view->{"views_{$currentMonth}"},
            'monthly_views_data' => [
                $view->views_january,
                $view->views_february,
                $view->views_march,
                $view->views_april,
                $view->views_may,
                $view->views_june,
                $view->views_july,
                $view->views_august,
                $view->views_september,
                $view->views_october,
                $view->views_november,
                $view->views_december,
            ],
            'total_visitors' => $visitorCount->total_visitors,
            'new_visitors' => $visitorCount->new_visitors,
            'monthly_visitors_data' => $visitorCount->getMonthlyVisitorsData(),
            'total_students' => $totalStudents,
            'new_students_count' => $newStudentsCount,
            'monthly_student_applications' => $monthlyStudentApplications,
            'total_beds' => $totalBeds,
            'total_occupied_beds' => $totalOccupiedBeds,
            'total_Open_beds' => $totalOpenBeds,
            'total_under_maintenance_beds' => $totalunder_maintenanceBeds,
            'total_reserve_beds' => $totalreserveBeds,
            'male_students' => $maleStudents,
            'female_students' => $femaleStudents,
            'male_percentage' => $male_percentage,
            'female_percentage' => $female_percentage,
            'recent_applications' => $recentApplications, // Pass recent applications data
        ]);
    }

    public function showAdminProfile()
    {
        $currentMonth = strtolower(Carbon::now()->format('F'));

        // Handle view counts
        $view = ViewCount::firstOrCreate(['id' => 1]);

        // Get visitor data
        $visitorCount = VisitorCount::firstOrCreate(['id' => 1]);

        // Fetch user data
        $totalStudents = User::count();
        $newStudentsCount = User::whereMonth('created_at', Carbon::now()->month)
                                ->whereYear('created_at', Carbon::now()->year)
                                ->count();

        // Get the number of users for each month of the current year
        $monthlyStudentApplications = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyStudentApplications[$i] = User::whereMonth('created_at', $i)
                                                 ->whereYear('created_at', Carbon::now()->year)
                                                 ->count();
        }

        // Get total number of beds
        $totalBeds = Bed::count();
        $totalOccupiedBeds = Bed::whereNotNull('user_id')->count();
        $totalOpenBeds = Bed::where('status', 'activate')->count();
        $totalunder_maintenanceBeds = Bed::where('status', 'under_maintenance')->count();
        $totalreserveBeds = Bed::where('status', 'reserve')->count();

        $maleStudents = User::where('gender', 'male')->count();
        $femaleStudents = User::where('gender', 'female')->count();

        // Calculate percentages
        $male_percentage = $totalStudents > 0 ? round(($maleStudents / $totalStudents) * 100, 2) : 0;
        $female_percentage = $totalStudents > 0 ? round(($femaleStudents  / $totalStudents) * 100, 2) : 0;

        // Fetch the 10 most recent applications
        $recentApplications = User::latest()->take(5)->get();

        // Pass the view count, visitor, user, and recent applications data to the view
        return view('admin.profile', [
            'total_views' => $view->total_views,
            'monthly_views' => $view->monthly_views,
            'current_month_views' => $view->{"views_{$currentMonth}"},
            'monthly_views_data' => [
                $view->views_january,
                $view->views_february,
                $view->views_march,
                $view->views_april,
                $view->views_may,
                $view->views_june,
                $view->views_july,
                $view->views_august,
                $view->views_september,
                $view->views_october,
                $view->views_november,
                $view->views_december,
            ],
            'total_visitors' => $visitorCount->total_visitors,
            'new_visitors' => $visitorCount->new_visitors,
            'monthly_visitors_data' => $visitorCount->getMonthlyVisitorsData(),
            'total_students' => $totalStudents,
            'new_students_count' => $newStudentsCount,
            'monthly_student_applications' => $monthlyStudentApplications,
            'total_beds' => $totalBeds,
            'total_occupied_beds' => $totalOccupiedBeds,
            'total_Open_beds' => $totalOpenBeds,
            'total_under_maintenance_beds' => $totalunder_maintenanceBeds,
            'total_reserve_beds' => $totalreserveBeds,
            'male_students' => $maleStudents,
            'female_students' => $femaleStudents,
            'male_percentage' => $male_percentage,
            'female_percentage' => $female_percentage,
            'recent_applications' => $recentApplications, // Pass recent applications data
        ]);
    }

    private function trackVisitors()
    {
        $currentMonth = Carbon::now()->format('F');
        $currentYear = Carbon::now()->year;
        $cookieName = "visited_{$currentMonth}_{$currentYear}";

        // Check if the cookie already exists
        if (!Cookie::has($cookieName)) {
            // Set the cookie to expire at the end of the current month
            $cookieExpiration = Carbon::now()->endOfMonth();
            Cookie::queue($cookieName, true, $cookieExpiration->diffInMinutes());

            // Increment the total and new visitors count
            $visitorCount = VisitorCount::firstOrCreate(['id' => 1]);
            $visitorCount->increment('total_visitors');
            $visitorCount->increment('new_visitors');
            $visitorCount->save();
        }
    }
}
