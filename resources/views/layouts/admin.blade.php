<?php

// header('Cache-Control: no-cache, no-store, must-revalidate');
// header('Pragma: no-cache');
// header('Expires: 0');

?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" >

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}">

    <!-- DEMO CHARTS -->
    <link rel="stylesheet" href="{{ asset('demo/chartist.css') }}">
    <link rel="stylesheet" href="{{ asset('demo/chartist-plugin-tooltip.css') }}">
    <link rel="stylesheet" href="{{ asset('graindashboard/css/graindashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mycss.css') }}">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css" integrity="sha512-CruCP+TD3yXzlvvijET8wV5WxxEh5H8P4cmz0RFbKK6FlZ2sYl3AEsKlLPHbniXKSrDdFewhbmBK5skbdsASbQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
            integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
            crossorigin="anonymous"
            referrerpolicy="no-referrer"></script>

    <!-- Croppie -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
</head>

<!-- Success Toast -->
<div id="success-toast" class="toast bg-success text-white" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-body">
        <!-- Success message will be inserted here -->
    </div>
</div>

<!-- Error Toast -->

<div id="error-toast" class="toast bg-danger text-white" role="alert" aria-live="assertive" aria-atomic="true" style=" z-index: 1055;">
    <div class="toast-body">
        <!-- Error message will be inserted here -->
    </div>
</div>

<!-- Preloader -->
<div id="preloder" style=" z-index: 1055;">
    <div class="loader">
        <div class="spinner-border" style="width: 3rem; height: 3rem;"></div>
    </div>
