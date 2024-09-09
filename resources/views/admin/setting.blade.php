<div class="content">
    <div class="py-4 px-3 px-md-4">
        <div class="mb-3 mb-md-4 d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Settings</h3>
        </div>

        <!-- Settings Container -->
        <div class="bg-white p-4">
            <!-- Turn off algorithm -->
            <div class="form-group row align-items-center mb-3">
                <label class="col-sm-8 col-form-label">Turn On/Off Algorithm</label>
                <div class="col-sm-4 text-right">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="algorithmSwitch"
                            data-setting="algorithm" {{ $settings->algorithm ? 'checked' : '' }}>
                        <label class="custom-control-label" for="algorithmSwitch"></label>
                    </div>
                </div>
            </div>
            <hr>

            <!-- Show reserved rooms to students -->
            <div class="form-group row align-items-center mb-3">
                <label class="col-sm-8 col-form-label">Show Reserved Rooms to Students</label>
                <div class="col-sm-4 text-right">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="reservedRoomsSwitch"
                            data-setting="reserved_bed" {{ $settings->reserved_bed ? 'checked' : '' }}>
                        <label class="custom-control-label" for="reservedRoomsSwitch"></label>
                    </div>
                </div>
            </div>
            <hr>

            <!-- Show maintenance beds to students -->
            <div class="form-group row align-items-center mb-3">
                <label class="col-sm-8 col-form-label">Show Maintenance Beds to Students</label>
                <div class="col-sm-4 text-right">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="maintenanceBedsSwitch"
                            data-setting="maintenance_bed" {{ $settings->maintenance_bed ? 'checked' : '' }}>
                        <label class="custom-control-label" for="maintenanceBedsSwitch"></label>
                    </div>
                </div>
            </div>
            <hr>

            <!-- Number of Days Input -->
            <div class="form-group row align-items-center mb-3">
                <label class="col-sm-10 col-form-label">
                    Number of days students have to make a payment before their hostel application expires
                </label>

                <div class="col-sm-2 text-right">
                    <select id="daysSelect" class="wide">
                        <option value="1" {{ $expirationDays == 1 ? 'selected' : '' }}>1 Day</option>
                        <option value="2" {{ $expirationDays == 2 ? 'selected' : '' }}>2 Days</option>
                        <option value="3" {{ $expirationDays == 3 ? 'selected' : '' }}>3 Days</option>
                        <option value="4" {{ $expirationDays == 4 ? 'selected' : '' }}>4 Days</option>
                        <option value="5" {{ $expirationDays == 5 ? 'selected' : '' }}>5 Days</option>
                        <option value="6" {{ $expirationDays == 6 ? 'selected' : '' }}>6 Day</option>
                        <option value="7" {{ $expirationDays == 7 ? 'selected' : '' }}>7 Days</option>
                        <option value="8" {{ $expirationDays == 8 ? 'selected' : '' }}>8 Days</option>
                        <option value="9" {{ $expirationDays == 9 ? 'selected' : '' }}>9 Days</option>
                        <option value="10" {{ $expirationDays == 10 ? 'selected' : '' }}>10 Days</option>
                    </select>
                </div>
            </div>
            <hr>

<!-- Open Date Input -->
<div class="form-group row align-items-center mb-3">
    <label for="openDate" class="col-sm-10 col-form-label">
        Open Date for Application
    </label>
    <div class="col-sm-2 text-right">
        <input type="date" id="openDate" class="form-control" value="{{ $openDate }}">
    </div>
</div>
<hr>
 <!-- Deadline Date Input -->
<div class="form-group row align-items-center mb-3">
    <label for="deadlineDate" class="col-sm-10 col-form-label">
        Deadline Date for Application Expiration
    </label>
    <div class="col-sm-2 text-right">
        <input type="date" id="deadlineDate" class="form-control" value="{{ $deadlineDate }}">
    </div>
</div>
<hr>



<!-- Report Date Input -->
<div class="form-group row align-items-center mb-3">
    <label for="reportDate" class="col-sm-10 col-form-label">
        Report Date
    </label>
    <div class="col-sm-2 text-right">
        <input type="date" id="reportDate" class="form-control" value="{{ $reportDate }}">
    </div>
