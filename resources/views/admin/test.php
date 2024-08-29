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


<!-- Number of Days Input -->
<div class="form-group row align-items-center mb-3">
    <label class="col-sm-8 col-form-label">
        Number of days students have to make a payment before their hostel application expires
    </label>

    <div class="col-sm-4 text-right">
        <select id="daysSelect" class="form-control">
            <option value="1">1 Day</option>
            <option value="2">2 Days</option>
            <option value="3">3 Days</option>
            <option value="4">4 Days</option>
            <option value="5">5 Days</option>
        </select>
    </div>
</div>


        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
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
            var selectedDays = parseInt($(this).val(), 10); // Get selected days and ensure it's an integer
            var millisecondsPerDay = 24 * 60 * 60 * 1000; // Milliseconds in one day
            var expirationMilliseconds = selectedDays * millisecondsPerDay; // Calculate total milliseconds

            $.ajax({
                url: '{{ route("admin.updateExpirationDate") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    expiration_date: expirationMilliseconds // Send the milliseconds to the server
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
            $toast.toast({ delay: 3000 }); // Set the delay for the toast to hide automatically
            $toast.toast('show');
        }
    });
</script>