</div>

    <body class="has-sidebar has-fixed-sidebar-and-header">
        <!-- Header -->
        <header class="header bg-body">
            <nav class="navbar flex-nowrap p-0">
                <div class="navbar-brand-wrapper d-flex align-items-center col-auto">
                    <!-- Logo For Mobile View -->
                    <a class="navbar-brand navbar-brand-mobile" href="/">
                        <i class="gd-key text-dark" style="font-size: 30px;"></i>
                    </a>
                    <!-- End Logo For Mobile View -->

                    <!-- Logo For Desktop View -->
                    <a class="navbar-brand navbar-brand-desktop" href="/">

                        <div class="side-nav-show-on-closed">
                            <i class="gd-key text-dark" style="font-size: 30px;"></i>
                        </div>

                        <div class="side-nav-hide-on-closed">
                            <i class="gd-key text-dark" style="font-size: 30px;"></i>

                        </div>





                    </a>
                    <!-- End Logo For Desktop View -->
                </div>

                <div class="header-content col px-md-3">
                    <div class="d-flex align-items-center">
                        <!-- Side Nav Toggle -->
                        <a class="js-side-nav header-invoker d-flex mr-md-2" href="#" data-close-invoker="#sidebarClose"
                            data-target="#sidebar" data-target-wrapper="body">
                            <i class="gd-align-left"></i>
                        </a>
                        <!-- End Side Nav Toggle -->

                        <!-- User Notifications -->
                        <div class="dropdown ml-auto">
                            <a id="notificationsInvoker" class="header-invoker" href="#" aria-controls="notifications"
                                aria-haspopup="true" aria-expanded="false" data-unfold-event="click"
                                data-unfold-target="#notifications" data-unfold-type="css-animation"
                                data-unfold-duration="300" data-unfold-animation-in="fadeIn"
                                data-unfold-animation-out="fadeOut">
                                <span
                                    class="indicator indicator-bordered indicator-top-right indicator-primary rounded-circle"></span>
                                <i class="gd-bell"></i>
                            </a>

                            <div id="notifications"
                                class="dropdown-menu dropdown-menu-center py-0 mt-4 w-18_75rem w-md-22_5rem unfold-css-animation unfold-hidden"
                                aria-labelledby="notificationsInvoker" style="animation-duration: 300ms;">
                                <div class="card">
                                    <div class="card-header d-flex align-items-center border-bottom py-3">
                                        <h5 class="mb-0">Notifications</h5>
                                        <a class="link small ml-auto" href="#">Clear All</a>
                                    </div>

                                    <div class="card-body p-0">
                                        <div class="list-group list-group-flush">
                                            <div class="list-group-item list-group-item-action">
                                                <div class="d-flex align-items-center text-nowrap mb-2">
                                                    <i class="gd-info-alt icon-text text-primary mr-2"></i>
                                                    <h6 class="font-weight-semi-bold mb-0">New Update</h6>
                                                    <span class="list-group-item-date text-muted ml-auto">just
                                                        now</span>
                                                </div>
                                                <p class="mb-0">
                                                    Order <strong>#10000</strong> has been updated.
                                                </p>
                                                <a class="list-group-item-closer text-muted" href="#"><i
                                                        class="gd-close"></i></a>
                                            </div>
                                            <div class="list-group-item list-group-item-action">
                                                <div class="d-flex align-items-center text-nowrap mb-2">
                                                    <i class="gd-info-alt icon-text text-primary mr-2"></i>
                                                    <h6 class="font-weight-semi-bold mb-0">New Update</h6>
                                                    <span class="list-group-item-date text-muted ml-auto">just
                                                        now</span>
                                                </div>
                                                <p class="mb-0">
                                                    Order <strong>#10001</strong> has been updated.
                                                </p>
                                                <a class="list-group-item-closer text-muted" href="#"><i
                                                        class="gd-close"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End User Notifications -->
                        <!-- User Avatar -->
                        <div class="dropdown mx-3 dropdown ml-2">
                            <a id="profileMenuInvoker" class="header-complex-invoker" href="#"
                                aria-controls="profileMenu" aria-haspopup="true" aria-expanded="false"
                                data-unfold-event="click" data-unfold-target="#profileMenu"
                                data-unfold-type="css-animation" data-unfold-duration="300"
                                data-unfold-animation-in="fadeIn" data-unfold-animation-out="fadeOut">
                                <!--img class="avatar rounded-circle mr-md-2" src="#" alt="John Doe"-->
                                <span class="mr-md-2 avatar-placeholder">{{ substr(auth()->guard('admin')->user()->name, 0, 1) }}
                                </span>
                                <span class="d-none d-md-block">{{ auth()->guard('admin')->user()->name }}</span>
                                <i class="gd-angle-down d-none d-md-block ml-2"></i>
                            </a>

                            <ul id="profileMenu"
                                class="unfold unfold-user unfold-light unfold-top unfold-centered position-absolute pt-2 pb-1 mt-4 unfold-css-animation unfold-hidden fadeOut"
                                aria-labelledby="profileMenuInvoker" style="animation-duration: 300ms;">
                                <li class="unfold-item">
                                    <a class="unfold-link d-flex align-items-center text-nowrap" href="#">
                                        <span class="unfold-item-icon mr-3">
                                            <i class="gd-user"></i>
                                        </span>
                                        My Profile
                                    </a>
                                </li>
                                <li class="unfold-item unfold-item-has-divider">
                                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>

                                        <a type="submit" class="unfold-link d-flex align-items-center text-nowrap" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();  $('#overlay').css('display', 'flex');">
                                            <span class="unfold-item-icon mr-3">
                                                <i class="gd-power-off"></i>
                                            </span>
                                            Sign Out
                                        </a>

                                </li>
                            </ul>
                        </div>
                        <!-- End User Avatar -->
                    </div>
                </div>
            </nav>
        </header>
        <!-- End Header -->

        <main class="main">
            <!-- Sidebar Nav -->
            <aside id="sidebar" class="js-custom-scroll side-nav ">
                <ul id="sideNav" class="side-nav-menu side-nav-menu-top-level mb-0">
                    <!-- Title -->
                    <li class="sidebar-heading h6">Profile</li>
                    <!-- End Title -->

                    <!-- Dashboard -->
                    <li class="side-nav-menu-item active " id="nav_profile">
                        <a class="side-nav-menu-link media align-items-center" href="#" onclick="dashbord()">
                            <span class="side-nav-menu-icon d-flex mr-3">
                                <i class="gd-dashboard  "></i>
                            </span>
                            <span class="side-nav-fadeout-on-closed media-body text-dark">Dashboard</span>
                        </a>
                    </li>


                    <li class="side-nav-menu-item " id="nav_hostel">
                        <a class="side-nav-menu-link media align-items-center" href="#" onclick="hostel()">
                            <span class="side-nav-menu-icon d-flex mr-3">
                               <i class="gd-key " ></i>


                            </span>
                            <span class="side-nav-fadeout-on-closed media-body  text-dark">Hostel</span>
                        </a>
                    </li>


                    <li class="side-nav-menu-item " id="nav_aplication">
                        <a class="side-nav-menu-link media align-items-center" href="#" onclick="aplication()">
                            <span class="side-nav-menu-icon d-flex mr-3">


                                   <i class="gd-view-list-alt  small" id="gd-room"></i>
                            </span>
                            <span class="side-nav-fadeout-on-closed media-body  text-dark">Aplication</span>
                        </a>
                    </li>







                    <li class="side-nav-menu-item" id="nav_checkin">
                        <a class="side-nav-menu-link media align-items-center" href="#" onclick="checkin()">
                            <span class="side-nav-menu-icon d-flex mr-3">
                                <i class="gd-shift-right small"></i>
                            </span>
                            <span class="side-nav-fadeout-on-closed media-body text-dark">Check-in</span>
                        </a>
                    </li>


                    <li class="side-nav-menu-item" id="nav_checkout">
                        <a class="side-nav-menu-link media align-items-center" href="#" onclick="checkout()">
                            <span class="side-nav-menu-icon d-flex mr-3">
                                <i class="gd-shift-left small"></i>
                            </span>
                            <span class="side-nav-fadeout-on-closed media-body text-dark">Check-out</span>
                        </a>
                    </li>




                    <li class="side-nav-menu-item "  id="nav_elligable">
                        <a class="side-nav-menu-link media align-items-center" href="#"onclick="elligable()">
                            <span class="side-nav-menu-icon d-flex mr-3">
                                <i class="gd-user small"></i>
                            </span>
                            <span class="side-nav-fadeout-on-closed media-body  text-dark">Elligable</span>
                        </a>
                    </li>



                    <li class="side-nav-menu-item" id="nav_report">
                        <a class="side-nav-menu-link media align-items-center" href="#" onclick="report()">
                            <span class="side-nav-menu-icon d-flex mr-3">
                                <i class="gd-printer small"></i>
                            </span>
                            <span class="side-nav-fadeout-on-closed media-body text-dark">Report</span>
                        </a>
                    </li>

                    <li class="side-nav-menu-item "  id="nav_control">
                        <a class="side-nav-menu-link media align-items-center"  href="#"onclick="control()">
                            <span class="side-nav-menu-icon d-flex mr-3">
                                <i class="gd-panel small"></i>
                            </span>
                            <span class="side-nav-fadeout-on-closed media-body  text-dark">Control</span>
                        </a>
                    </li>






                    <!-- Title -->
                    <li class="sidebar-heading h6">Others</li>
                    <!-- End Title -->

                    <!-- Users -->
                    {{-- <li class="side-nav-menu-item side-nav-has-menu">
                        <a class="side-nav-menu-link media align-items-center" href="#" data-target="#subUsers">
                            <span class="side-nav-menu-icon d-flex mr-3">
                                <i class="gd-user"></i>
                            </span>
                            <span class="side-nav-fadeout-on-closed media-body text-dark">Users</span>
                            <span class="side-nav-control-icon d-flex">
                                <i class="gd-angle-right side-nav-fadeout-on-closed"></i>
                            </span>
                            <span class="side-nav__indicator side-nav-fadeout-on-closed"></span>
                        </a>

                        <!-- Users: subUsers -->
                        <ul id="subUsers" class="side-nav-menu side-nav-menu-second-level mb-0">
                            <li class="side-nav-menu-item">
                                <a class="side-nav-menu-link" href="users.html">All Users</a>
                            </li>
                            <li class="side-nav-menu-item">
                                <a class="side-nav-menu-link" href="user-edit.html">Add new</a>
                            </li>
                        </ul>
                        <!-- End Users: subUsers -->
                    </li> --}}
                    <!-- End Users -->

                    <!-- Authentication -->
                    {{-- <li class="side-nav-menu-item side-nav-has-menu">
                        <a class="side-nav-menu-link media align-items-center" href="#" data-target="#subPages">
                            <span class="side-nav-menu-icon d-flex mr-3">
                                <i class="gd-lock"></i>
                            </span>
                            <span class="side-nav-fadeout-on-closed media-body text-dark">Authentication</span>
                            <span class="side-nav-control-icon d-flex">
                                <i class="gd-angle-right side-nav-fadeout-on-closed"></i>
                            </span>
                            <span class="side-nav__indicator side-nav-fadeout-on-closed"></span>
                        </a>

                        <!-- Pages: subPages -->
                        <ul id="subPages" class="side-nav-menu side-nav-menu-second-level mb-0">
                            <li class="side-nav-menu-item">
                                <a class="side-nav-menu-link" href="login.html">Login</a>
                            </li>
                            <li class="side-nav-menu-item">
                                <a class="side-nav-menu-link" href="register.html">Register</a>
                            </li>
                            <li class="side-nav-menu-item">
                                <a class="side-nav-menu-link" href="password-reset.html">Forgot Password</a>
                            </li>
                            <li class="side-nav-menu-item">
                                <a class="side-nav-menu-link" href="password-reset-2.html">Forgot Password 2</a>
                            </li>
                            <li class="side-nav-menu-item">
                                <a class="side-nav-menu-link" href="email-verification.html">Email Verification</a>
                            </li>
                        </ul>
                        <!-- End Pages: subPages -->
                    </li> --}}
                    <!-- End Authentication -->

                    <!-- Settings -->
                    <li class="side-nav-menu-item"  id="nav_setting">
                        <a class="side-nav-menu-link media align-items-center" href="#" onclick="setting()">
                            <span class="side-nav-menu-icon d-flex mr-3">
                                <i class="gd-settings"></i>
                            </span>
                            <span class="side-nav-fadeout-on-closed media-body text-dark">Settings</span>
                        </a>
                    </li>

                    <li class="side-nav-menu-item" id="nav_semester">
                        <a class="side-nav-menu-link media align-items-center" href="#" onclick="semester()">
                            <span class="side-nav-menu-icon d-flex mr-3">
                                <i class="gd-calendar"></i> <!-- Changed icon to a calendar or similar for semester -->
                            </span>
                            <span class="side-nav-fadeout-on-closed media-body text-dark">Semester</span>
                        </a>
                    </li>












                    <!-- End Settings -->

                    <!-- Static -->
                    {{-- <li class="side-nav-menu-item">
                        <a class="side-nav-menu-link media align-items-center" href="static-non-auth.html">
                            <span class="side-nav-menu-icon d-flex mr-3">
                                <i class="gd-file"></i>
                            </span>
                            <span class="side-nav-fadeout-on-closed media-body text-dark">Static page</span>
                        </a>
                    </li> --}}


                </ul>
            </aside>


            <div id="dash">
                @yield('content')
            </div>

        </main>
        <div class="overlay" id="overlay">
            <div class="spinner-border lik" style="width: 6rem; height: 6rem;  z-index: 9999;" role="status"></div>
          </div>
          <script src="{{ asset('graindashboard/js/graindashboard.js') }}"></script>
          <script src="{{ asset('graindashboard/js/graindashboard.vendor.js') }}"></script>

          <!-- DEMO CHARTS -->
          <script src="{{ asset('demo/resizeSensor.js') }}"></script>
          <script src="{{ asset('demo/chartist.js') }}"></script>
          <script src="{{ asset('demo/chartist-plugin-tooltip.js') }}"></script>
          <script src="{{ asset('demo/gd.chartist-area.js') }}"></script>
          <script src="{{ asset('demo/gd.chartist-bar.js') }}"></script>
          <script src="{{ asset('demo/gd.chartist-donut.js') }}"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.js" integrity="sha512-GkPcugMfi6qlxrYTRUH4EwK4aFTB35tnKLhUXGLBc3x4jcch2bcS7NHb9IxyM0HYykF6rJpGaIJh8yifTe1Ctw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


        <script>
            $.GDCore.components.GDChartistArea.init('.js-area-chart');
            $.GDCore.components.GDChartistBar.init('.js-bar-chart');
            $.GDCore.components.GDChartistDonut.init('.js-donut-chart');
        </script>

        <script>


