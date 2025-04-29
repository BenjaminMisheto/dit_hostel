<div class="content">
    <div class="py-4 px-3 px-md-4">
        <div class="mb-3 mb-md-4 d-flex justify-content-between align-items-center">
            <h3 class="mb-0">History</h3>
        </div>

        @if ($adminCheckouts->isEmpty())
            <div class="container d-flex align-items-center justify-content-center" style="height: 70vh;">
                <div style="width: 18rem;">
                    <div class="card-body text-center">
                        <i class="gd-alert text-danger" style="font-size: 3rem;"></i><br>
                        <small class="card-title">No history available for this student.</small>
                    </div>
                </div>
            </div>
        @else
        @foreach ($adminCheckouts as $semesterId => $checkouts)
        <p class="mt-4 text-center">{{ $checkouts->first()->semester->name }}</p>
        <p class="text-center">{{ auth()->user()->name }}</p>

        @foreach ($checkouts->groupBy('block_name') as $blockName => $blockItems)
            @foreach ($blockItems->groupBy('floor_name') as $floorName => $floorItems)
                @foreach ($floorItems->groupBy('room_name') as $roomName => $roomItems)
                    @foreach ($roomItems->groupBy('bed_name') as $bedName => $bedItems)
                        <!-- Block, Floor, Room, Bed Information (on the same line) -->
                        <div class="row mb-3">
                            <div class="col">
                                <strong>{{ $blockName }}</strong>
                            </div>
                            <div class="col">
                                <strong>Floor:</strong> {{ $floorName }}
                            </div>
                            <div class="col">
                                <strong>Room:</strong> {{ $roomName }}
                            </div>
                            <div class="col">
                                <strong>Bed:</strong> {{ $bedName }}
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Items</th>
                                        <th scope="col">Condition</th>
                                        <th scope="col">Payment</th>
                                        <th scope="col">Amount (TZS)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $hasPendingPayment = false;
                                        $totalAmount = 0;
                                    @endphp
                                   @foreach ($bedItems as $checkout)
                                   @if ($checkout->condition === 'Bad' || $checkout->condition === 'None')
                                       @php
                                           $hasPendingPayment = true;
                                           $totalAmount += $checkout->payment_price ?? 0;
                                       @endphp
                                   @endif
                                   <tr>
                                       <td>{{ $loop->iteration }}</td>
                                       <td>{{ $checkout->name }}</td>
                                       <td class="
                                           @if($checkout->condition === 'None') text-warning
                                           @elseif($checkout->condition === 'Bad') text-danger
                                           @elseif($checkout->condition === 'Good') text-success
                                           @endif">
                                           {{ $checkout->condition }}
                                       </td>
                                       <td class="
                                           @if($checkout->condition === 'Bad' || $checkout->condition === 'None') text-danger
                                           @elseif($checkout->condition === 'Good') text-success
                                           @endif">
                                           @if($checkout->condition === 'Bad' || $checkout->condition === 'None')
                                               @if($checkout->paid)
                                                   <span class="text-success">Paid</span>
                                               @else
                                                   Not Paid
                                               @endif
                                           @elseif($checkout->condition === 'Good')
                                               Good
                                           @endif
                                       </td>
                                       <td class="text-end fw-bold">
                                           @if($checkout->condition === 'Bad' || $checkout->condition === 'None')
                                               TZS {{ number_format($checkout->payment_price ?? 0, 2) }}
                                           @else
                                               -
                                           @endif
                                       </td>
                                   </tr>
                                   @endforeach

                                </tbody>
                            </table>
                        </div>

                        <!-- Control Number Display or Generate -->
                        @if ($hasPendingPayment)
                            @php
                                $controlNumber = $bedItems->first()->control_number;
                            @endphp

                            @if ($controlNumber)
                                <!-- Display existing control number -->
                                <div class="row ">
                                    <div class="col-md-3">
                                        <p><strong>Control Number:</strong> {{ $controlNumber }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p><strong>Total Amount:</strong> TZS {{ number_format($totalAmount, 2) }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p><strong>Status:</strong>
                                            <span class="paymentStatus{{ $loop->parent->iteration }}">
                                                @if ($checkout->paid)
                                                    <span class="text-success">Paid</span>
                                                @else
                                                    <span class="text-warning">Pending Payment</span>
                                                @endif
                                            </span>
                                        </p>
                                    </div>

                                    <div class="col-md-3">
                                        <p><strong>Payment Date:</strong>
                                            <span class="Payment Date:">
                                                @if ($checkout->payment_date)
                                                    <span class="text-dark">{{$checkout->payment_date}}</span>
                                                @else
                                                    <span class="text-dark">null</span>
                                                @endif
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Pay Button -->
                                <div class="row text-center mt-3">
                                    <div class="col">
                                        @if($checkout->paid !== 1)
                                            <button class="payButton{{ $loop->parent->iteration }} btn btn-success" data-total-amount="{{ $totalAmount }}" data-control-number="{{ $controlNumber }}" data-checkout-id="{{ $checkout->id }}">Pay Now TZS {{ number_format($totalAmount, 2) }}</button>
                                        @endif
                                    </div>
                                </div>

                                <script>

                                    $(document).ready(function() {
                                        $('.payButton{{ $loop->parent->iteration }}').on('click', function() {
                                            $('#overlay').css('display', 'flex'); // Show overlay
                                            const totalAmount = $(this).data('total-amount');
                                            const controlNumber = $(this).data('control-number');
                                            const userId = '{{ auth()->user()->id }}';
                                            const semesterId = '{{ $semesterId }}';

                                            $(this).closest('.row').find('.paymentStatus{{ $loop->parent->iteration }}').text('Paid');
                                            $(this).hide();

                                            $.ajax({
                                                url: '{{ route('payCheckout') }}',
                                                method: 'POST',
                                                data: {
                                                    user_id: userId,
                                                    control_number: controlNumber,
                                                    semester_id: semesterId,
                                                    _token: $('meta[name="csrf-token"]').attr('content')
                                                },
                                                success: function(response) {

                                                        $('#overlay').fadeOut(); // Hide overlay after AJAX call

                                                    if (response.success) {
                                                        showToast('#success-toast', 'Payment successfully processed.');
                                                         // Ensure historyFunction() runs only once

                                                    } else {
                                                        showToast('#error-toast', 'Payment error: ' + response.message);
                                                    }
                                                },
                                                error: function() {
                                                    $('#overlay').fadeOut(); // Hide overlay after AJAX call

                                                    showToast('#error-toast', 'An error occurred.');
                                                }
                                            });
                                        });
                                    });
                                </script>

                            @else
                                <!-- Generate Control Number Button -->
                                <div class="row justify-content-center">
                                    <div class="col-auto">
                                        <button class="generateControlNumberBtn{{ $loop->parent->iteration }} btn btn-primary" data-semester-id="{{ $semesterId }}" data-user-id="{{ auth()->user()->id }}">Generate Control Number</button>
                                    </div>
                                </div>
                                <div class="text-center mt-3 generatedControlNumber{{ $loop->parent->iteration }}" style="display:none;">
                                    <p><strong>Generated Control Number:</strong> <span class="controlNumberText{{ $loop->parent->iteration }}"></span></p>
                                </div>

                                <script>

                                    $(document).ready(function() {
                                        $('.generateControlNumberBtn{{ $loop->parent->iteration }}').on('click', function() {
                                            $('#overlay').css('display', 'flex'); // Show overlay
                                            const $btn = $(this);
                                            $btn.prop('disabled', true);

                                            const semesterId = $(this).data('semester-id');
                                            const userId = $(this).data('user-id');

                                            const controlNumber = 'CN' + Math.floor(Math.random() * 1000000).toString().padStart(6, '0');
                                            $('.generatedControlNumber{{ $loop->parent->iteration }}').show();
                                            $('.controlNumberText{{ $loop->parent->iteration }}').text(controlNumber);
                                            $btn.hide();

                                            $.ajax({
                                                url: "{{ route('saveControlNumber') }}",
                                                method: 'POST',
                                                data: {
                                                    control_number: controlNumber,
                                                    semester_id: semesterId,
                                                    user_id: userId,
                                                    _token: $('meta[name="csrf-token"]').attr('content')
                                                },
                                                success: function(response) {
                                                    $('#overlay').fadeOut(); // Hide overlay after AJAX call

                                                    if (response.success) {
                                                        showToast('#success-toast', response.message);

                                                    } else {
                                                        showToast('#error-toast', response.message);
                                                    }
                                                },
                                                error: function() {
                                                    $('#overlay').fadeOut(); // Hide overlay after AJAX call

                                                    showToast('#error-toast', "Error saving control number.");
                                                },
                                                complete: function() {
                                                    $btn.prop('disabled', false);
                                                }
                                            });
                                        });
                                    });
                                </script>
                            @endif
                        @endif
                    @endforeach
                @endforeach
            @endforeach
        @endforeach
    @endforeach

        @endif
    </div>
</div>

<script>

    // Function to show toast notifications
    function showToast(toastId, message) {
        var $toast = $(toastId);
        $toast.find('.toast-body').text(message); // Set the message in the toast body
        $toast.toast({ delay: 3000 }); // Toast delay of 3 seconds
        $toast.toast('show'); // Display the toast



    }
</script>
