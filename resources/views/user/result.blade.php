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
<script>
    $('#gd-finish,#gd-result').removeClass('gd-check text-success').addClass(' gd-close text-danger');
</script>

    @else
    @if ($expirationDate->isPast() and empty($user->payment_status))










    <script>
        $('#gd-hostel,#gd-finish').removeClass('gd-close text-danger ').addClass(' gd-check text-success');
</script>
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
    <script>
        $('#gd-hostel,#gd-finish,#gd-result').removeClass('gd-check text-success').addClass(' gd-close text-danger');
</script>
















    @else

    @if ($publishes->first()->status == 0)
    <script>
        $('#gd-hostel,#gd-finish').removeClass('gd-close text-danger ').addClass(' gd-check text-success');
</script>

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
    @if ($user->counter == 0)
    <script>
        $('#gd-hostel,#gd-finish,#gd-result').removeClass('gd-close text-danger ').addClass(' gd-check text-success');
</script>
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
                        <span>
                            <small>
                                Please be advised that the results have already been published. If you do not wish to reapply, no further action is required on your part.
                            </small>
                        </span>
                        <br>

                        <button id="reapplyButton" class="btn btn-danger mt-3">Reapply</button>
                    </div>





                </div>

            </div>
            <!-- End Card -->
        </div>
    </div>
    @else
    <script>
        $('#gd-hostel,#gd-finish,#gd-result').removeClass('gd-close text-danger ').addClass(' gd-check text-success');