function checkin(start = 1, end = 10){
                const selectors = [
                    "#nav_hostel",
        "#nav_aplication",
        "#nav_elligable",
        "#nav_result",
        "#nav_control",
        "#nav_profile",
        "#nav_setting",
        "#nav_report",
        "#nav_checkout",
        '#nav_semester',

    ];

    selectors.forEach(function(selector) {
        $(selector).removeClass("active");
    });
    $("#nav_checkin").addClass("active");
                $("#dash").html(
                    '<div class="spinner-container">' +
                    '<div class="black show d-flex align-items-center justify-content-center">' +
                    '<div class="spinner-border lik" style="width: 3rem; height: 3rem;" role="status">' +
                    '<span class="sr-only">Loading...</span>' +
                    '</div>' +
                    '</div>' +
                    '</div>'
                );

                $.ajax({
    url: "{{ route('admin.checkin') }}",
    method: 'GET',
    data: {
        start: start,
        end: end
    },
    success: function(response) {
        $("#dash").html(response);
        checkinPagination(); // Use the elligable-specific pagination setup
    },
    error: function(xhr) {
        const msg = `Sorry, but there was an error: ${xhr.status} ${xhr.statusText}`;
        $("#error").html(msg);
    }
});

}


// Setup pagination event listeners specifically for the elligable function
function checkinPagination() {
$('.pagination a').on('click', function(event) {
    event.preventDefault(); // Prevent default link behavior

    var url = $(this).attr('href'); // Get the URL from the clicked link
    var queryParams = new URLSearchParams(url.split('?')[1]); // Parse query parameters

    var page = queryParams.get('page'); // Get the page number
    var start = (page - 1) * 10 + 1; // Calculate start (10 records per page)
    var end = start + 9; // Calculate end (10 records per page)

    // Call the `elligable` function with new start and end values
    checkin(start, end);
});
}



            function checkout(start = 1, end = 10){
                const selectors = [
                    "#nav_hostel",
        "#nav_aplication",
        "#nav_elligable",
        "#nav_result",
        "#nav_control",
        "#nav_profile",
        "#nav_setting",
        "#nav_report",
        "#nav_checkin",
        '#nav_semester',

    ];

    selectors.forEach(function(selector) {
        $(selector).removeClass("active");
    });
    $("#nav_checkout").addClass("active");
                $("#dash").html(
                    '<div class="spinner-container">' +
                    '<div class="black show d-flex align-items-center justify-content-center">' +
                    '<div class="spinner-border lik" style="width: 3rem; height: 3rem;" role="status">' +
                    '<span class="sr-only">Loading...</span>' +
                    '</div>' +
                    '</div>' +
                    '</div>'
                );

                $.ajax({
    url: "{{ route('admin.checkout') }}",
    method: 'GET',
    data: {
        start: start,
        end: end
    },
    success: function(response) {
        $("#dash").html(response);
        checkoutPagination(); // Use the elligable-specific pagination setup
    },
    error: function(xhr) {
        const msg = `Sorry, but there was an error: ${xhr.status} ${xhr.statusText}`;
        $("#error").html(msg);
    }
});
            }


            // Setup pagination event listeners specifically for the elligable function
