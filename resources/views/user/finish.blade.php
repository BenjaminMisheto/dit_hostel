@php
use Carbon\Carbon;
$expirationDate = Carbon::parse($user->expiration_date);
@endphp
<div class="content">
    <div class="py-4 px-3 px-md-4">
        <div class="mb-3 mb-md-4 d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Confirm your Application</h3>
        </div>

        @php
            $isEmpty = empty($user->block_id) || empty($user->room_id) || empty($user->floor_id) ;
        @endphp

        @if ($isEmpty)
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Important!</strong> Please ensure that your application is confirmed before proceeding.
            </div>
            <script>
                $('#gd-hostel,#gd-finish,#gd-result').removeClass('gd-check text-success').addClass(' gd-close text-danger');
        </script>
        @else
        <div class="row g-3">
            <div class="col-md-12 col-xl-12">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="font-weight-semi-bold text-uppercase mb-0">Information</h5>
                    </div>
                    <div class="card-body pt-0">

                        <!-- Alert for Important Notice -->
                        <div class="alert alert-warning mb-4">
                            <strong>Important Notice:</strong>
                            <p class="mb-0">Before confirming your application, please verify all details carefully. Once confirmed, you will not be able to edit your application. If you wish to choose another hostel bed, feel free to select from the available options.</p>
                            <p class="mb-0">Please note that the institution reserves the right to reassign your room if necessary. We appreciate your understanding.</p>
                        </div>



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

                            </div>
                        </div>

                        <!-- Alerts Based on Application Status -->
                        @if ($expirationDate->isPast() && empty($user->payment_status) && !empty($user->bed))
                            <div class="alert alert-danger mb-3" role="alert">
                                <strong>We regret to inform you, {{$user->name}}.</strong> Your application has expired. If you wish to reapply, please click "Reapply" on the results page.
                            </div>
                        @else
                            @if ($user->application == 1)
                                <div class="alert alert-success mb-3" role="alert">
                                    <strong>Congratulations, {{$user->name}}!</strong> Your application has been successfully submitted. Please check the results page regularly to stay updated on the status of your application.
                                </div>
                            @else
                                <form id="confirmApplicationForm" class="mb-3">
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-outline-info" id="confirmButton">Confirm Your Application</button>
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
</div>

<script>
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
                    showToast('success-toast', 'Your application has been confirmed successfully.');
                    result();
                },
                error: function(xhr) {
                    $('#overlay').fadeOut();
                    showToast('error-toast', 'An error occurred while confirming your application. Please try again.');
                    console.error('AJAX error response:', xhr.responseText);
                }
            });
        });

        function showToast(toastId, message) {
            var toastElement = $('#' + toastId);
            toastElement.find('.toast-body').text(message);
            toastElement.toast({ delay: 3000 });
            toastElement.toast('show');
        }
    });
</script>
