<div class="content">
    <div class="py-4 px-3 px-md-4">
        <div class="mb-3 mb-md-4 d-flex justify-content-between">
            <div class="h3 mb-0">Result</div>
        </div>
        @php
        use Carbon\Carbon;
        $expirationDate = Carbon::parse($user->expiration_date);
    @endphp

@if ($user->application != 1)

<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Warning!</strong> Please confirm your application first
</div>

    @else
    @if ($expirationDate->isPast() and empty($user->payment_status))
    <div class="row">
        <div class="col-md-12">
            <!-- Card -->
            <div class="card h-100">
                <div class="card-header d-flex">
                    <h5 class="h6 font-weight-semi-bold text-uppercase mb-0">
                        Information
                    </h5>
                </div>
                <div class="card-body pt-0">
                    <div class="alert alert-danger">
                        <span><small>Notice</small></span><br>
                        <span><small>{{$user->name}} Your application has expired. Please reapply to continue with the process.</small></span><br>
                        <button id="reapplyButton" class="btn btn-danger mt-3">Reapply</button>
                    </div>
                </div>
            </div>
            <!-- End Card -->
        </div>
    </div>
    @else

    @if ($publishes->first()->status == 0)

    <div class="row">

        <div class="col-md-12">
            <!-- Card -->
            <div class="card h-100">
                <div class="card-header d-flex">
                    <h5 class="h6 font-weight-semi-bold text-uppercase mb-0">
                        Information
                    </h5>
                </div>
                <div class="card-body pt-0">

                    <div class="alert alert-warning">
                        <span><small>Notice</small></span><br>
                        <span><small>Results have not been published yet. Please be patient as we continue
                                processing your application.</small></span>
                    </div>

                </div>

            </div>
            <!-- End Card -->
        </div>
    </div>
    @else
    @if ($user->status == 'disapproved')
    <div class="row">

        <div class="col-md-12">
            <!-- Card -->
            <div class="card h-100">
                <div class="card-header d-flex">
                    <h5 class="h6 font-weight-semi-bold text-uppercase mb-0">
                        Information
                    </h5>
                </div>
                <div class="card-body pt-0">
                    <div class="alert alert-danger">
                        <span><strong>Dear {{$user->name}},</strong></span><br>
                        <span><small>We regret to inform you that your application has not been approved due to limited capacity and recent institutional changes.</small></span><br>
                        <hr>
                        <span><small>As the results have already been published, if you already reapply, please contact the administration to request approval. Note: If you do not wish to reapply, no further action is needed.</small></span><br>

                        <button id="reapplyButton" class="btn btn-danger mt-3">Reapply</button>
                    </div>





                </div>

            </div>
            <!-- End Card -->
        </div>
    </div>
    @else

    <div class="row">
        <div class="col-md-12">
            <!-- Card -->
            <div class="card h-100 ">
                <div class="card-header d-flex align-items-center justify-content-between ">
                    <h5 class="h6 font-weight-semi-bold text-uppercase mb-0">
                        Information
                    </h5>

                </div>
                <div class="card-body pt-3">
                    <div class="alert alert-success mb-4">
                        <strong>Congratulations, {{$user->name}}!</strong><br>
                        <span>
                            We are pleased to inform you that your application has been approved. Please review the details below. Kindly generate a control number and complete the payment before  {{ $formattedExpirationDate }}. Failure to do so may result in your bed being reallocated to another student, and you will need to reapply.
                        </span>

                    </div>

                    <div class="row mb-3">
                        <div class="col font-weight-bold text-muted">
                            Name:
                        </div>
                        <div class="col">
                            {{ $user->name }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col font-weight-bold text-muted">
                            Registration Number:
                        </div>
                        <div class="col">
                            {{ $user->registration_number }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col font-weight-bold text-muted">
                            Hostel:
                        </div>
                        <div class="col">
                            {{ $user->block ? $user->block->name : 'Not Assigned' }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col font-weight-bold text-muted">
                            Room:
                        </div>
                        <div class="col">
                            {{ $user->room ? $user->room->room_number : 'Not Assigned' }}
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col font-weight-bold text-muted">
                            Bed:
                        </div>
                        <div class="col">
                            {{ $user->bed ? $user->bed->bed_number : 'Not Assigned' }}
                        </div>
                    </div>

                    @if (empty($user->payment_status) or empty($user->Control_Number))


                    <div class="row mb-4">
                        <div class="col font-weight-bold text-muted">
                            Time left:
                        </div>
                        <div class="col">
                            <p id="countdown" class="text-danger">Loading countdown...</p>

                        </div>
                    </div>
                    <script>
                        $(document).ready(function() {


                            // Get the expiration date from the Blade view
                            var expirationDateString = @json($expirationDate); // Pass the ISO 8601 date string from Blade

                            // Convert expirationDateString to a Date object and get the time in milliseconds
                            var targetDate = new Date(expirationDateString).getTime();

                            function updateCountdown() {
                                var now = new Date().getTime(); // Get current time in milliseconds
                                var timeDifference = targetDate - now;

                                // Check if the countdown has expired
                                if (timeDifference <= 0) {
                                    $('#countdown').text('Expired');
                                    $('#paymentContainer').html('<div class="col-12"><button id="reapplyButton" class="btn btn-outline-danger mt-3">Reapply</button></div>');

                                    clearInterval(countdownInterval); // Stop the countdown
                                    return;
                                }

                                // Calculate days, hours, minutes, and seconds remaining
                                var days = Math.floor(timeDifference / (1000 * 60 * 60 * 24));
                                var hours = Math.floor((timeDifference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                var minutes = Math.floor((timeDifference % (1000 * 60 * 60)) / (1000 * 60));
                                var seconds = Math.floor((timeDifference % (1000 * 60)) / 1000);

                                // Format the countdown display
                                $('#countdown').text(`${days}d ${hours}h ${minutes}m ${seconds}s`);
                            }

                            // Update countdown every second
                            var countdownInterval = setInterval(updateCountdown, 1000);

                            // Initial call to display the countdown immediately
                            updateCountdown();

                            // Use event delegation to handle click events for the reapply button
                            $('#paymentContainer').on('click', '#reapplyButton', function() {
                                // Show a confirmation prompt or message if needed
                                if (confirm('Are you sure you want to reapply?')) {
                                    $('#overlay').css('display', 'flex');
                                    $.ajax({
                                        url: '{{ route("update.expirationapp") }}', // Define this route in your web.php
                                        type: 'POST',
                                        data: {
                                            _token: '{{ csrf_token() }}',
                                            user_id: '{{ $user->id }}'
                                        },
                                        success: function(response) {
                                            $('#overlay').fadeOut();

                                            // Show toast based on server response
                                            if (response.success) {
                                                $('#gd-hostel, #gd-finish, #gd-hostel').removeClass('gd-check text-success').addClass('gd-close text-danger');

                                                console.log(response.message);
                                                // showToast('#successToast', response.message); // Show success toast with message from server
                                                hostel(); // Call your hostel function or any other logic
                                            } else {
                                                console.log(response.message);
                                                // showToast('#errorToast', response.message); // Show error toast with message from server
                                            }
                                        },
                                        error: function(xhr, status, error) {
                                            $('#overlay').fadeOut();
                                            // Show error toast with a generic message in case of error
                                            // showToast('#errorToast', 'An error occurred while trying to reset your application. Please try again.');
                                        }
                                    });
                                }
                            });
                        });
                    </script>



                    @endif
                    @if (!empty($user->payment_status) && !empty($user->Control_Number))
                    <div class="row mb-4 ">
                        <div class="col font-weight-bold text-muted">
                            Control Number:
                        </div>
                        <div class="col">
                            <span class="p-2">{{$user->Control_Number}}</span>

                        </div>
                    </div>
                            <!-- Paid Amount Display -->
                            <div class="row mb-4 ">
                                <div class="col font-weight-bold text-success">
                                    Paid Amount:
                                </div>
                                <div class="col">
                                    <span id="paidAmount" class="p-2">{{ number_format($user->payment_status) }}</span>

                                </div>
                            </div>

                    <div class="alert alert-danger">
                        <strong>Note!</strong><br>
                        <span>Please visit your Block Manager before April 1, 2024, to confirm your accommodation at the hostel. Failure to adhere to this directive may result in the reassignment of your hostel accommodation to another student, and refunds will not be issued.</span>
                    </div>

                    @else



                    @if (!empty($user->Control_Number))


                        <div class="row mb-4 ">
                            <div class="col font-weight-bold text-muted">
                                Control Number:
                            </div>
                            <div class="col">
                                <span class="p-2">{{$user->Control_Number}}</span>

                            </div>
                        </div>
                             <!-- Pay Button (Initially disabled) -->
                    <div id="paymentContainer" class="row mb-4 " >
                        <div class="col-12">
                            <button id="payButton" class="btn btn-outline-success ">
                                Pay this Amount ({{ $user->block->price }})
                            </button>
                        </div>
                    </div>

                              <!-- Paid Amount Display -->
                              <div class="row mb-4 ">
                                <div class="col font-weight-bold text-muted">
                                    Paid Amount:
                                </div>
                                <div class="col">
                                    <span id="paidAmount" class=" p-2">Not Paid</span>
                                </div>
                            </div>







                    @else
                    <!-- Button to Generate Control Number -->
                    <div class="row mb-3">
                        <div class="col-12 ">
                            <button id="generateControlNumber" class="btn btn-outline-primary ">
                                Generate Control Number
                            </button>
                        </div>
                    </div>

                    <!-- Display Control Number -->
                    <div class="row mb-3 ">
                        <div class="col font-weight-bold text-muted">
                            Control Number:
                        </div>
                        <div class="col">
                            <span id="controlNumber" class=" p-2">Not Generated</span>
                        </div>
                    </div>

                    <!-- Pay Button (Initially disabled) -->
                    <div id="paymentContainer" class="row mb-4 " style="display: none;">
                        <div class="col-12">
                            <button id="payButton" class="btn btn-outline-success ">
                                Pay this Amount ({{ $user->block->price }})
                            </button>
                        </div>
                    </div>

                    <!-- Paid Amount Display -->
                    <div class="row mb-4 ">
                        <div class="col font-weight-bold text-muted">
                            Paid Amount:
                        </div>
                        <div class="col">
                            <span id="paidAmount" class=" p-2">Not Paid</span>
                        </div>
                    </div>


                @endif

                    @endif


                </div>
            </div>
            <!-- End Card -->
        </div>
    </div>




    @endif

    @endif

    @endif

</div>



    @endif


    <script>
        $(document).ready(function() {


            // When the "Generate Control Number" button is clicked
            $('#generateControlNumber').on('click', function() {
                $('#overlay').css('display', 'flex');
                var blockPrice = {{ $user->block->price ?? 0 }};
                var controlNumber = generateControlNumber(blockPrice);
                $('#controlNumber').text(controlNumber);
                $('#overlay').fadeOut();

                // Disable the generate button after generating the control number
                $(this).prop('disabled', true);

                // Show the payment container and enable the "Pay" button
                $('#paymentContainer').show();

                // Send the generated control number to the database
                $.ajax({
                    url: '{{ route("update.control.number") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        user_id: '{{ $user->id }}',
                        control_number: controlNumber
                    },
                    success: function(response) {
                        console.log('Control number updated successfully:', response);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error updating control number:', error);
                    }
                });
            });

            // When the "Pay" button is clicked
            $('#payButton').on('click', function() {
                var blockPrice = {{ $user->block->price ?? 0 }};
                $('#paidAmount').text(formatNumber(blockPrice));
                $(this).prop('disabled', true).text('Paid');

                // Send the payment status to the database
                $.ajax({
                    url: '{{ route("update.payment.status") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        user_id: '{{ $user->id }}',
                        payment_status: blockPrice
                    },
                    success: function(response) {
                        $('#gd-result').removeClass('gd-close text-danger').addClass('gd-check text-success');
                        console.log('Payment status updated successfully:', response);
                        result();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error updating payment status:', error);
                    }
                });
            });

            function generateControlNumber(price) {
                var base = Math.floor(Math.random() * 100000) + 10000; // 10-digit base number
                var checkDigit = base % 97; // Simple modulus operation for check digit
                return `99${base}${checkDigit.toString().padStart(2, '0')}${price}`;
            }

            function formatNumber(number) {
                return number.toLocaleString(); // Improved number formatting
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
                delay: 3000 // Set the delay for the toast to hide automatically
            });
            $toast.toast('show');
        }

        $('#reapplyButton').on('click', function() {
            // Show a confirmation prompt or message if needed
            if (confirm('Are you sure you want to reapply?')) {
                $('#overlay').css('display', 'flex');
                $.ajax({
                    url: '{{ route("update.expirationapp") }}', // Define this route in your web.php
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        user_id: '{{ $user->id }}'
                    },
                    success: function(response) {
                        $('#overlay').fadeOut();

                        // Show toast based on server response
                        if (response.success) {
                            $('#gd-hostel, #gd-finish, #gd-hostel').removeClass('gd-check text-success').addClass('gd-close text-danger');

                            console.log(response.message);
                            showToast('#successToast', response.message); // Show success toast with message from server
                            hostel(); // Call your hostel function or any other logic
                        } else {
                            console.log(response.message);
                            showToast('#errorToast', response.message); // Show error toast with message from server
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#overlay').fadeOut();
                        // Show error toast with a generic message in case of error
                        showToast('#errorToast', 'An error occurred while trying to reset your application. Please try again.');
                    }
                });
            }
        });
    });
</script>


</div>