function checkoutPagination() {
$('.pagination a').on('click', function(event) {
    event.preventDefault(); // Prevent default link behavior

    var url = $(this).attr('href'); // Get the URL from the clicked link
    var queryParams = new URLSearchParams(url.split('?')[1]); // Parse query parameters

    var page = queryParams.get('page'); // Get the page number
    var start = (page - 1) * 10 + 1; // Calculate start (10 records per page)
    var end = start + 9; // Calculate end (10 records per page)

    // Call the `elligable` function with new start and end values
    checkout(start, end);
});
}










function semester(){
                const selectors = [
                    "#nav_hostel",
        "#nav_aplication",
        "#nav_elligable",
        "#nav_result",
        "#nav_control",
        "#nav_profile",
        "#nav_setting",
        "#nav_checkout",
        "#nav_checkin",
        '#nav_report',


    ];

    selectors.forEach(function(selector) {
        $(selector).removeClass("active");
    });
    $("#nav_semester").addClass("active");
                $("#dash").html(
                    '<div class="spinner-container">' +
                    '<div class="black show d-flex align-items-center justify-content-center">' +
                    '<div class="spinner-border lik" style="width: 3rem; height: 3rem;" role="status">' +
                    '<span class="sr-only">Loading...</span>' +
                    '</div>' +
                    '</div>' +
                    '</div>'
                );

                $("#dash").load("{{ route('admin.semester') }}", (response, status, xhr) => {
                    if (status === "error") {
                        const msg = `Sorry, but there was an error: ${xhr.status} ${xhr.statusText}`;
                        $("#error").html(msg);
                    }
                });
            }















