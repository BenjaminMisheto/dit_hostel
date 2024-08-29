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

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

  <body class="">
    <div id="preloder">
        <div class="loader">
            <div class="spinner-border " style="width: 3rem; height: 3rem;"></div>

        </div>
    </div>
    <main class="main">
        <div id="dash">
            @yield('content')
        </div>

    </main>
    <div class="overlay" id="overlay">
        <div class="spinner-border lik" style="width: 6rem; height: 6rem;  z-index: 9999;" role="status"></div>
      </div>
      <script src="{{ asset('graindashboard/js/graindashboard.js') }}" defer></script>
      <script src="{{ asset('graindashboard/js/graindashboard.vendor.js') }}" defer></script>

    <script>

        $(window).on('load', function () {
        $(".loader").fadeOut();
        $("#preloder").delay(200).fadeOut("slow");
    });
    </script>
  </body>
</html>