</div>




        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('select').niceSelect();
        $('.custom-control-input').on('change', function() {
            var setting = $(this).data('setting');
            var status = $(this).is(':checked') ? 1 : 0; // Convert to 1 (true) or 0 (false)
            $.ajax({
                url: '{{ route("admin.updateSetting") }}',
                type: 'POST',
                data: {
                    setting: setting,
                    status: status, // Now this will be 1 or 0, which PHP interprets as true or false
                    _token: '{{ csrf_token() }}' // Ensure to include CSRF token
                },
                success: function(response) {
                    showToast('#success-toast', response.message);
                },
                error: function(xhr) {
                    var errorMessage = 'Failed to update setting: ';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage += xhr.responseJSON.message;
                    } else {
                        errorMessage += xhr.responseText;
                    }
                    showToast('#error-toast', errorMessage);
                }
            });
        });

        function showToast(toastId, message) {
            var $toast = $(toastId);
            $toast.find('.toast-body').text(message);
            $toast.toast({
                delay: 3000
            }); // Set the delay for the toast to hide automatically
            $toast.toast('show');
        }
    });
</script>

<script>
$(document).ready(function() {
    $('#daysSelect').on('change', function() {
        var selectedDays = parseInt($(this).val(), 10); // Get selected days as an integer

        $.ajax({
            url: '{{ route("admin.updateExpirationDate") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                days: selectedDays // Send the days as an integer
            },
            success: function(response) {
                showToast('#success-toast', response.message);
            },
            error: function(xhr) {
                var errorMessage = 'Failed to update expiration date: ';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage += xhr.responseJSON.message;
                } else {
                    errorMessage += xhr.responseText;
                }
                showToast('#error-toast', errorMessage);
            }
        });
    });

    function showToast(toastId, message) {
        var $toast = $(toastId);
        $toast.find('.toast-body').text(message);
        $toast.toast({
            delay: 3000
        }); // Set the delay for the toast to hide automatically
        $toast.toast('show');
    }
});

</script>


<script>
    $(document).ready(function() {
        const $reportDateInput = $('#reportDate');
        const $deadlineDateInput = $('#deadlineDate');  // Added deadline date
        const $openDateInput = $('#openDate');

        // Function to send AJAX request to update dates
        function updateDates() {
            const reportDate = new Date($reportDateInput.val());
            const deadlineDate = new Date($deadlineDateInput.val());  // Added deadline date
            const openDate = new Date($openDateInput.val());

            // Make sure the dates are not empty and open date is not greater than deadline date, and deadline date is not greater than report date
            if (reportDate && deadlineDate && openDate && openDate <= deadlineDate && deadlineDate <= reportDate) {
                $.ajax({
    url: 'update-dates',
    type: 'POST',
    contentType: 'application/json',
    data: JSON.stringify({
        report_date: $reportDateInput.val(),
        deadline: $deadlineDateInput.val(),  // Changed to 'deadline'
        open_date: $openDateInput.val()
    }),
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    success: function(data) {
        if (data.success) {
            showToast('success-toast', 'Dates updated successfully');
        } else {
            showToast('error-toast', 'Failed to update dates');
        }
    },
    error: function(jqXHR, textStatus, errorThrown) {
        showToast('error-toast', 'An error occurred. Please try again.');
        console.error("Error details:", jqXHR, textStatus, errorThrown);
    }
});

            } else if (openDate > deadlineDate) {
                // Show error toast if open date is greater than deadline date
                showToast('error-toast', 'Open date cannot be greater than deadline date.');
            } else if (deadlineDate > reportDate) {
                // Show error toast if deadline date is greater than report date
                showToast('error-toast', 'Deadline date cannot be greater than report date.');
            }
        }

        // Event listener for when the report date, deadline date, or open date is changed
        $reportDateInput.add($deadlineDateInput).add($openDateInput).on('change', updateDates);

        // Function to show toast notifications
        function showToast(toastId, message) {
            $('#' + toastId).find('.toast-body').text(message).end().toast('show');
        }
    });
    </script>
