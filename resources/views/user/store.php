<div class="content">
    <div class="py-4 px-3 px-md-4">
        <div class="mb-3 mb-md-4 d-flex justify-content-between">
            <div class="h3 mb-0">Result</div>
        </div>
        @if ($user->application !== 1)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Warning!</strong> Please confirm your application first
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
                            <span><small>Sorry!: {{$user->name}}</small></span><br>
                            <span><small>We regret to inform you that your application has not been approved due to
                                    limited capacity and recent changes made by the institution.</small></span>
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
                <div class="card h-100 shadow-sm">
                    <div class="card-header d-flex align-items-center justify-content-between ">
                        <h5 class="h6 font-weight-semi-bold text-uppercase mb-0">
                            Information
                        </h5>
                    </div>
                    <div class="card-body pt-3">
                        <div class="alert alert-success mb-4">
                            <strong>Congratulations, {{$user->name}}!</strong><br>
                            <span>
                                We are pleased to inform you that your application has been approved. Please review the details below. Kindly generate a control number and complete the payment by  April 1, 2024. Failure to do so may result in your bed being reallocated to another student, and you will need to reapply.
                            </span>

                        </div>

                        <div class="row mb-3">
                            <div class="col-5 font-weight-bold text-muted">
                                Name:
                            </div>
                            <div class="col-7">
                                {{ $user->name }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-5 font-weight-bold text-muted">
                                Registration Number:
                            </div>
                            <div class="col-7">
                                {{ $user->registration_number }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-5 font-weight-bold text-muted">
                                Hostel:
                            </div>
                            <div class="col-7">
                                {{ $user->block ? $user->block->name : 'Not Assigned' }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-5 font-weight-bold text-muted">
                                Room:
                            </div>
                            <div class="col-7">
                                {{ $user->room ? $user->room->room_number : 'Not Assigned' }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-5 font-weight-bold text-muted">
                                Bed:
                            </div>
                            <div class="col-7">
                                {{ $user->bed ? $user->bed->bed_number : 'Not Assigned' }}
                            </div>
                        </div>










                        @if (!empty($user->payment_status) && !empty($user->Control_Number))
                        <div class="row mb-4 ">
                            <div class="col-5 font-weight-bold text-muted">
                                Control Number:
                            </div>
                            <div class="col-7">
                                <span class="p-2">{{$user->Control_Number}}</span>

                            </div>
                        </div>
                                <!-- Paid Amount Display -->
                                <div class="row mb-4 ">
                                    <div class="col-5 font-weight-bold text-success">
                                        Paid Amount:
                                    </div>
                                    <div class="col-7">
                                        <span id="paidAmount" class="p-2">{{ number_format($user->payment_status) }}</span>

                                    </div>
                                </div>

                        <div class="alert alert-danger">
                            <strong>Note!</strong><br>
                            <span>Please visit your Block Manager before April 1, 2024, to confirm your accommodation at the hostel. Failure to adhere to this directive may result in the reassignment of your hostel accommodation to another student, and refunds will not be issued.</span>
                        </div>

                        @else



                        @if (!empty($user->Control_Number))

                            <!-- Button to Generate Control Number -->
                            <div class="row mb-3">
                                <div class="col-12 ">
                                    <button  class="btn btn-outline-primary " disabled>
                                        Generate Control Number
                                    </button>
                                </div>
                            </div>
                            <div class="row mb-4 ">
                                <div class="col-5 font-weight-bold text-muted">
                                    Control Number:
                                </div>
                                <div class="col-7">
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
                                    <div class="col-5 font-weight-bold text-muted">
                                        Paid Amount:
                                    </div>
                                    <div class="col-7">
                                        <span id="paidAmount" class=" p-2">Not Paid</span>
                                    </div>
                                </div>

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
                                            $(this).attr('disabled', true);

                                            // Show the payment container and enable the "Pay" button
                                            $('#paymentContainer').show();

                                            // Send the generated control number to the database
                                            $.ajax({
                                                url: '{{ route("update.control.number") }}', // Replace with your actual route for updating control number
                                                type: 'POST',
                                                data: {
                                                    _token: '{{ csrf_token() }}',
                                                    user_id: '{{ $user->id }}', // Pass the user's ID
                                                    control_number: controlNumber // Send the generated control number
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
                                                url: '{{ route("update.payment.status") }}', // Replace with your actual route
                                                type: 'POST',
                                                data: {
                                                    _token: '{{ csrf_token() }}',
                                                    user_id: '{{ $user->id }}', // Pass the user's ID
                                                    payment_status: blockPrice // This could be 1 or the amount depending on your schema
                                                },
                                                success: function(response) {
                                                    $('#gd-result').removeClass('gd-close text-danger').addClass('gd-check text-success');
                                                    console.log('Payment status updated successfully:', response);
                                                },
                                                error: function(xhr, status, error) {
                                                    console.error('Error updating payment status:', error);
                                                }
                                            });
                                        });

                                        function generateControlNumber(price) {
                                            var base = Math.floor(Math.random() * 100000) + 10000; // 10-digit base number
                                            var checkDigit = base % 97; // Simple modulus operation for check digit
                                            return '99' + base.toString() + checkDigit.toString().padStart(2, '0') + price;
                                        }

                                        function formatNumber(number) {
                                            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                        }
                                    });
                                </script>






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
                            <div class="col-5 font-weight-bold text-muted">
                                Control Number:
                            </div>
                            <div class="col-7">
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
                            <div class="col-5 font-weight-bold text-muted">
                                Paid Amount:
                            </div>
                            <div class="col-7">
                                <span id="paidAmount" class=" p-2">Not Paid</span>
                            </div>
                        </div>
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
                                    $(this).attr('disabled', true);

                                    // Show the payment container and enable the "Pay" button
                                    $('#paymentContainer').show();

                                    // Send the generated control number to the database
                                    $.ajax({
                                        url: '{{ route("update.control.number") }}', // Replace with your actual route for updating control number
                                        type: 'POST',
                                        data: {
                                            _token: '{{ csrf_token() }}',
                                            user_id: '{{ $user->id }}', // Pass the user's ID
                                            control_number: controlNumber // Send the generated control number
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
                                        url: '{{ route("update.payment.status") }}', // Replace with your actual route
                                        type: 'POST',
                                        data: {
                                            _token: '{{ csrf_token() }}',
                                            user_id: '{{ $user->id }}', // Pass the user's ID
                                            payment_status: blockPrice // This could be 1 or the amount depending on your schema
                                        },
                                        success: function(response) {
                                            $('#gd-result').removeClass('gd-close text-danger').addClass('gd-check text-success');
                                            console.log('Payment status updated successfully:', response);
                                        },
                                        error: function(xhr, status, error) {
                                            console.error('Error updating payment status:', error);
                                        }
                                    });
                                });

                                function generateControlNumber(price) {
                                    var base = Math.floor(Math.random() * 100000) + 10000; // 10-digit base number
                                    var checkDigit = base % 97; // Simple modulus operation for check digit
                                    return '99' + base.toString() + checkDigit.toString().padStart(2, '0') + price;
                                }

                                function formatNumber(number) {
                                    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                }
                            });
                        </script>

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

</div>