function report(){
                const selectors = [
                    "#nav_hostel",
        "#nav_aplication",
        "#nav_elligable",
        "#nav_result",
        "#nav_control",
        "#nav_profile",
        "#nav_setting",
        "#nav_checkout",
        "#nav_checkin",
        '#nav_semester',

    ];

    selectors.forEach(function(selector) {
        $(selector).removeClass("active");
    });
    $("#nav_report").addClass("active");
                $("#dash").html(
                    '<div class="spinner-container">' +
                    '<div class="black show d-flex align-items-center justify-content-center">' +
                    '<div class="spinner-border lik" style="width: 3rem; height: 3rem;" role="status">' +
                    '<span class="sr-only">Loading...</span>' +
                    '</div>' +
                    '</div>' +
                    '</div>'
                );

                $("#dash").load("{{ route('admin.report') }}", (response, status, xhr) => {
                    if (status === "error") {
                        const msg = `Sorry, but there was an error: ${xhr.status} ${xhr.statusText}`;
                        $("#error").html(msg);
                    }
                });
            }






            function dashbord(){
                const selectors = [
        "#nav_hostel",
        "#nav_aplication",
        "#nav_elligable",
        "#nav_result",
        "#nav_control",
        "#nav_setting",
        "#nav_report",
        "#nav_checkout",
        "#nav_checkin",
        '#nav_semester',
    ];

    selectors.forEach(function(selector) {
        $(selector).removeClass("active");
    });
    $("#nav_profile").addClass("active");
                $("#dash").html(
                    '<div class="spinner-container">' +
                    '<div class="black show d-flex align-items-center justify-content-center">' +
                    '<div class="spinner-border lik" style="width: 3rem; height: 3rem;" role="status">' +
                    '<span class="sr-only">Loading...</span>' +
                    '</div>' +
                    '</div>' +
                    '</div>'
                );

                $("#dash").load("{{ route('admin.profile') }}", (response, status, xhr) => {
                    if (status === "error") {
                        const msg = `Sorry, but there was an error: ${xhr.status} ${xhr.statusText}`;
                        $("#error").html(msg);
                    }
                });
            }


            function setting() {
                const selectors = [
        "#nav_hostel",
        "#nav_aplication",
        "#nav_elligable",
        "#nav_result",
        "#nav_control",
        "#nav_profile",
        "#nav_report",
        "#nav_checkout",
        "#nav_checkin",
        '#nav_semester',
    ];

    selectors.forEach(function(selector) {
        $(selector).removeClass("active");
    });
    $("#nav_setting").addClass("active");
                $("#dash").html(
                    '<div class="spinner-container">' +
                    '<div class="black show d-flex align-items-center justify-content-center">' +
                    '<div class="spinner-border lik" style="width: 3rem; height: 3rem;" role="status">' +
                    '<span class="sr-only">Loading...</span>' +
                    '</div>' +
                    '</div>' +
                    '</div>'
                );

                $("#dash").load("{{ route('admin.setting') }}", (response, status, xhr) => {
                    if (status === "error") {
                        const msg = `Sorry, but there was an error: ${xhr.status} ${xhr.statusText}`;
                        $("#error").html(msg);
                    }
                });
            }


