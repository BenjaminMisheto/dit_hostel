<div class="content">
    <div class="py-4 px-3 px-md-4">
        <div class="d-flex justify-content-between mb-4">
            <div class="h3 mb-0 me-auto">Check-Out Confirmation</div> <!-- Left aligned -->

            <button class="btn btn-outline-secondary mx-auto" onclick="checkout()">
                <!-- Center aligned -->
                <i class="gd-shift-left"></i>
            </button>

            <button class="btn btn-outline-secondary ms-auto" onclick="checkoutAction({{ $user->bed->id }})">
                <!-- Right aligned -->
                <i class="gd-loop"></i>
            </button>
        </div>

        <div class="row">
            <!-- Student Profile Section -->
            <div class="col-md-6">
                <div id="profileContent" class="profile-card mt-4 mx-auto p-4 rounded">
                    <div class="row align-items-center">
                        <div class="col-md-12 text-center mb-4">
                            <img id="profileImage" class="profile-image img-fluid rounded-circle border border-light"
                                src="{{ $user->profile_photo_path ?? 'img/placeholder.jpg' }}" alt="Profile Image"
                                style="max-width: 220px; height: auto;">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Name:</label>
                            <input type="text" id="profileName" class="form-control" disabled value="{{ $user->name }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Registration Number:</label>
                            <input type="text" id="profileRegNum" class="form-control" disabled
                                value="{{ $user->registration_number }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Sponsorship:</label>
                            <input type="text" id="profileSponsorship" class="form-control" disabled value="{{ $user->sponsorship }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Phone:</label>
                            <input type="text" id="profilePhone" class="form-control" disabled value="{{ $user->phone }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="fw-bold">Gender:</label>
                            <input type="text" id="profileGender" class="form-control" disabled value="{{ $user->gender }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="fw-bold">Nationality:</label>
                            <input type="text" id="profileNationality" class="form-control" disabled value="{{ $user->nationality }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="fw-bold">Course:</label>
                            <input type="text" id="profileCourse" class="form-control" disabled value="{{ $user->course }}">
                        </div>


                    </div>
                </div>
            </div>

<!-- Check-Out Items Section -->
<div class="col-12 col-md-6">
    <div class="card p-3 p-md-4">
        <h5 class="card-title mb-3">Check-Out Items {{session('semester')}}</h5>
        <input type="hidden" id="user_id" value="{{ $user->id }}">

        <p class="mb-4">Please verify that the student has returned the following items</p>


<!-- Checklist for returning items -->
<div class="row">
    @foreach ($confirmationItems as $index => $item)
        <div class="col-12 col-sm-6 mb-3">
            <input class="form-check-input item-checkbox" type="checkbox" id="checkbox-{{ $index }}" {{ $item['condition'] ? 'checked' : '' }} hidden disabled>
            <label class="btn btn-outline-default d-flex align-items-center w-100" for="checkbox-{{ $index }}">
                <i class="bi bi-check-circle me-2"></i> {{ $item['name'] }}
            </label>

            <!-- Condition radio buttons -->
            <div class="condition-radio mt-2" id="condition-{{ $index }}" style="display: {{ $item['condition'] ? 'block' : 'none' }};">
                <p class="fw-bold">Condition of {{ $item['name'] }}:</p>
                <div class="d-flex flex-wrap justify-content-between">
                    <div class="d-flex align-items-center me-2">
                        <input class="form-check-input me-1" type="radio" name="condition-{{ $index }}" value="Good" id="good-{{ $index }}" {{ $item['condition'] === 'Good' ? 'checked' : '' }} @if ($checkoutCount > 0) disabled @endif hidden >
                        <label class="btn btn-outline-success cursor-pointer" for="good-{{ $index }}" @if ($checkoutCount > 0) style="pointer-events: none!important;" @endif>Good</label>
                    </div>
                    <div class="d-flex align-items-center me-2">
                        <input class="form-check-input me-1" type="radio" name="condition-{{ $index }}" value="None" id="none-{{ $index }}" {{ $item['condition'] === 'None' ? 'checked' : '' }} hidden @if ($checkoutCount > 0) disabled @endif>
                        <label class="btn btn-outline-warning cursor-pointer" for="none-{{ $index }}" @if ($checkoutCount > 0) style="pointer-events: none; " @endif>None</label>
                    </div>
                    <div class="d-flex align-items-center">
                        <input class="form-check-input me-1" type="radio" name="condition-{{ $index }}" value="Bad" id="bad-{{ $index }}" {{ $item['condition'] === 'Bad' ? 'checked' : '' }} hidden @if ($checkoutCount > 0) disabled @endif>
                        <label class="btn btn-outline-danger cursor-pointer" for="bad-{{ $index }}" @if ($checkoutCount > 0) style="pointer-events: none;" @endif>Bad</label>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>


        <!-- Conditional warning message and buttons -->
        @if ($checkoutCount === 0)
            <div id="warningMessage" class="alert alert-danger mb-3">
                <i class="bi bi-exclamation-triangle me-2"></i> Please review the condition of all items above carefully. Once submitted, this action is irreversible and changes cannot be made.
            </div>

            <!-- Submit button -->
            <div class="d-grid text-center">
                <button id="submitButton" class="btn btn-outline-secondary" data-toggle="modal" data-target="#Checkout_Confirmation">Submit Check-Out</button>
            </div>
        @else
            <div id="warningMessage" class="alert alert-success mb-3">
                <i class="bi bi-check-circle me-2"></i> The student has successfully completed the check-out process. You can generate a detailed report on the report page for further information.
            </div>
        @endif
    </div>
