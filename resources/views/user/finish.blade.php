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
                <div class="col-md-6 col-xl-4">
                    <!-- Profile Image Card -->
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="h6 font-weight-semi-bold text-uppercase mb-0">Image</h5>
                        </div>
                        <div class="card-body d-flex align-items-center justify-content-center p-3">
                            <img src="{{ $user->profile_photo_path ?? 'img/placeholder.jpg' }}" class="img-fluid rounded w-100" alt="Profile Image">
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-8">
                    <!-- User Information Card -->
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="h6 font-weight-semi-bold text-uppercase mb-0">Information</h5>
                        </div>
                        <div class="card-body pt-0">
                            <div class="alert alert-warning">
                                <strong>Important Notice:</strong>
                                <p class="mb-0">Before confirming your application, please verify all details carefully. Once confirmed, you will not be able to edit your application. If you wish to choose another hostel bed, feel free to select from the available options.</p>
                                <p class="mb-0">Please note that the institution reserves the right to reassign your room if necessary. We appreciate your understanding.</p>
                            </div>

                            <hr>

                            <div class="row mb-3">
                                <div class="col-4">
                                    <h6 class="lh-1 mb-1">Name</h6>
                                </div>
                                <div class="col-8">
                                    <h6 class="lh-1 mb-1">{{ $user->name }}</h6>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-4">
                                    <h6 class="lh-1 mb-1">Number</h6>
                                </div>
                                <div class="col-8">
                                    <h6 class="lh-1 mb-1">{{ $user->registration_number }}</h6>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-4">
                                    <h6 class="lh-1 mb-1">Hostel</h6>
                                </div>
                                <div class="col-8">
                                    <h6 class="lh-1 mb-1">{{ $user->block ? $user->block->name : 'Not Assigned' }}</h6>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-4">
                                    <h6 class="lh-1 mb-1">Room</h6>
                                </div>
                                <div class="col-8">
                                    <h6 class="lh-1 mb-1">{{ $user->room ? $user->room->room_number : 'Not Assigned' }}</h6>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-4">
                                    <h6 class="lh-1 mb-1">Bed</h6>
                                </div>
                                <div class="col-8">
                                    <h6 class="lh-1 mb-1">{{ $user->bed ? 'Bed ' . $user->bed->bed_number : 'Not Assigned' }}</h6>
                                </div>
                            </div>
                        </div>
                        @if ($expirationDate->isPast() and empty($user->payment_status) and !empty($user->bed))


                        <div class="alert alert-danger mb-3" role="alert">
                            <strong>We regret to inform you, {{$user->name}}.</strong> Your application has expired. If you wish to reapply, please click "Reapply" on the results page.
                        </div>

                        @else



                        @if ($user->application == 1)



                            <div class="alert alert-success  mb-3" role="alert">
                                <strong>Congratulations, {{$user->name}}!</strong> Your application has been successfully submitted. Please check the results page regularly to stay updated on the status of your application.
                            </div>


                        @else

                            <form id="confirmApplicationForm" class="mb-3">
                                <div class=" text-center">
                                    <button type="submit" class="btn btn-outline-info" id="confirmButton">Confirm your Application</button>
                                </div>
                            </form>

                        @endif
                        @endif
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