function hostel() {
    const selectors = [
        "#nav_profile",
        "#nav_aplication",
        "#nav_elligable",
        "#nav_result",
        "#nav_control",
        "#nav_setting",
        "#nav_report",
        "#nav_checkout",
        "#nav_checkin",
        '#nav_semester',
    ];

    selectors.forEach(function(selector) {
        $(selector).removeClass("active");
    });
    $("#nav_hostel").addClass("active");

    const spinnerHtml = '<div class="spinner-container">' +
        '<div class="black show d-flex align-items-center justify-content-center">' +
        '<div class="spinner-border lik" style="width: 3rem; height: 3rem;" role="status">' +
        '<span class="sr-only">Loading...</span>' +
        '</div>' +
        '</div>' +
        '</div>';

    $("#dash").html(spinnerHtml).load("{{ route('admin.hostel') }}", (response, status, xhr) => {
        if (status === "error") {
            const msg = `Sorry, but there was an error: ${xhr.status} ${xhr.statusText}`;
            $("#error").html(msg);
        }
    });
}

function aplication(start = 1, end = 100) {
    // Deactivate all navigation items
    const selectors = [
        "#nav_profile",
        "#nav_hostel",
        "#nav_elligable",
        "#nav_result",
        "#nav_control",
        "#nav_setting",
        "#nav_report",
        "#nav_checkout",
        "#nav_checkin",
        '#nav_semester',
    ];

    selectors.forEach(function(selector) {
        $(selector).removeClass("active");
    });

    // Activate the application navigation item
    $("#nav_aplication").addClass("active");

    // Show a loading spinner while the content is being loaded
    const spinnerHtml = '<div class="spinner-container">' +
        '<div class="black show d-flex align-items-center justify-content-center">' +
        '<div class="spinner-border lik" style="width: 3rem; height: 3rem;" role="status">' +
        '<span class="sr-only">Loading...</span>' +
        '</div>' +
        '</div>' +
        '</div>';

    $("#dash").html(spinnerHtml);

    // Load the application data with pagination
    $.ajax({
        url: "{{ route('admin.application') }}",
        method: 'GET',
        data: {
            start: start,
            end: end
        },
        success: function(response) {
            $("#dash").html(response);
            setupApplicationPagination(); // Use the application-specific pagination setup
        },
        error: function(xhr) {
            const msg = `Sorry, but there was an error: ${xhr.status} ${xhr.statusText}`;
            $("#error").html(msg);
        }
    });
}

