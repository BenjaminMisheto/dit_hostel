<div class="content">
    <div class="py-4 px-3 px-md-4">
        <div class="mb-3 mb-md-4 d-flex justify-content-between">
            <div class="h3 mb-0">Result</div>
        </div>
        @php
        use Carbon\Carbon;
        $expirationDate = $user->expiration_date ? Carbon::parse($user->expiration_date) : null;
        @endphp

        @if ($user->application != 1)

        <div class="container full-height d-flex align-items-center justify-content-center" style="height: 70vh;">
            <div class="" style="width: 18rem;">
                <div class="card-body text-center">
                    <i class="gd-alert text-danger" style="font-size: 3rem;"></i><br>
                    <strong>Warning!</strong> Please confirm your application first
                </div>
            </div>
        </div>
        <script>
            $('#gd-finish,#gd-result').removeClass('gd-check text-success').addClass(' gd-close text-danger');
        </script>

        @else
        @if ($expirationDate && $expirationDate->isPast() and empty($user->payment_status))

        <script>
            $('#gd-hostel,#gd-finish').removeClass('gd-close text-danger ').addClass(' gd-check text-success');
        </script>

        <script>
            $('#gd-hostel,#gd-finish,#gd-result').removeClass('gd-check text-success').addClass(
            ' gd-close text-danger');
        </script>

        <button class="btn btn-danger mt-3" data-toggle="modal" data-target="#reapplyModal">Re-apply</button>

        <div class="container full-height d-flex align-items-center justify-content-center" style="height: 70vh;">
            <div class="" style="width: 18rem;">
                <div class="card-body text-center">
                    <i class="gd-alert text-danger" style="font-size: 3rem;"></i><br>
                    <span><strong>Dear {{$user->name}},</strong></span><br>
                    <hr>
                    <span><small>Your application expired on {{$user->expiration_date}}. To secure a hostel, please
                            reapply if you wish to proceed.</small></span><br>

                    <button id="reapplyButton" class="btn btn-danger mt-3">Reapply</button>
                </div>
            </div>
        </div>

        @else

        @if ($publishes->first()->status == 0)
        <script>
            $('#gd-hostel,#gd-finish').removeClass('gd-close text-danger ').addClass(' gd-check text-success');
        </script>

        <div class="container full-height d-flex align-items-center justify-content-center" style="height: 70vh;">
            <div class="" style="width: 18rem;">
                <div class="card-body text-center">
                    <i class="gd-alert text-danger" style="font-size: 3rem;"></i><br>
                    <span><strong>Dear {{$user->name}},</strong></span><br>
                    <hr>
                    <span><small>Results have not been published yet. Please be patient as we continue
                            processing your application.</small></span><br>
                </div>
            </div>
        </div>

        @else
        @if ($user->status == 'disapproved')

        @if ($user->afterpublish == 1)
        <script>
            $('#gd-hostel,#gd-finish,#gd-result').removeClass('gd-close text-danger ').addClass(
                ' gd-check text-success');
        </script>

        <div class="container full-height d-flex align-items-center justify-content-center" style="height: 70vh;">
            <div class="" style="width: 18rem;">
                <div class="card-body text-center">
                    <i class="gd-alert text-danger" style="font-size: 3rem;"></i><br>

                    <span><strong>Dear {{$user->name}},</strong></span><br>
                    <span><small>Thank you for your patience. Your application is being processed, and you will be
                            notified once it has been reviewed.</small></span>

                </div>
            </div>
        </div>

        @else
        <script>
            $('#gd-hostel,#gd-finish,#gd-result').removeClass('gd-close text-danger ').addClass(
                ' gd-check text-success');
        </script>

        <div class="container full-height d-flex align-items-center justify-content-center" style="height: 70vh;">
            <div class="" style="width: 18rem;">
                <div class="card-body text-center">
                    <i class="gd-alert text-danger" style="font-size: 3rem;"></i><br>

                    <span><strong>Dear {{$user->name}},</strong></span><br>
                    <hr>
                    <span><small>We regret to inform you that your application was not approved. If you wish to reapply,
                            please click the button below.</small></span><br>
                    <button class="btn btn-danger mt-3" data-toggle="modal"
                        data-target="#reapplyModal">Re-apply</button>

                </div>
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
                                We are pleased to inform you that your application has been approved. Please review the
                                details below. Kindly generate a control number and complete the payment before
                                {{ $formattedExpirationDate }}. Failure to do so may result in your bed being
                                reallocated to another student, and you will need to reapply.
                            </span>

                        </div>

                        @else

                        @endif
                        <div class="row ">

                            <div class="col-md-4 text-center mb-4">
                                <img id="profileImage"
                                    class="profile-image img-fluid rounded-circle border border-light"
                                    src="{{ $user->profile_photo_path ?? 'img/placeholder.jpg' }}" alt="Profile Image"
                                    style="max-width: 220px; height: auto;">
                            </div>

                            <div class="col-md-8 mb-4">
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

                                            // Handle expiration
                                            if (timeDifference <= 0) {
                                                $('#countdown').text('Expired');
                                                $('#paymentContainer').html(
                                                    '<button class="btn btn-danger mt-3" data-toggle="modal" data-target="#reapplyModal">Re-apply</button>'
                                                );
                                                clearInterval(countdownInterval); // Stop the countdown
                                                return;
                                            }

                                            // Calculate time components
                                            var days = Math.floor(timeDifference / (1000 * 60 * 60 * 24));
                                            var hours = Math.floor((timeDifference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                            var minutes = Math.floor((timeDifference % (1000 * 60 * 60)) / (1000 * 60));
                                            var seconds = Math.floor((timeDifference % (1000 * 60)) / 1000);

                                            // Update countdown display
                                            $('#countdown').text(`${days}d ${hours}h ${minutes}m ${seconds}s`);
                                        }

                                        // Clear any existing countdown interval
                                        if (countdownInterval) {
                                            clearInterval(countdownInterval);
                                        }

                                        // Start the countdown
                                        countdownInterval = setInterval(countdown, 1000);
                                        countdown(); // Initial call to display the countdown immediately
                                    }

                                    // Function to fetch expiration date via AJAX and update countdown
                                    function fetchExpirationDate() {
                                        $.ajax({
                                            url: '{{ route('get.expiration.date') }}',
                                            method: 'GET',
                                            success: function(response) {
                                                var expirationDateString = response.expirationDate;
                                                updateCountdown(expirationDateString); // Update the countdown with fetched date
                                            },
                                            error: function(xhr, status, error) {
                                                console.error('Failed to fetch expiration date:', error);
                                            }
                                        });
                                    }

                                    // Document ready handler
                                    $(document).ready(function() {
                                        // Fetch expiration date on page load
                                        fetchExpirationDate();

                                        // MutationObserver to watch for changes in the target container
                                        var targetNode = document.getElementById('paymentContainer');
                                        var observerOptions = { childList: true, subtree: true };

                                        var observer = new MutationObserver(function(mutations) {
                                            mutations.forEach(function(mutation) {
                                                fetchExpirationDate(); // Fetch the expiration date on any change
                                            });
                                        });

                                        observer.observe(targetNode, observerOptions);

                                        // Optionally fetch expiration date periodically every minute
                                        setInterval(fetchExpirationDate, 60000);
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
                                        <span id="paidAmount"
                                            class="">{{ number_format($user->payment_status) ?? 'NULL' }}</span>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-info">
                            <strong>Important Notice:</strong><br>
                            <span>
                                Please ensure you visit your Block Manager, <strong>{{$user->block->manager}}</strong>,
                                start from
                                <strong>{{ \Carbon\Carbon::parse($publishes->first()->report_date)->format('d M Y') }}</strong>
                                , to confirm your hostel accommodation. After confirmation, kindly complete the form
                                below to proceed with your check-in.

                                Ensure that all required items are brought with you, and carefully inspect all Given
                                items before confirming their condition, as you will be responsible for returning them
                                in the <strong>same condition</strong>.

                                Failure to adhere to these guidelines may result in your accommodation being
                                <strong>reassigned</strong> to another student, and <strong>no refunds</strong> will be
                                issued.
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
                                            <strong>Note:</strong> No items to bring data available in the confirmation
                                            record.
                                        </div>
                                        @else
                                        <div class="row">
                                            @foreach($itemsToBring as $item)
                                            @if(is_array($item))
                                            <div class="col-md-4 mb-3">
                                                <div class="card p-3">
                                                    <h6 class="card-title mb-1">{{ $item['name'] ?? 'Unknown' }}</h6>
                                                    <p class="mb-0">Quantity:
                                                        <span>{{ $item['quantity'] ?? 'N/A' }}</span></p>
                                                    <input type="hidden" name="requirement_ids[]"
                                                        value="{{ $item['id'] ?? '' }}">
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
                                            <strong>Note:</strong> Items to bring data is not available in the
                                            confirmation record.
                                        </div>
                                        @endif
                                        @else
                                        @if($requirements->isEmpty())
                                        <div class="alert alert-warning">
                                            <strong>Note:</strong> Items are not specified by admin. Please contact the
                                            admin, as you will not be able to complete your application without them.
                                        </div>
                                        @else
                                        <div class="row">
                                            @foreach($requirements as $requirement)
                                            <div class="col-md-4 mb-3">
                                                <div class="card p-3">
                                                    <h6 class="card-title mb-1">{{ $requirement->name }}</h6>
                                                    <p class="mb-0">Quantity: <span>{{ $requirement->quantity }}</span>
                                                    </p>
                                                    <input type="hidden" name="requirement_ids[]"
                                                        value="{{ $requirement->id }}">
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
                                            <strong>Note:</strong> No check-out items data available in the confirmation
                                            record.
                                        </div>
                                        @else
                                        <div class="row border">
                                            @foreach($checkoutItemsNames as $item)
                                            @if(is_array($item))
                                            <div class="col-md-4 mb-3">
                                                <div class="card p-3">
                                                    <h6 class="card-title mb-1">{{ $item['name'] ?? 'Unknown' }}</h6>
                                                    <p class="mb-0">Condition: <span
                                                            class="fw-bold @if($item['condition'] === 'Good') text-success @elseif($item['condition'] === 'Bad') text-danger @elseif($item['condition'] === 'None') text-warning @endif">{{ $item['condition'] ?? 'N/A' }}</span>
                                                    </p>
                                                    <input type="hidden" name="item_ids[]"
                                                        value="{{ $item['id'] ?? '' }}">
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
                                            <strong>Note:</strong> Check-out items data is not available in the
                                            confirmation record.
                                        </div>
                                        @endif
                                        @else
                                        @if($checkOutItems->isEmpty())
                                        <div class="alert alert-warning">
                                            <strong>Note:</strong> Check-out items are not specified by admin. Please
                                            contact the admin, as you will not be able to complete your application
                                            without them.
                                        </div>
                                        @else
                                        <div class="row">
                                            @foreach($checkOutItems as $item)
                                            <div class="col-md-4 mb-3">
                                                <div class="card p-3">
                                                    <h6 class="card-title mb-1">{{ $item->name }}</h6>
                                                    <p class="mb-0">Condition: <span
                                                            class="fw-bold @if($item->condition === 'Good') text-success @elseif($item->condition === 'Bad') text-danger @elseif($item->condition === 'None') text-warning @endif">{{ $item->condition }}</span>
                                                    </p>
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
                                                    <p class="mb-0">Condition: <span
                                                            class="fw-bold @if($item->condition === 'Good') text-success @elseif($item->condition === 'Bad') text-danger @elseif($item->condition === 'None') text-warning @endif">{{ $item->condition }}</span>
                                                    </p>
                                                    <input type="hidden" name="item_ids[]" value="{{ $item->id }}">
                                                </div>
                                                @if($item->condition === 'Bad')
                                                <span class="badge bg-danger text-white">Requires Payment</span>
                                                @elseif($item->condition === 'None')
                                                <span class="badge bg-warning text-dark">Not Returned - Payment
                                                    Required</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                @if($needsToPay)
                                <div class="alert alert-warning mt-3">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    Attention: You are required to make payment for any items marked as "Bad" or not
                                    returned. The cost for these items will be assessed by the institute. Failure to
                                    settle these charges may result in further actions, including potential legal
                                    consequences for damage to institute property. Please ensure all outstanding
                                    payments are resolved to complete your checkout process.
                                </div>

                                @elseif($allGood)
                                <div class="alert alert-success mt-3">
                                    <i class="bi bi-check-circle me-2"></i> Congratulations! All items have been
                                    returned in good condition. Thank you, and we hope to see you next time.
                                </div>
                                @endif
                                @endif

                                @else
                                <div class="text-center mt-4">
                                    <div class="alert alert-success">
                                        <strong>Note:</strong> Congratulations! You are now officially a member of
                                        {{$user->block->name}}, Room {{$user->room->room_number}} . Enjoy your stay at
                                        the hostel, and please adhere to all regulations.
                                    </div>
                                </div>
                                @endif

                                @else
                                <div class="text-center mt-4">
                                    <div class="alert alert-warning">
                                        <strong>Note:</strong> You have already confirmed your items. Please await
                                        confirmation from the admin to complete your check-in process.
                                    </div>
                                </div>
                                @endif


                        @else

                                <div class="text-center mt-4">
                                    <button type="button" class="btn btn-outline-primary" data-toggle="modal"
                                        data-target="#confirmItemModal">
                                        Confirm Given Item
                                    </button>

                                </div>

                                @endif
                                @endif
                            </div>
                        </div>

                        <script>
                            $(document).ready(function() {

                                // Handle confirmation button click
                                $('#confirm').click(function() {
                                    hideModal(); // Close modal before proceeding
                                    $('#overlay').css('display', 'flex'); // Show overlay

                                    // AJAX request to confirm requirement items
                                    $.ajax({
                                        url: '/confirm-requirements-items',
                                        method: 'POST',
                                        data: {
                                            _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token for security
                                            user_id: '{{ auth()->id() }}', // User ID
                                            block_id: '{{ $user->block->id }}' // Block ID
                                        },
                                        success: function(response) {
                                            $('#overlay').fadeOut(); // Hide overlay on success
                                            showToast('#success-toast', response.message); // Show success toast
                                        },
                                        error: function(xhr) {
                                            $('#overlay').fadeOut(); // Hide overlay on error
                                            let errorMessage = xhr.responseJSON && xhr.responseJSON.message
                                                ? xhr.responseJSON.message
                                                : 'An error occurred while processing your confirmation.'; // Default error message
                                            showToast('#error-toast', errorMessage); // Show error toast
                                        }
                                    });
                                });

                                // Function to show toast notifications
                                function showToast(toastId, message) {
                                    var $toast = $(toastId);
                                    $toast.find('.toast-body').text(message); // Set the message in the toast body
                                    $toast.toast({ delay: 3000 }); // Toast delay of 3 seconds
                                    $toast.toast('show'); // Display the toast
                                }

                                // Function to hide modal and trigger result after closing
                                function hideModal() {
                                    $('#confirmItemModal').modal('hide'); // Close the modal
                                    $('#confirmItemModal').on('hidden.bs.modal', function() {
                                        result(); // Ensure the result() function is called after the modal closes
                                    });
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
                        <div id="paymentContainer" class="row mb-4 ">
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
            // Handle the "Generate Control Number" button click
            $('#generateControlNumber').on('click', function() {
                $('#overlay').css('display', 'flex'); // Show overlay

                var blockPrice = {{ $user->block->price ?? 0 }}; // Fetch block price or default to 0
                var controlNumber = generateControlNumber(blockPrice); // Generate control number

                $('#controlNumber').text(controlNumber); // Display the generated control number
                $('#overlay').fadeOut(); // Hide overlay after generating the control number

                $(this).prop('disabled', true); // Disable the generate button

                $('#paymentContainer').show(); // Show the payment container

                // Send the generated control number to the server via AJAX
                $.ajax({
                    url: '{{ route("update.control.number") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}', // CSRF token for security
                        user_id: '{{ $user->id }}',   // User ID
                        control_number: controlNumber // Generated control number
                    },
                    success: function(response) {
                        console.log('Control number updated successfully:', response);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error updating control number:', error);
                    }
                });
            });

            // Handle the "Pay" button click
            $('#payButton').on('click', function() {
                var blockPrice = {{ $user->block->price ?? 0 }}; // Fetch block price or default to 0

                $('#paidAmount').text(formatNumber(blockPrice)); // Display formatted block price
                $(this).prop('disabled', true).text('Paid'); // Disable the pay button and change text to "Paid"

                // Send the payment status to the server via AJAX
                $.ajax({
                    url: '{{ route("update.payment.status") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',    // CSRF token for security
                        user_id: '{{ $user->id }}',      // User ID
                        payment_status: blockPrice       // Block price as payment status
                    },
                    success: function(response) {
                        $('#gd-result')
                            .removeClass('gd-close text-danger')
                            .addClass('gd-check text-success'); // Update result UI
                        console.log('Payment status updated successfully:', response);
                        result(); // Call the result function
                    },
                    error: function(xhr, status, error) {
                        console.error('Error updating payment status:', error);
                    }
                });
            });

            // Function to generate control number based on price
            function generateControlNumber(price) {
                var base = Math.floor(Math.random() * 100000) + 10000; // 10-digit base number
                var checkDigit = base % 97; // Simple modulus operation for check digit
                return `99${base}${checkDigit.toString().padStart(2, '0')}${price}`; // Concatenate parts
            }

            // Function to format numbers with commas
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
                        hidemodalreappy();
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

        // Function to close the modal and call the hostel() function
        function hidemodalreappy() {
            $('#reapplyModal').modal('hide'); // Close the modal

            // Ensure hostel() is called after modal is closed
            $('#reapplyModal').on('hidden.bs.modal', function() {
                hostel();
            });
        }
    });
</script>

</div>

<div id="confirmItemModal" class="modal fade" role="dialog" aria-labelledby="confirmItemModalLabel" aria-hidden="true">
    <div class="modal-dialog rounded" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="text-center rounded">
                    <i class="gd-alert icon-text icon-text-xxl d-block text-danger mb-3 mb-md-4"></i>
                    <div class="h5 font-weight-semi-bold mb-2">Confirm Item Return</div>
                    <p class="mb-3 mb-md-4">Once you <strong>confirm the received items</strong>, no further
                        modifications will be allowed. Please ensure that each item is returned in the
                        <strong>agreed-upon condition</strong>. <strong>Any damage or failure to return the items will
                            result in a fine</strong>.</p>

                    <div class="d-flex justify-content-between mb-4">
                        <a class="btn btn-outline-success" href="#" id="confirm">Yes, Confirm</a>
                        <a class="btn btn-outline-danger" href="#" data-dismiss="modal">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="reapplyModal" class="modal fade" role="dialog" aria-labelledby="Re-applyModalLabel" aria-hidden="true">
    <div class="modal-dialog rounded" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="text-center rounded">
                    <i class="gd-alert icon-text icon-text-xxl d-block text-danger mb-3 mb-md-4"></i>
                    <div class="h5 font-weight-semi-bold mb-2">Are you sure ?</div>

                    <div class="d-flex justify-content-between mb-4">
                        <a class="btn btn-outline-success" href="#" id="reapplyButton">Yes, Confirm</a>
                        <a class="btn btn-outline-danger" href="#" data-dismiss="modal">No</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
