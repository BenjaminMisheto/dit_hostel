<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\FloorController;
use App\Http\Controllers\StudentfindController;
use App\Http\Controllers\BedController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ElligableStudentController;
use Carbon\Carbon;
use App\Models\ViewCount;
use App\Models\VisitorCount;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\ControlController;

use App\Models\Block;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', function () {
    $currentMonth = strtolower(Carbon::now()->format('F'));

    // Update View Counts
    $view = ViewCount::firstOrCreate(['id' => 1]);
    $lastUpdateMonth = strtolower($view->updated_at->format('F'));
    if ($lastUpdateMonth !== $currentMonth) {
        $view->monthly_views = 1;
    } else {
        $view->increment('total_views');
        $view->increment('monthly_views');
        $view->increment("views_{$currentMonth}");
    }
    $view->save();

    // Log updated view counts
    Log::info('Updated view counts', [
        'total_views' => $view->total_views,
        'monthly_views' => $view->monthly_views,
        "views_{$currentMonth}" => $view["views_{$currentMonth}"],
    ]);

    // Handle Visitor Counts
    $visitor = VisitorCount::firstOrCreate(['id' => 1]);
    $visitorCookieName = 'visited_this_month';

    if (!Cookie::has($visitorCookieName)) {
        // Increment the total visitors and the visitors for the current month
        $visitor->increment('total_visitors');
        $visitor->increment("visitors_{$currentMonth}");
        $visitor->increment('new_visitors');

        // Set cookie to prevent multiple counts within the same month
        Cookie::queue($visitorCookieName, true, 43200); // Cookie valid for 30 days
    }

    // Save visitor count data
    $visitor->save();

    // Log updated visitor counts
    Log::info('Updated visitor counts', [
        'total_visitors' => $visitor->total_visitors,
        'new_visitors' => $visitor->new_visitors,
        "visitors_{$currentMonth}" => $visitor["visitors_{$currentMonth}"],
    ]);

    return view('welcome');
});






Route::middleware(['custom.auth'])->group(function () {

    route::post('update.payment.status', [AjaxController::class, 'updatePaymentStatus'])->name('update.payment.status');
    Route::post('update.control.number', [AjaxController::class, 'updateControlNumber'])->name('update.control.number');
    Route::get('dashboard', [AjaxController::class, 'getdash'])->name('dashboard');

Route::get('/profile', [AjaxController::class, 'getProfile'])->name('profile');

Route::get('hostel', [AjaxController::class, 'gethostel'])->name('hostel');

Route::post('/update-user-bed-selection', [AjaxController::class, 'updateBedSelection']);


    Route::get('userroom/{blockId}', [AjaxController::class, 'loadRoom'])->name('userroom');


    Route::get('finish', [AjaxController::class, 'getuserinfo'])->name('finish');
    Route::get('result', [AjaxController::class, 'getUserInforesult'])->name('result');

    Route::post('/confirm-application', [AjaxController::class, 'confirmApplication'])->name('confirm.application');


    route::post('update.expirationapp', [AjaxController::class, 'updateExpirationapp'])->name('update.expirationapp');


// Route to search for profiles based on the registration number
Route::post('/search', [SearchController::class, 'search'])->name('search');
// Add this to your routes/web.php
Route::post('/update-profile', [SearchController::class, 'updateProfile']);


    Route::post('/save-data', [AjaxController::class, 'saveData']);

    Route::post('/ hostel_sent', [AjaxController::class, 'hostel_sent']);

    Route::post('/ room_select', [AjaxController::class, 'room_select']);

    Route::get('get.expiration.date', [AjaxController::class, 'getExpirationDate'])->name('get.expiration.date');



});




//admin
Route::get('admin', function () {
    return view('auth.admin-login');
})
    ->middleware('admin.auth')
    ->name('admin');



Route::get('admin.load', function () {
    return view('auth.log');
})->name('admin.load');


Route::get('user.load', function () {
    return view('auth.userlog');
})->name('user.load');





Route::post('/admin.login', [AjaxController::class, 'admin_login'])->name('admin.login');

Route::middleware(['admin'])->group(function () {
    // routes/web.php
    Route::get('admin.setting', [AjaxController::class, 'setting'])->name('admin.setting');


    route::post('admin.updateSetting', [AjaxController::class, 'updateSetting'])->name('admin.updateSetting');




    route::post('update-dates', [AjaxController::class, 'updateDates'])->name('update-dates');
// web.php
Route::post('admin.updateExpirationDate', [AjaxController::class, 'updateExpirationDate'])->name('admin.updateExpirationDate');

    Route::post('slider.store', [ControlController::class, 'store'])->name('slider.store');

    Route::get('admin.elligable', [ElligableStudentController::class, 'elligable'])->name('admin.elligable');

    Route::get('admin.control', [ControlController::class, 'control'])->name('admin.control');

    Route::get('/admin.dashboard', [DashboardController::class, 'showAdminDashboard'])->name('admin.dashboard');
    Route::get('/admin.profile', [DashboardController::class, 'showAdminProfile'])->name('admin.profile');

    // Route to handle displaying the view with data from the controller
    Route::get('/admin.hostel', [BlockController::class, 'index'])->name('admin.hostel');
    Route::get('/admin.application', [ApplicationController::class, 'index'])->name('admin.application');
    // For web routes in routes/web.php
    Route::post('/update-status/{userId}', [ApplicationController::class, 'updateStatus'])->name('user.updateStatus');

    Route::post('/apply-yes', [ApplicationController::class, 'applyYes'])->name('apply.yes');
    Route::post('/apply-no', [ApplicationController::class, 'applyNo'])->name('apply.no');

    Route::post('/hostel_create', [AjaxController::class, 'hostel_create'])->name('hostel_create');
    Route::get('/room/{id}', [RoomController::class, 'show'])->name('room.show');

    //     Route::get('/floor/add/{id}', [FloorController::class, 'add'])->name('floor.add');
    Route::get('/floor/update/{id}', [FloorController::class, 'edit'])->name('floor.edit'); // Renamed for consistency

    // For web routes
    Route::delete('/blocks/{block}', [BlockController::class, 'destroy'])->name('blocks.destroy');

    Route::put('/blocks/update/{block}', [BlockController::class, 'update'])->name('blocks.update');

    Route::delete('/floors/{id}', [FloorController::class, 'destroy'])->name('floor.delete');

    Route::put('/floors/{id}', [FloorController::class, 'updateFloor'])->name('floor.update'); // Renamed for consistency

    Route::post('/floor_create/{blockId}', [FloorController::class, 'create'])->name('floor.create');

    Route::put('/blocks/update-status/{id}', [BlockController::class, 'updateStatus']);

    Route::get('room/bed/{roomId}', [RoomController::class, 'showBed'])->name('room.bed');

    Route::get('/students/search', [StudentfindController::class, 'search']);

    Route::post('/update-bed/{id}', [BedController::class, 'updateBed']);

    Route::post('/student/add/{bedId}', [StudentfindController::class, 'add'])->name('student.add');

    // web.php or api.php
    Route::post('/student/remove/{bedId}', [StudentfindController::class, 'remove'])->name('student.remove');

    // Route for deleting a bed
    Route::delete('/bed/{id}', [BedController::class, 'destroy'])->name('bed.destroy');

    Route::post('/update-publish-status', [AjaxController::class, 'updatePublishStatus'])->name('publish.update');
});

Route::post('/admin.logout', function () {
    Auth::guard('admin')->logout();

    return redirect()->route('admin');
})->name('admin.logout');
