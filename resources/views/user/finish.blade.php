@php
use Carbon\Carbon;
$expirationDate = $user->expiration_date ? Carbon::parse($user->expiration_date) : null;
@endphp
<div class="content">
    <div class="py-4 px-3 px-md-4">
        <div class="mb-3 mb-md-4 d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Application</h3>
            <p>{{ auth()->user()->semester->name ?? 'No semester found' }}</p>

        </div>

        @php
            $isEmpty = empty($user->block_id) || empty($user->room_id) || empty($user->floor_id) ;
        @endphp

        @if ($isEmpty)
        <div class="container full-height d-flex align-items-center justify-content-center" style="height: 70vh;">
            <div class="" style="width: 18rem;">
                <div class="card-body text-center">
                    <i class="gd-alert text-danger" style="font-size: 3rem;"></i><br>
                    <small class="card-title">No Please ensure that your application is confirmed before proceeding.</small>
                </div>
            </div>
        </div>






            <script>
                $('#gd-hostel,#gd-finish,#gd-result').removeClass('gd-check text-success').addClass(' gd-close text-danger');
        </script>
        @else
        <div class="">
            <div class="alert alert-info ">
                <strong>Important Notice:</strong>
                <p class="mb-0">Before confirming your application, please verify all details carefully. Once confirmed, you will not be able to edit your application. If you wish to choose another hostel bed, feel free to select from the available options.</p>
                <p class="mb-0">Please note that the institution reserves the right to reassign your room if necessary. We appreciate your understanding.</p>
                <p class="mb-0"><strong>Expiration Policy:</strong> If your application expires, the selected block, room, and bed will be released and made available to other students. To secure your place, please confirm your application before the expiration time.</p>
            </div>
        </div>






        @if ($expirationDate && $expirationDate->isPast() && empty($user->payment_status) && !empty($user->bed))
        <div class="alert alert-danger mb-3" role="alert">
            <strong>Dear {{ $user->name }}, we regret to inform you that your application has expired.</strong> If you wish to reapply, please visit the hostel page and submit a new application.
        </div>

        @else
        @if ($user->application == 1)
            <div class="alert alert-success mb-3" role="alert">
                <strong>Congratulations, {{$user->name}}!</strong> Your application has been successfully submitted. Please check the results page regularly to stay updated on the status of your application.
            </div>
        @endif
        @endif






        <div class="row g-3">
            <div class="col-md-12 col-xl-12">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="font-weight-semi-bold text-uppercase mb-0">Information</h5>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row align-items-center">

                            <!-- Profile Image -->
                            <div class="col-md-4 text-center mb-4">
                                <img id="profileImage" class="profile-image img-fluid rounded-circle border border-light"
                                     src="{{ $user->profile_photo_path ?? 'img/placeholder.jpg' }}" alt="Profile Image"
                                     style="max-width: 220px; height: auto;">
                            </div>

                            <!-- User Information -->
                            <div class="col-md-8">
                                <div class="row mb-3">
                                    <div class="col  mb-2">
                                        Name
                                    </div>
                                    <div class="col  mb-2">
                                        {{ $user->name }}
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col  mb-2">
                                        Number
                                    </div>
                                    <div class="col  mb-2">
                                        {{ $user->registration_number }}
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col  mb-2">
                                        Hostel
                                    </div>
                                    <div class="col  mb-2">
                                        {{ $user->block ? $user->block->name : 'Not Assigned' }}
                                    </div>
                                </div>


                                <div class="row mb-3">
                                    <div class="col  mb-2">
                                        Floor
                                    </div>
                                    <div class="col  mb-2">
                                        {{ $user->floor ? $user->floor->floor_number : 'Not Assigned' }}
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col  mb-2">
                                Room
                                    </div>
                                    <div class="col  mb-2">
                                        {{ $user->room ? $user->room->room_number : 'Not Assigned' }}
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col  mb-2">
                                        Bed
                                    </div>
                                    <div class="col  mb-2">
                                        {{ $user->bed ? 'Bed ' . $user->bed->bed_number : 'Not Assigned' }}
                                    </div>
                                </div>

                                @if ($user->expiration_date and $user->application ==! 1)
                                <div class="row mb-3">
                                    <div class="col mb-2">
                                        Expiration Time
                                    </div>
                                    <div class="col mb-2">
                                        <span id="countdown">
                                            @if ($user->expiration_date )
                                                {{ \Carbon\Carbon::parse($user->expiration_date)->format('Y-m-d H:i:s') }}
                                            @else
                                                Null
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                @endif

                                @if ($user->expiration_date and $user->application ==! 1)

                                    <script>
                                        $(document).ready(function () {
                                            let expirationTime = "{{ \Carbon\Carbon::parse($user->expiration_date)->format('Y-m-d H:i:s') }}";

                                            if (expirationTime !== "Null") {
                                                let countDownDate = new Date(expirationTime).getTime();
                                                let countdownElement = $("#countdown");

                                                function updateCountdown() {
                                                    let now = new Date().getTime();
                                                    let distance = countDownDate - now;

                                                    if (distance <= 0) {
                                                        countdownElement.html("<span class='text-danger fw-bold'>Expired</span>");
                                                        clearInterval(interval);
                                                        return;
                                                    }

                                                    let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                                    let seconds = Math.floor((distance % (1000 * 60)) / 1000);

                                                    countdownElement.html(`<span class='text-success fw-bold'>${minutes}m ${seconds}s</span>`);
                                                }

                                                // Update immediately and then every second
                                                updateCountdown();
                                                let interval = setInterval(updateCountdown, 1000);
                                            }
                                        });
                                    </script>
                                @endif



                            </div>



                                   <!-- Alert for Important Notice -->
                        </div>


                    <!-- Alerts Based on Application Status -->