// Setup pagination event listeners specifically for the application function
function setupApplicationPagination() {
    $('.pagination a').on('click', function(event) {
        event.preventDefault(); // Prevent default link behavior

        var url = $(this).attr('href'); // Get the URL from the clicked link
        var queryParams = new URLSearchParams(url.split('?')[1]); // Parse query parameters

        var page = queryParams.get('page'); // Get the page number
        var start = (page - 1) * 100 + 1; // Calculate start (100 records per page)
        var end = start + 99; // Calculate end (100 records per page)

        // Call the `aplication` function with new start and end values
        aplication(start, end);
    });
}


function room(blockId) {
    const selectors = [
        "#nav_profile",
        "#nav_aplication",
        "#nav_elligable",
        "#nav_result",
        "#nav_control",
        "#nav_setting",
        "#nav_report",
        "#nav_checkout",
        "#nav_checkin",
        '#nav_semester',
    ];

    selectors.forEach(function(selector) {
        $(selector).removeClass("active");
    });
    $("#nav_hostel").addClass("active");

    $("#dash").html(
        '<div class="spinner-container">' +
        '<div class="black show d-flex align-items-center justify-content-center">' +
        '<div class="spinner-border lik" style="width: 3rem; height: 3rem;" role="status">' +
        '<span class="sr-only">Loading...</span>' +
        '</div>' +
        '</div>' +
        '</div>'
    );

    $("#dash").load("{{ url('room') }}/" + blockId, (response, status, xhr) => {
        if (status === "error") {
            const msg = `Sorry, but there was an error: ${xhr.status} ${xhr.statusText}`;
            $("#error").html(msg);
        }
    });
}








function roomitem(RoomId) {
    const selectors = [
        "#nav_profile",
        "#nav_aplication",
        "#nav_elligable",
        "#nav_result",
        "#nav_control",
        "#nav_setting",
        "#nav_report",
        "#nav_checkout",
        "#nav_checkin",
        '#nav_semester',
    ];

    selectors.forEach(function(selector) {
        $(selector).removeClass("active");
    });
    $("#nav_hostel").addClass("active");

    $("#dash").html(
        '<div class="spinner-container">' +
        '<div class="black show d-flex align-items-center justify-content-center">' +
        '<div class="spinner-border lik" style="width: 3rem; height: 3rem;" role="status">' +
        '<span class="sr-only">Loading...</span>' +
        '</div>' +
        '</div>' +
        '</div>'
    );

    $("#dash").load("{{ url('roomitem') }}/" + RoomId, (response, status, xhr) => {
        if (status === "error") {
            const msg = `Sorry, but there was an error: ${xhr.status} ${xhr.statusText}`;
            $("#error").html(msg);
        }
    });
}









function floorAction(action, floorId) {
    const selectors = [
        "#nav_profile",
        "#nav_aplication",
        "#nav_elligable",
        "#nav_result",
        "#nav_control",
        "#nav_setting",
        "#nav_report",
        "#nav_checkout",
        "#nav_checkin",
        '#nav_semester',
    ];

    selectors.forEach(function(selector) {
        $(selector).removeClass("active");
    });
    $("#nav_hostel").addClass("active");

    $("#dash").html(
        '<div class="spinner-container">' +
        '<div class="black show d-flex align-items-center justify-content-center">' +
        '<div class="spinner-border lik" style="width: 3rem; height: 3rem;" role="status">' +
        '<span class="sr-only">Loading...</span>' +
        '</div>' +
        '</div>' +
        '</div>'
    );

    let url;
    switch (action) {
        case 'add':
            url = `{{ url('floor/add') }}/${floorId}`;
            break;
        case 'update':
            url = `{{ url('floor/update') }}/${floorId}`;
            break;
        case 'delete':
            url = `{{ url('floor/delete') }}/${floorId}`;
            break;
        default:
            console.error('Invalid action');
            return;
    }

    $("#dash").load(url, (response, status, xhr) => {
        if (status === "error") {
            const msg = `Sorry, but there was an error: ${xhr.status} ${xhr.statusText}`;
            $("#error").html(msg);
        }
    });
}



