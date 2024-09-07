@extends('layouts.app') @section('content')
<style>
    .search-container {
        position: relative;
        /* Positioning context for absolute positioning */
    }

    #searchResults {
        position: absolute;
        /* Position it absolutely */
        top: 100%;
        /* Position it just below the input field */
        left: 0;
        right: 0;
        max-height: 300px;
        overflow-y: auto;
        z-index: 1000;
        /* Ensure it's above other elements */
        border: 1px solid #ddd;
        background: #fff;
        display: none;
        /* Hide initially */
    }
</style>

<div class="content">
    <div class="py-4 px-3 px-md-4">
        <div class="mb-3 mb-md-4 d-flex justify-content-between">
            <h3 class="mb-0">My Profile</h3>
        </div>
        @if ($user->application == 1)
        <div class="">

            <div class="alert alert-success " role="alert">
                <strong>Congratulations, {{$user->name}}!</strong> Your application has been successfully submitted.
                Please check the results page regularly to stay updated on the status of your application.
            </div>

        </div>

        @endif

        @if ($user->confirmation == 0)
        <div class="profile-container">
            <div class="search-container">
                <form id="searchForm">
                    <div class="input-group mb-3">
                        <input type="text" id="searchInput" class="form-control" placeholder="Enter your ID or Name"
                            aria-label="Search ID">
                        <input type="hidden" name="csrf_token" value="{{ csrf_token() }}">
                        <div class="input-group-append">
                            <div id="spinner" class="spinner-border spinner-border-sm text-primary ml-2" role="status"
                                style="display: none;">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </form>
                <div id="searchResults" class="search-result">
                    <!-- Results will be populated here -->
                </div>
                <div id="alertBox" class="alert alert-success alert-box mt-4 d-none">Profile loaded successfully!</div>
                <div id="errorBox" class="alert alert-danger mt-4 d-none"></div>
            </div>
        </div>

        <div id="profileContent" class="profile-card mt-4 mx-auto p-4 rounded">
            <div class="row align-items-center">

                <div class="col-md-6">
                            <!-- Profile Image -->
                <div class="col-12 text-center mb-4">
                    <img id="profileImage" class="profile-image img-fluid rounded-circle  border-light mx-auto"
                         src="img/placeholder.jpg" alt="Profile Image" style="max-width: 220px; height: auto;">
                </div>
                <div class=" col-md-12 mb-3">
                    <label class="fw-bold">Name:</label>
                    <input type="text" id="profileName" class="form-control" disabled placeholder="John Doe">
                </div>

                <div class="col-md-12 mb-3">
                    <label class="fw-bold">Registration Number:</label>
                    <input type="text" id="profileRegNum" class="form-control" disabled placeholder="123456">
                </div>
                <div class="col-md-12 mb-3">
                    <label class="fw-bold">Sponsorship:</label>
                    <input type="text" id="profileSponsorship" class="form-control" disabled placeholder="Scholarship">
                </div>


                </div>



                <!-- User Information -->
                <div class="col-md-6">





                    <div class="col-md-12 mb-3">
                        <label class="fw-bold">Phone:</label>
                        <input type="text" id="profilePhone" class="form-control" disabled placeholder="123-456-7890">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="fw-bold">Gender:</label>
                        <input type="text" id="profileGender" class="form-control" disabled placeholder="Male">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="fw-bold">Nationality:</label>
                        <input type="text" id="profileNationality" class="form-control" disabled placeholder="American">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="fw-bold">Course:</label>
                        <input type="text" id="profileCourse" class="form-control" disabled placeholder="Computer Science">
                    </div>

                    <!-- Note Section -->
                    <div class="col-md-12 mb-3">
                        <div id="verifyBox" class="alert alert-warning ">
                            <strong>Warning!</strong> Please review the information below and confirm if it is correct before proceeding.
                        </div>
                    </div>

                    <!-- Confirm Button -->
                    <div class="text-center mt-4">
                        <button id="confirmButton" class="btn btn-outline-success px-4 py-2">Confirm Your Information</button>
                    </div>

                </div>

            </div>
        </div>

    </div>

    <script>
        $(document).ready(function() {
            const $searchInput = $('#searchInput');
            const $searchResults = $('#searchResults');
            const $profileContent = $('#profileContent');
            const $alertBox = $('#alertBox');
            const $errorBox = $('#errorBox');
            const $spinner = $('#spinner'); // Reference to the spinner
            const $verifyBox = $('#verifyBox'); // New warning box
            const csrfToken = $('input[name="csrf_token"]').val(); // Get the CSRF token from the hidden input
            let debounceTimer;
            // Hide the error box and warning box initially
            $errorBox.addClass('d-none');
            $verifyBox.addClass('d-none'); // Hide the verification warning box
            $searchResults.hide(); // Use .hide() instead of .addClass('d-none')
            function debounce(func, wait) {
                return function(...args) {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => func.apply(this, args), wait);
                };
            }

            function performSearch(query) {
                if (query.length < 5) { // Start searching after 5 characters
                    $searchResults.empty().hide();
                    return;
                }
                $spinner.show(); // Show the spinner
                // Fetch matching profiles
                $.ajax({
                    url: `/search`,
                    method: 'POST',
                    data: {
                        query: query, // Use "query" instead of "registrationNumber"
                        _token: csrfToken // Send CSRF token with the request
                    },
                    dataType: 'json',
                    success: function(data) {
                        $searchResults.empty().show(); // Show search results
                        $errorBox.addClass('d-none'); // Hide error box on successful search
                        $spinner.hide(); // Hide the spinner
                        if (data.results.length === 0) {
                            $searchResults.append(
                                '<div class="dropdown-item text-muted">No matches found</div>');
                            return;
                        }
                        data.results.forEach(function(result) {
                            const $item = $('<a>')
                                .addClass('dropdown-item')
                                .attr('href', '#')
                                .text(
                                    `${result.student_name} (ID: ${result.registration_number})`
                                    )
                                .on('click', function() {
                                    // Populate profile information in placeholders with animation
                                    const $fields = [{
                                            id: '#profileImage',
                                            value: result.image ||
                                                'img/placeholder.jpg',
                                            type: 'src'
                                        },
                                        {
                                            id: '#profileName',
                                            value: result.student_name,
                                            type: 'val'
                                        },
                                        {
                                            id: '#profileRegNum',
                                            value: result.registration_number,
                                            type: 'val'
                                        },
                                        {
                                            id: '#profileSponsorship',
                                            value: result.sponsorship,
                                            type: 'val'
                                        },
                                        {
                                            id: '#profilePhone',
                                            value: result.phone,
                                            type: 'val'
                                        },
                                        {
                                            id: '#profileGender',
                                            value: result.gender,
                                            type: 'val'
                                        },
                                        {
                                            id: '#profileNationality',
                                            value: result.nationality,
                                            type: 'val'
                                        },
                                        {
                                            id: '#profileCourse',
                                            value: result.course,
                                            type: 'val'
                                        },
                                        {
                                            id: '#profilePaymentStatus',
                                            value: result.school_fee,
                                            type: 'val'
                                        }
                                    ];
                                    $fields.forEach((field, index) => {
                                        setTimeout(() => {
                                            if (field.type === 'src') {
                                                $(field.id).fadeOut(200,
                                                    function() {
                                                        $(this)
                                                            .attr(
                                                                'src',
                                                                field
                                                                .value
                                                                )
                                                            .fadeIn(
                                                                200
                                                                );
                                                    });
                                            } else {
                                                $(field.id).fadeOut(200,
                                                    function() {
                                                        $(this).val(
                                                                field
                                                                .value
                                                                )
                                                            .fadeIn(
                                                                200
                                                                );
                                                    });
                                            }
                                        }, index *
                                        100); // Staggered fade-in effect
                                    });
                                    $searchResults.hide(); // Hide search results
                                    $profileContent
                                .show(); // Ensure profile content is shown
                                    $verifyBox.removeClass(
                                    'd-none'); // Show the verification warning box
                                    // Clear the search input
                                    $searchInput.val(result.registration_number);
                                });
                            $searchResults.append($item);
                        });
                    },
                    error: function(xhr) {
                        const errorMessage = xhr.responseJSON.message ||
                            'An error occurred while searching. Please try again.';
                        console.error('Error fetching data:', errorMessage);
                        $searchResults.hide(); // Hide search results on error
                        $errorBox.text(errorMessage).removeClass('d-none'); // Show error message
                        $spinner.hide(); // Hide the spinner
                    }
                });
            }
            // Attach the debounced search function to the input event
            $searchInput.on('input', debounce(function() {
                const query = $searchInput.val().trim();
                performSearch(query);
            }, 300)); // Adjust the debounce delay (e.g., 300 ms) as needed
            $(document).on('click', function(event) {
                if (!$searchInput.is(event.target) && !$searchResults.is(event.target) && $searchResults
                    .has(event.target).length === 0) {
                    $searchResults.hide(); // Hide search results if clicking outside
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            const $confirmButton = $('#confirmButton');
            const csrfToken = $('input[name="csrf_token"]').val(); // Get the CSRF token from the hidden input
            function showToast(toastId, message) {
                var toastElement = $('#' + toastId);
                toastElement.find('.toast-body').html(message);
                toastElement.toast('show');
            }
            // Function to validate the form fields
            function validateForm() {
                let isValid = true;
                const errorMessages = [];
                // Clear previous error messages
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').remove();
                // Retrieve field values and ensure they are strings
                const name = ($('#profileName').val() || '').trim();
                const registrationNumber = ($('#profileRegNum').val() || '').trim();
                const phone = ($('#profilePhone').val() || '').trim();
                if (name === '') {
                    //   $('#profileName').addClass('is-invalid');
                    errorMessages.push('Name is required.');
                    isValid = false;
                }
                if (registrationNumber === '' || isNaN(registrationNumber)) {
                    //    $('#profileRegNum').addClass('is-invalid');
                    errorMessages.push('Valid registration number is required.');
                    isValid = false;
                }
                if (phone === '') {
                    //  $('#profilePhone').addClass('is-invalid');
                    errorMessages.push('Phone number is required.');
                    isValid = false;
                }
                // Show specific error message if validation fails
                if (!isValid) {
                    showToast('error-toast',
                        'Please ensure your name or ID number has been searched  before proceeding.');
                    $('#confirmButton').prop('disabled', false);
                    $('#overlay').fadeOut();
                    return false; // Return false to prevent form submission
                }
                return true;
            }
            $confirmButton.on('click', function() {
                if (!validateForm()) {
                    return; // Exit if validation fails
                }
                $('#confirmButton').prop('disabled', true);
                $('#overlay').css('display', 'flex');
                const userData = {
                    profile_photo_path: ($('#profileImage').attr('src') || ''),
                    name: ($('#profileName').val() || '').trim(),
                    registration_number: ($('#profileRegNum').val() || '').trim(),
                    sponsorship: ($('#profileSponsorship').val() || '').trim(),
                    phone: ($('#profilePhone').val() || '').trim(),
                    gender: ($('#profileGender').val() || '').trim(),
                    nationality: ($('#profileNationality').val() || '').trim(),
                    course: ($('#profileCourse').val() || '').trim(),
                    _token: csrfToken // Send CSRF token with the request
                };
                $.ajax({
                    url: `/update-profile`, // Update this to match your route
                    method: 'POST',
                    data: userData,
                    dataType: 'json',
                    success: function(response) {
                        $('#confirmButton').prop('disabled', false);
                        $('#overlay').fadeOut();
                        $('#gd-close').removeClass('gd-close text-danger').addClass(
                            'gd-check text-success');
                        if (response.success) {
                            profile();
                            // Show success toast
                            showToast('success-toast', 'Profile updated successfully!');
                        } else {
                            // Show error toast
                            showToast('error-toast', response.message ||
                                'An error occurred while updating the profile.');
                        }
                    },
                    error: function(xhr) {
                        $('#confirmButton').prop('disabled', false);
                        $('#overlay').fadeOut();
                        const errorMessage = xhr.responseJSON.message ||
                            'An error occurred while updating the profile. Please try again.';
                        // Show error toast
                        showToast('error-toast', errorMessage);
                    }
                });
            });
        });
    </script>

    @else
    <div class="profile-container">
        <div class="search-container">
            <form id="searchForm">
                <div class="input-group mb-3">
                    <input type="text" id="searchInput" class="form-control" placeholder="Enter your ID or Name"
                        aria-label="Search ID" disabled>
                    <input type="hidden" name="csrf_token" value="{{ csrf_token() }}" disabled>
                    <div class="input-group-append">
                        <div id="spinner" class="spinner-border spinner-border-sm text-primary ml-2" role="status"
                            style="display: none;">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </form>
            <div id="searchResults" class="search-result">
                <!-- Results will be populated here -->
            </div>
            <div id="alertBox" class="alert alert-success alert-box mt-4 d-none">Profile loaded successfully!</div>
            <div id="errorBox" class="alert alert-danger mt-4 d-none"></div>
        </div>
    </div>

    <div id="profileContent" class="profile-card mt-4 mx-auto p-4 rounded">

<div class="row align-items-center">

    <div class="col-md-6">
        <!-- Profile Image -->
        <div class="col-12 text-center mb-4">
            <img id="profileImage" class="profile-image img-fluid rounded-circle border border-light"
                src="{{ $user->profile_photo_path ?? 'img/placeholder.jpg' }}" alt="Profile Image"
                style="max-width: 220px; height: auto;">
        </div>

        <!-- User Information -->
        <div class="col-md-12 mb-3">
            <label class="fw-bold">Name:</label>
            <input type="text" id="profileName" class="form-control" disabled value="{{ $user->name }}">
        </div>

        <div class="col-md-12 mb-3">
            <label class="fw-bold">Registration Number:</label>
            <input type="text" id="profileRegNum" class="form-control" disabled
                value="{{ $user->registration_number }}">
        </div>
        <div class="col-md-12 mb-3">
            <label class="fw-bold">Sponsorship:</label>
            <input type="text" id="profileSponsorship" class="form-control" disabled value="{{ $user->sponsorship }}">
        </div>
    </div>

    <div class="col-md-6">

        <div class="col-md-12 mb-3">
            <label class="fw-bold">Phone:</label>
            <input type="text" id="profilePhone" class="form-control" disabled value="{{ $user->phone }}">
        </div>

        <div class="col-md-12 mb-3">
            <label class="fw-bold">Gender:</label>
            <input type="text" id="profileGender" class="form-control" disabled value="{{ $user->gender }}">
        </div>

        <div class="col-md-12 mb-3">
            <label class="fw-bold">Nationality:</label>
            <input type="text" id="profileNationality" class="form-control" disabled value="{{ $user->nationality }}">
        </div>

        <div class="col-md-12 mb-3">
            <label class="fw-bold">Course:</label>
            <input type="text" id="profileCourse" class="form-control" disabled value="{{ $user->course }}">
        </div>

        <!-- Note Section -->
        <div class="col-12 mb-3">
            <div id="verifyBox" class="alert alert-success  align-items-center">

                <strong>Success!</strong> Your information has been successfully confirmed.
            </div>
        </div>

        <!-- Next Step Button -->
        <div class="col-12 text-center mt-4">
            @if ($user->application == 1)
            <!-- Button or content for application status 1 -->
            @else
            <button id="nextstep" class="btn btn-outline-success" onclick="hostel()">Next Stage</button>
            @endif
        </div>
    </div>

</div>

    </div>

</div>

@endif

</div>

@endsection
