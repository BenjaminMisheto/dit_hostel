<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;

class OtpVerificationController extends Controller
{
    /**
     * Show the OTP verification form.
     *
     * @return \Illuminate\View\View
     */
    public function showVerificationForm()
    {
        return view('auth.otp-verification'); // Make sure the path is correct
    }

    /**
     * Verify the OTP entered by the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */

}