@if ($expirationDate && $expirationDate->isPast() && empty($user->payment_status) && !empty($user->bed))

<div class="text-center d-flex justify-content-center">
    <button id="reapplyButton" class="btn btn-danger mt-3">Reapply</button>

</div>
@else
@if ($user->application == 1)

    <div class="text-center d-flex justify-content-center">
        <button type="button" class="btn btn-outline-success mr-3" onclick="result()">Result</button>
    </div>
@else
<form id="confirmApplicationForm" class="mb-3">
    <div class="text-center d-flex justify-content-center">
        <button type="button" class="btn btn-outline-danger mr-3" onclick="hostel()">Go Back</button>
        <button type="submit" class="btn btn-outline-success" id="confirmButton">Confirm</button>
    </div>
</form>


@endif
@endif

                    </div>
                </div>
            </div>
        </div>


        @endif
    </div>
</div><script>
    $(document).ready(function () {
    $('#confirmButton').on('click', function (e) {
        e.preventDefault(); // Prevent form submission
        $('#overlay').css('display', 'flex');

        $.ajax({
            url: '/confirm-application', // Update with your route
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                application: 1
            },
            success: function(response) {
                $('#gd-finish').removeClass('gd-close text-danger').addClass('gd-check text-success');
                $('#overlay').fadeOut();
                showToast('success-toast', response.message); // Display success toast
                result();
            },
            error: function(xhr) {
                $('#overlay').fadeOut();

                var errorMessage = 'An error occurred while confirming your application. Please try again.';

                try {
                    // Try to parse the response JSON to get error details
                    var response = xhr.responseJSON;

                    if (response && response.message) {
                        errorMessage = response.message; // Extract server message
                    } else if (response && response.errors) {
                        // Collect and format error messages
                        var errorMessages = [];
                        $.each(response.errors, function(key, value) {
                            errorMessages.push(value.join('<br>'));
                        });
                        errorMessage = errorMessages.join('<br>');
                    }
                } catch (e) {
                    console.error('Error parsing response JSON:', e);
                }

                showToast('error-toast', errorMessage);
                console.error('AJAX error response:', xhr.responseText);
            }
        });
    });

    function showToast(toastId, message) {
        var toastElement = $('#' + toastId);
        toastElement.find('.toast-body').html(message); // Use html() to support line breaks
        toastElement.toast({ delay: 5000 }); // Increase delay for better visibility
        toastElement.toast('show');
    }
});

</script>

<script>
    $(document).ready(function() {

        // Function to show toast notifications
        function showToast(toastId, message) {
            var $toast = $(toastId);
            $toast.find('.toast-body').text(message);
            $toast.toast({
                delay: 3000 // Automatically hide after 3 seconds
            });
            $toast.toast('show'); // Show the toast
        }

        // Handle the "Reapply" button click
        $('#reapplyButton').on('click', function() {
            $('#overlay').css('display', 'flex'); // Show overlay

            // AJAX request to reset the application
            $.ajax({
                url: '{{ route("update.expirationapp") }}', // Define this route in web.php
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // CSRF token for security
                    user_id: '{{ $user->id }}'    // User ID
                },
                success: function(response) {
                    $('#overlay').fadeOut(); // Hide overlay after AJAX call

                    // Show toast and update UI based on response
                    if (response.success) {

                        // Update result UI classes
                        $('#gd-hostel, #gd-finish, #gd-result')
                            .removeClass('gd-check text-success')
                            .addClass('gd-close text-danger');
                        console.log(response.message);
                        showToast('#successToast', response.message); // Show success toast

                        // Hide modal after successful reapply
                        //hidemodalreappy();
                        hostel();
                    } else {
                        console.log(response.message);
                        showToast('#errorToast', response.message); // Show error toast if not successful
                    }
                },
                error: function(xhr, status, error) {
                    $('#overlay').fadeOut(); // Hide overlay on error

                    // Show error toast with a generic message
                    showToast('#errorToast', 'An error occurred while trying to reset your application. Please try again.');
                }
            });
        });

    });
</script>