</div>


<script>
    $(document).ready(function() {
        const submitButton = $('#submitButton');
        const warningMessage = $('#warningMessage');

        // Function to validate checked items and conditions
        function toggleSubmitButton() {
            let valid = true;

            $('.item-checkbox').each(function() {
                const checkboxId = $(this).attr('id');
                const index = checkboxId.split('-')[1];
                const conditionDiv = $('#condition-' + index);

                if ($(this).is(':checked')) {
                    const selectedCondition = conditionDiv.find('input[type="radio"]:checked');
                    if (selectedCondition.length === 0) {
                        valid = false;
                    }
                }
            });

            if (valid) {
                warningMessage.hide();
                submitButton.prop('disabled', false); // Enable submit button
            } else {
                warningMessage.show();
                submitButton.prop('disabled', true); // Disable submit button
            }
        }

        // Function to handle form submission via AJAX
        window.submitCheckout = function() {
            const items = [];
            let validationPassed = true;

            // Collect selected items and their conditions
            $('.item-checkbox:checked').each(function() {
                const checkboxId = $(this).attr('id');
                const index = checkboxId.split('-')[1];
                const conditionDiv = $('#condition-' + index);
                const condition = conditionDiv.find('input[type="radio"]:checked').val() || 'None'; // Default to 'None' if no condition selected

                if (!condition) {
                    validationPassed = false;
                }

                items.push({
                    name: $(this).siblings('label').text().trim(),
                    condition: condition
                });
            });

            // Validate if all checked items have a condition selected
            if (!validationPassed) {
                showToast('#error-toast', 'Please select a condition for all checked items.');
                return;
            }

            // Check if at least one checkbox is checked
            const anyChecked = $('.item-checkbox:checked').length > 0;

            if (!anyChecked) {
                showToast('#error-toast', 'Please select at least one item and its condition.');
                return;
            }

            // Hide warning message and show overlay
            warningMessage.hide();
            $('#overlay').css('display', 'flex');

            $.ajax({
                url: '{{ route('admin.checkout.student') }}',
                method: 'POST',
                data: {
                    user_id: $('#user_id').val(),

                    items: items,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {


                    $('#overlay').fadeOut();
                    console.log('AJAX success response:', response); // Log the entire response

                    if (response.success) {
                        hidemodal()
                        showToast('#success-toast', 'Check-out successfully submitted!');
                    } else {
                        console.log('Error message from server:', response.message); // Log error message
                        showToast('#error-toast', response.message || 'An error occurred.');
                    }

                },
                error: function(xhr) {
                    $('#overlay').fadeOut();
                    console.log('AJAX error response:', xhr); // Log the entire error response

                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        let errorMessages = '';

                        $.each(errors, function(key, value) {
                            errorMessages += value + '<br>';
                            console.log(`Validation error on ${key}: ${value}`); // Log each validation error
                        });

                        showToast('#error-toast', errorMessages);
                    } else {
                        console.log('Unexpected error:', xhr.responseJSON); // Log unexpected error details
                        showToast('#error-toast', 'An error occurred: ' + xhr.responseJSON.message);
                    }
                }
            });

                // Function to close the modal and call the hostel() function
        function hidemodal() {
            $('#Checkout_Confirmation').modal('hide'); // Close the modal

            // Ensure hostel() is called after modal is closed
            $('#Checkout_Confirmation').on('hidden.bs.modal', function() {
                checkoutAction({{ $user->bed->id }})
            });
        }
        }

        // Function to display toast messages
        function showToast(toastId, message) {
            var $toast = $(toastId);
            $toast.find('.toast-body').html(message);
            $toast.toast({ delay: 3000 });
            $toast.toast('show');
        }
    });
</script>

<script>
    function checkoutAction(bedId) {
    // Deactivate all navigation links
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
    ];

    selectors.forEach(function(selector) {
        $(selector).removeClass("active");
    });
    $("#nav_checkout").addClass("active"); // Set checkout as active

    // Show loading spinner
    $("#dash").html(
        '<div class="spinner-container">' +
        '<div class="black show d-flex align-items-center justify-content-center">' +
        '<div class="spinner-border lik" style="width: 3rem; height: 3rem;" role="status">' +
        '<span class="sr-only">Loading...</span>' +
        '</div>' +
        '</div>' +
        '</div>'
    );

    // Define the checkout URL dynamically
    let url = `{{ url('bed/checkout') }}/${bedId}`;

    // Load the checkout page for the bed ID
    $("#dash").load(url, (response, status, xhr) => {
        if (status === "error") {
            const msg = `Sorry, but there was an error: ${xhr.status} ${xhr.statusText}`;
            $("#error").html(msg); // Display error message
        }
    });
}

</script>
</div>
</div>
</div>

<div id="Checkout_Confirmation" class="modal fade" role="dialog" aria-labelledby="Re-applyModalLabel" aria-hidden="true">
    <div class="modal-dialog rounded" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="text-center rounded">
                    <i class="gd-alert icon-text icon-text-xxl d-block text-danger mb-3 mb-md-4"></i>
                    <div class="h5 font-weight-semi-bold mb-2">Are you sure ?</div>
                    <p>Any errors may result in the student being charged if the returned item is not carefully confirmed.</p>

                    <div class="d-flex justify-content-between mb-4">
                        <a class="btn btn-outline-success" href="#" onclick="submitCheckout()">Yes, Confirm</a>
                        <a class="btn btn-outline-danger" href="#" data-dismiss="modal">No</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