</script>
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
                        <hr>
                        <span><small>As the results have already been published, if you already reapply, please contact the administration to request approval. </small></span><br>

                        <button id="reapplyButton" class="btn btn-danger mt-3">Reapply</button>
                    </div>
                </div>

            </div>
            <!-- End Card -->
        </div>
    </div>
    @endif



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



                    @if ($user->payment_status == '')

                    <div class="alert alert-success ">
                        <strong>Congratulations, {{$user->name}}!</strong><br>
                        <span>
                            We are pleased to inform you that your application has been approved. Please review the details below. Kindly generate a control number and complete the payment before  {{ $formattedExpirationDate }}. Failure to do so may result in your bed being reallocated to another student, and you will need to reapply.
                        </span>

                    </div>

                    @else

                    @endif



                    <div class="row mb-3">
                        <div class="col font-weight-bold ">
                            Name:
                        </div>
                        <div class="col">
                            {{ $user->name }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col font-weight-bold ">
                            Registration Number:
                        </div>
                        <div class="col">
                            {{ $user->registration_number }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col font-weight-bold ">
                            Hostel:
                        </div>
                        <div class="col">
                            {{ $user->block ? $user->block->name : 'Not Assigned' }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col font-weight-bold ">
                            Room:
                        </div>
                        <div class="col">
                            {{ $user->room ? $user->room->room_number : 'Not Assigned' }}
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col font-weight-bold ">
                            Bed:
                        </div>
                        <div class="col">
                            {{ $user->bed ? $user->bed->bed_number : 'Not Assigned' }}
                        </div>
                    </div>

                    @if (empty($user->payment_status) or empty($user->Control_Number))


                    <div class="row mb-4">
                        <div class="col font-weight-bold ">
                            Time left:
                        </div>
                        <div class="col">
                            <p id="countdown" class="text-danger">Loading countdown...</p>

                        </div>
                    </div>

<script>
    var countdownInterval; // Declare the interval variable in the outer scope

    // Function to update the countdown
    function updateCountdown(expirationDateString) {
        var targetDate = new Date(expirationDateString).getTime();

        function countdown() {
            var now = new Date().getTime();
            var timeDifference = targetDate - now;

            if (timeDifference <= 0) {
                $('#countdown').text('Expired');
                $('#paymentContainer').html('<div class="col-12"><button id="reapplyButton" class="btn btn-outline-danger mt-3">Reapply</button></div>');
                clearInterval(countdownInterval);
                return;
            }

            var days = Math.floor(timeDifference / (1000 * 60 * 60 * 24));
            var hours = Math.floor((timeDifference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((timeDifference % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((timeDifference % (1000 * 60)) / 1000);

            $('#countdown').text(`${days}d ${hours}h ${minutes}m ${seconds}s`);
        }

        // Clear any existing countdown interval
        if (countdownInterval) {
            clearInterval(countdownInterval);
        }

        // Start the countdown
        countdownInterval = setInterval(countdown, 1000);

        // Initial call to display the countdown immediately
        countdown();
    }

    // Function to fetch expiration date via AJAX and update countdown
    function fetchExpirationDate() {
        $.ajax({
            url: '{{ route('get.expiration.date') }}',
            method: 'GET',
            success: function(response) {
                var expirationDateString = response.expirationDate;
                updateCountdown(expirationDateString);
            },
            error: function(xhr, status, error) {
                console.error('Failed to fetch expiration date:', error);
            }
        });
    }

    $(document).ready(function() {
        // Fetch expiration date on page load
        fetchExpirationDate();

        // Setup MutationObserver to watch for changes in the target container
        var targetNode = document.getElementById('paymentContainer'); // Adjust this selector as needed
        var observerOptions = {
            childList: true,
            subtree: true
        };

        var observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                // Call the fetch function whenever changes are detected
                fetchExpirationDate();
            });
        });

        observer.observe(targetNode, observerOptions);

        // Optionally, you may want to fetch the expiration date periodically
        // to handle cases where the server date is updated but the page hasn't refreshed
        setInterval(fetchExpirationDate, 60000); // Fetch every minute
    });
</script>

                    <script>
                        $(document).ready(function() {


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
                        <div class="col font-weight-bold ">
                            Control Number:
                        </div>
                        <div class="col">
                            <span class="">{{$user->Control_Number}}</span>

                        </div>
                    </div>
                            <!-- Paid Amount Display -->
                            <div class="row mb-4 ">
                                <div class="col font-weight-bold text-success">
                                    Paid Amount:
                                </div>
                                <div class="col">
                                    <span id="paidAmount" class="">{{ number_format($user->payment_status) ?? 'NULL' }}</span>

                                </div>
                            </div>
                            <div class="alert alert-info">
                                <strong>Important Notice:</strong><br>
                                <span>
                                    Please ensure you visit your Block Manager, <strong>{{$user->block->manager}}</strong>, start from <strong>{{ \Carbon\Carbon::parse($publishes->first()->report_date)->format('d M Y') }}</strong>
                                    , to confirm your hostel accommodation. After confirmation, kindly complete the form below to proceed with your check-in.

                                    Ensure that all required items are brought with you, and carefully inspect all Given items before confirming their condition, as you will be responsible for returning them in the <strong>same condition</strong>.

                                    Failure to adhere to these guidelines may result in your accommodation being <strong>reassigned</strong> to another student, and <strong>no refunds</strong> will be issued.
                                </span>
                            </div>

                            <style>
                                .list-group-numbered li::before {
                                    counter-increment: list-counter;
                                    content: counter(list-counter) ". ";
                                    font-weight: bold;
                                    margin-right: 10px;
                                }

                                ol.list-group-numbered {
                                    counter-reset: list-counter;
                                    padding-left: 0;
                                }

                                .list-group-numbered li {
                                    display: flex;
                                    align-items: center;
                                }
                            </style>

                            <div class="mt-4">
                                <div class="form">

                                    <div class="row">
                                        <!-- Items to Bring Section -->
                                        <div class="col-md-6">
                                            <h5 class="mb-3">Items to Bring:</h5>
                                            @if($confirmation)
                                                @if($confirmation->items_to_bring_names)
                                                    @php
                                                        $itemsToBring = json_decode($confirmation->items_to_bring_names, true);
                                                    @endphp
                                                    @if(empty($itemsToBring))
                                                        <div class="alert alert-warning">
                                                            <strong>Note:</strong> No items to bring data available in the confirmation record.
                                                        </div>
                                                    @else
                                                        <div class="row">
                                                            @foreach($itemsToBring as $item)
                                                                @if(is_array($item))
                                                                    <div class="col-md-4 mb-3">
                                                                        <div class="card p-3">
                                                                            <h6 class="card-title mb-1">{{ $item['name'] ?? 'Unknown' }}</h6>
                                                                            <p class="mb-0">Quantity: <span>{{ $item['quantity'] ?? 'N/A' }}</span></p>
                                                                            <input type="hidden" name="requirement_ids[]" value="{{ $item['id'] ?? '' }}">
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <div class="alert alert-warning">
                                                                        <strong>Note:</strong> Unexpected data format in items to bring.
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="alert alert-warning">
                                                        <strong>Note:</strong> Items to bring data is not available in the confirmation record.
                                                    </div>
                                                @endif
                                            @else
                                                @if($requirements->isEmpty())
                                                    <div class="alert alert-warning">
                                                        <strong>Note:</strong> Items are not specified by admin. Please contact the admin, as you will not be able to complete your application without them.
                                                    </div>
                                                @else
                                                    <div class="row">
                                                        @foreach($requirements as $requirement)
                                                            <div class="col-md-4 mb-3">
                                                                <div class="card p-3">
                                                                    <h6 class="card-title mb-1">{{ $requirement->name }}</h6>
                                                                    <p class="mb-0">Quantity: <span>{{ $requirement->quantity }}</span></p>
                                                                    <input type="hidden" name="requirement_ids[]" value="{{ $requirement->id }}">
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            @endif
                                        </div>

                                        <!-- Check-Out Items Section -->
                                        <div class="col-md-6">
                                            <h5 class="mb-3">Given Items:</h5>
                                            @if($confirmation)
                                                @if($confirmation->checkout_items_names)
                                                    @php
                                                        $checkoutItemsNames = json_decode($confirmation->checkout_items_names, true);
                                                    @endphp
                                                    @if(empty($checkoutItemsNames))
                                                        <div class="alert alert-warning">
                                                            <strong>Note:</strong> No check-out items data available in the confirmation record.
                                                        </div>
                                                    @else
                                                        <div class="row border">
                                                            @foreach($checkoutItemsNames as $item)
                                                                @if(is_array($item))
                                                                    <div class="col-md-4 mb-3">
                                                                        <div class="card p-3">
                                                                            <h6 class="card-title mb-1">{{ $item['name'] ?? 'Unknown' }}</h6>
                                                                            <p class="mb-0">Condition: <span class="fw-bold @if($item['condition'] === 'Good') text-success @elseif($item['condition'] === 'Bad') text-danger @elseif($item['condition'] === 'None') text-warning @endif">{{ $item['condition'] ?? 'N/A' }}</span></p>
                                                                            <input type="hidden" name="item_ids[]" value="{{ $item['id'] ?? '' }}">
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <div class="alert alert-warning">
                                                                        <strong>Note:</strong> Unexpected data format in check-out items.
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="alert alert-warning">
                                                        <strong>Note:</strong> Check-out items data is not available in the confirmation record.
                                                    </div>
                                                @endif
                                            @else
                                                @if($checkOutItems->isEmpty())
                                                    <div class="alert alert-warning">
                                                        <strong>Note:</strong> Check-out items are not specified by admin. Please contact the admin, as you will not be able to complete your application without them.
                                                    </div>
                                                @else
                                                    <div class="row">
                                                        @foreach($checkOutItems as $item)
                                                            <div class="col-md-4 mb-3">
                                                                <div class="card p-3">
                                                                    <h6 class="card-title mb-1">{{ $item->name }}</h6>
                                                                    <p class="mb-0">Condition: <span class="fw-bold @if($item->condition === 'Good') text-success @elseif($item->condition === 'Bad') text-danger @elseif($item->condition === 'None') text-warning @endif">{{ $item->condition }}</span></p>
                                                                    <input type="hidden" name="item_ids[]" value="{{ $item->id }}">
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>





                                    <!-- Buttons for Confirm -->
                                    @if(!$requirements->isEmpty() && !$checkOutItems->isEmpty())

 @if($confirmation)
@if($user->checkin == 2)

@if($user->checkout == 1)

<h5 class="mb-3">Returned Items:</h5>


@if($checkOutItemsadmin->isEmpty())
    <p class="text-muted">No items to display.</p>
@else
    <div class="row border">
        @php
            $needsToPay = false;
            $allGood = true; // Assume all items are good initially
        @endphp

        @foreach($checkOutItemsadmin as $item)
            @if($item->condition === 'Bad' || $item->condition === 'None')
                @php
                    $needsToPay = true;
                    $allGood = false; // At least one item is not good
                @endphp
            @endif

            <div class="col-md-4 mb-3">
                <div class="card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">{{ $item->name }}</h6>
                            <p class="mb-0">Condition: <span class="fw-bold @if($item->condition === 'Good') text-success @elseif($item->condition === 'Bad') text-danger @elseif($item->condition === 'None') text-warning @endif">{{ $item->condition }}</span></p>
                            <input type="hidden" name="item_ids[]" value="{{ $item->id }}">
                        </div>
                        @if($item->condition === 'Bad')
                            <span class="badge bg-danger text-white">Requires Payment</span>
                        @elseif($item->condition === 'None')
                            <span class="badge bg-warning text-dark">Not Returned - Payment Required</span>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($needsToPay)
    <div class="alert alert-warning mt-3">
        <i class="bi bi-exclamation-triangle me-2"></i>
        Attention: You are required to make payment for any items marked as "Bad" or not returned. The cost for these items will be assessed by the institute. Failure to settle these charges may result in further actions, including potential legal consequences for damage to institute property. Please ensure all outstanding payments are resolved to complete your checkout process.
    </div>

    @elseif($allGood)
        <div class="alert alert-success mt-3">
            <i class="bi bi-check-circle me-2"></i> Congratulations! All items have been returned in good condition. Thank you, and we hope to see you next time.
        </div>
    @endif
@endif






@else
<div class="text-center mt-4">
    <div class="alert alert-success">
        <strong>Note:</strong> Congratulations! You are now officially a member of {{$user->block->name}}. Enjoy your stay at the hostel, and please adhere to all regulations.
    </div>
</div>
@endif




@else
    <div class="text-center mt-4">
        <div class="alert alert-success">
            <strong>Note:</strong> You have already confirmed your items. Please await confirmation from the admin to complete your check-in process.
        </div>
    </div>
@endif







                                        @else
                                            <div class="text-center mt-4">
                                                <button id="confirm" class="btn btn-outline-primary">Confirm Given Items</button>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>





<script>
    $(document).ready(function() {

        $('#confirm').click(function() {
            if (confirm('Are you sure?')) {
            $('#overlay').css('display', 'flex');
            $.ajax({
                url: '/confirm-requirements-items',
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    user_id: '{{ auth()->id() }}',  // Assuming you have access to user ID in the view
                    block_id: '{{ $user->block->id }}' // Assuming you have block ID available in the view
                },
                success: function(response) {
                    $('#overlay').fadeOut();
                    showToast('#success-toast', response.message);
                    result();
                },
                error: function(xhr) {
                    $('#overlay').fadeOut();
                    let errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'An error occurred while processing your confirmation.';
                    showToast('#error-toast', errorMessage);
                }
            });
        }

        });

        function showToast(toastId, message) {
            var $toast = $(toastId);
            $toast.find('.toast-body').text(message);
            $toast.toast({
                delay: 3000
            });
            $toast.toast('show');
        }
    });
</script>



                    @else



                    @if (!empty($user->Control_Number))


                        <div class="row mb-4 ">
                            <div class="col font-weight-bold ">
                                Control Number:
                            </div>
                            <div class="col">
                                <span class="">{{$user->Control_Number}}</span>

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
                                <div class="col font-weight-bold ">
                                    Paid Amount:
                                </div>
                                <div class="col">
                                    <span id="paidAmount" class="">Not Paid</span>
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
                        <div class="col font-weight-bold ">
                            Control Number:
                        </div>
                        <div class="col">
                            <span id="controlNumber" class=" ">Not Generated</span>
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
                        <div class="col font-weight-bold ">
                            Paid Amount:
                        </div>
                        <div class="col">
                            <span id="paidAmount" class=" ">Not Paid</span>
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
                            $('#gd-hostel, #gd-finish, #gd-result').removeClass('gd-check text-success').addClass('gd-close text-danger');

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