function elligable(start = 1, end = 10) {

const selectors = [
    "#nav_profile",
    "#nav_hostel",
    "#nav_room",
    "#nav_result",
    "#nav_aplication",
    "#nav_control",
    "#nav_setting",
    "#nav_report",
    "#nav_checkout",
    "#nav_checkin",
    '#nav_semester',
];

selectors.forEach(function(selector) {
    $(selector).removeClass("active");
});
$("#nav_elligable").addClass("active");

$("#dash").html(
    '<div class="spinner-container">' +
    '<div class="black show d-flex align-items-center justify-content-center">' +
    '<div class="spinner-border lik" style="width: 3rem; height: 3rem;" role="status">' +
    '<span class="sr-only">Loading...</span>' +
    '</div>' +
    '</div>' +
    '</div>'
);

$.ajax({
    url: "{{ route('admin.elligable') }}",
    method: 'GET',
    data: {
        start: start,
        end: end
    },
    success: function(response) {
        $("#dash").html(response);
        setupElligablePagination(); // Use the elligable-specific pagination setup
    },
    error: function(xhr) {
        const msg = `Sorry, but there was an error: ${xhr.status} ${xhr.statusText}`;
        $("#error").html(msg);
    }
});
}




// Setup pagination event listeners specifically for the elligable function
function setupElligablePagination() {
$('.pagination a').on('click', function(event) {
    event.preventDefault(); // Prevent default link behavior

    var url = $(this).attr('href'); // Get the URL from the clicked link
    var queryParams = new URLSearchParams(url.split('?')[1]); // Parse query parameters

    var page = queryParams.get('page'); // Get the page number
    var start = (page - 1) * 10 + 1; // Calculate start (10 records per page)
    var end = start + 9; // Calculate end (10 records per page)

    // Call the `elligable` function with new start and end values
    elligable(start, end);
});
}



function control() {
    const selectors = [
        "#nav_profile",
        "#nav_hostel",
        "#nav_room",
        "#nav_result",
        "#nav_aplication",
        "#nav_elligable",
        "#nav_setting",
        "#nav_report",
        "#nav_checkout",
        "#nav_checkin",
        '#nav_semester',
    ];

    selectors.forEach(function(selector) {
        $(selector).removeClass("active");
    });
    $("#nav_control").addClass("active");
    $("#dash").html(
        '<div class="spinner-container">' +
        '<div class="black show d-flex align-items-center justify-content-center">' +
        '<div class="spinner-border lik" style="width: 3rem; height: 3rem;" role="status">' +
        '<span class="sr-only">Loading...</span>' +
        '</div>' +
        '</div>' +
        '</div>'
    );

    $("#dash").load("{{ route('admin.control') }}", (response, status, xhr) => {
        if (status === "error") {
            const msg = `Sorry, but there was an error: ${xhr.status} ${xhr.statusText}`;
            $("#error").html(msg);
        }
    });
}

// Call checkAdminSessionAndControl to start the process





            function result() {
                const selectors = [
        "#nav_profile",
        "#nav_hostel",
        "#nav_room",
        "#nav_finish",
        "#nav_setting",
        "#nav_report",
        "#nav_checkout",
        "#nav_checkin",
        '#nav_semester',
    ];

    selectors.forEach(function(selector) {
        $(selector).removeClass("active");
    });
    $("#nav_result").addClass("active");
                $("#dash").html(
                    '<div class="spinner-container">' +
                    '<div class="black show d-flex align-items-center justify-content-center">' +
                    '<div class="spinner-border lik" style="width: 3rem; height: 3rem;" role="status">' +
                    '<span class="sr-only">Loading...</span>' +
                    '</div>' +
                    '</div>' +
                    '</div>'
                );

                $("#dash").load("{{ route('result') }}", (response, status, xhr) => {
                    if (status === "error") {
                        const msg = `Sorry, but there was an error: ${xhr.status} ${xhr.statusText}`;
                        $("#error").html(msg);
                    }
                });
            }


            $(window).on('load', function () {
        $(".loader").fadeOut();
        $("#preloder").delay(200).fadeOut("slow");
    });



        </script>

    </body>
</html>
