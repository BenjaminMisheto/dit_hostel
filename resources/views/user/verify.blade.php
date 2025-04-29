<div class="content">
    <div class="py-4 px-3 px-md-4">
        <div class="mb-3 mb-md-4 d-flex justify-content-between">
            <h3 class="mb-0">My Profile</h3>
            <p>{{ auth()->user()->semester->name ?? 'No semester found' }}</p>
        </div>

        <!-- Message Asking for Registration Number Verification -->
        <div class="alert alert-info mb-4">
            Please confirm if the registration number <strong>{{ $user->registration_number }}</strong> belongs to you.
        </div>

        <!-- OTP Input Section -->
        <div class="col-md-12 mb-3">
            <label class="fw-bold">
                Enter OTP: sent to your email
                {{ substr($user->email, 0, 1) . '***********' . substr($user->email, strpos($user->email, '@')) }}
            </label>
            <input type="text" id="otpInput" class="form-control" placeholder="Enter OTP sent to your email">
        </div>

        <!-- Submit Button -->
        <div class="col-md-12 text-center">
            <button type="submit" id="submitOtpButton" class="btn btn-outline-primary">Submit OTP</button>
        </div>
    </div>
</div>


<script>
$(document).ready(function() {
    $('#submitOtpButton').on('click', function() {
        var enteredOtp = $('#otpInput').val();

        // Validate OTP format (ensure the OTP is not empty)
        if (enteredOtp.trim() === '') {
            alert('Please enter the OTP.');
            return;
        }

        // Send OTP to the server for verification
        $.ajax({
            url: '{{ route('verify.otp') }}', // Your route to verify OTP
            type: 'POST',
            data: {
                otp: enteredOtp,
                _token: '{{ csrf_token() }}' // CSRF token for Laravel security
            },
            success: function(response) {
                alert(response.message); // OTP verified successfully
                // Redirect to the next page or perform any other actions
            },
            error: function(xhr) {
                alert(xhr.responseJSON.message); // Show error message if OTP is incorrect
            }
        });
    });
});
</script>
