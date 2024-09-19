@php

use App\Models\User;
use App\Models\Bed;

@endphp

<!-- Delete Bed Modal -->
<div id="deleteBed" class="modal fade" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog rounded" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="text-center rounded">
                    <i class="gd-alert icon-text icon-text-xxl d-block text-danger mb-3 mb-md-4"></i>
                    <div class="h5 font-weight-semi-bold mb-2">Delete Bed {{ $bed->bed_number }}</div>
                    <p class="mb-3 mb-md-4">Are you sure?</p>
                    <div class="d-flex justify-content-between mb-4">
                        <a class="btn btn-outline-success" href="#"
                           onclick="deleteBed(event, {{ $bed->id }})">Yes</a>
                        <a class="btn btn-outline-danger" href="#" data-dismiss="modal">No</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Function to handle bed deletion
        window.deleteBed = function(event, bedId) {
            event.preventDefault(); // Prevent default action

            // Show the overlay
            $('#overlay').css('display', 'flex');

            // Disable the button to prevent multiple submissions
            $('#deleteBed .btn-outline-success').prop('disabled', true);

            // Get the CSRF token from the meta tag in the HTML
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Make the AJAX request to delete the bed
            $.ajax({
                url: '{{ route('bed.destroy', ['id' => '__bedId__']) }}'.replace('__bedId__', bedId),
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Include the CSRF token in the request headers
                },
                success: function(response) {
                    $('#deleteBed .btn-outline-success').prop('disabled', false);
                    $('#overlay').fadeOut();

                    // Display success message from server
                    var successToast = $('#success-toast');
                    successToast.find('.toast-body').text(response.message || 'Bed deleted successfully.');
                    successToast.toast('show');

                    closeModalAndExecuteHostel();
                },
                error: function(xhr) {
                    $('#deleteBed .btn-outline-success').prop('disabled', false);
                    $('#overlay').fadeOut();

                    var errorToast = $('#error-toast');
                    errorToast.find('.toast-body').empty(); // Clear previous messages

                    // Handle error messages from server
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        // Display server message if available
                        errorToast.find('.toast-body').text(xhr.responseJSON.message);
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        // Collect all error messages
                        var errorMessages = [];
                        $.each(xhr.responseJSON.errors, function(key, messages) {
                            errorMessages.push(messages.join('<br>'));
                        });
                        errorToast.find('.toast-body').html(errorMessages.join('<br>'));
                    } else {
                        // Default error message if no specific message is found
                        errorToast.find('.toast-body').text('An error occurred. Please try again.');
                    }
                    errorToast.toast('show');

                    closeModalAndExecuteHostel1();
                },
                complete: function() {
                    // Always hide the overlay
                    $('#overlay').fadeOut('fast');
                }
            });
        };

        function showToast(toastId, message) {
            var toastElement = $('#' + toastId);
            toastElement.find('.toast-body').text(message);
            toastElement.toast('show');
        }

        // Close modal and reload hostel after deleting the bed
        function closeModalAndExecuteHostel() {
            $('#deleteBed').modal('hide'); // Use the correct ID of your modal
            $('#deleteBed').on('hidden.bs.modal', function() {
                room({{ $bed->room->floor->block->id }}); // Adjust as necessary
            });
        }

        function closeModalAndExecuteHostel1() {
            $('#deleteBed').modal('hide'); // Use the correct ID of your modal
            $('#deleteBed').on('hidden.bs.modal', function() {
                // Additional actions if needed
            });
        }
    });
</script>


<div class="content">
    <div class="py-4 px-3 px-md-4">


        <form id="bedForm" action="" method="POST">
            <div class="d-flex justify-content-between mb-4">

                <button class="btn btn-outline-secondary" onclick="room({{ $bed->room->floor->block->id }})"> <i
                        class="gd-shift-left"></i></button>
                <button class="btn btn-outline-secondary" onclick="floorAction('bed', {{ $bed->id }})"> <i
                        class="gd-loop "></i></button>

                <a href="#" class="text-danger btn border shadow-sm" title="Delete Bed {{ $bed->bed_number }}"
                    data-toggle="modal" data-target="#deleteBed">
                    <i class="gd-trash"></i>
                </a>

                <button id="update_button" type="submit" class="btn shadow-sm"> <i class="gd-upload"></i></button>

            </div>
                   <!-- Status Indicator -->
                   <div class="row">
                    <div class="col-md-12">

                        {{-- @if($status == 1)      5966


                    @else
                    <div class="alert alert-danger" role="alert">
                        This application has expired. The student will need to reapply if they wish to continue.
                    </div>

                    @endif --}}




                    @if($user)
                    <div class="alert alert-success" role="alert" id="statusindicator">
                        This room is occupied by {{ $user->name}}
                    </div>
                    @else
                    <div class="alert alert-danger" id="statusindicator" role="alert">
                        This room is open.
                    </div>
                    @endif





                    </div>
                </div>
            <!-- Bed Details -->
            <div class="">
                <div class="row">
                    <div class="col-md-6 mb-3">
      <!-- Bed Number -->
      <div class="col-md-12 mb-3">
        <label for="bedNumber">Bed Number</label>
        <input type="text" class="form-control" id="bedNumber" name="bed_number" value="{{ $bed->bed_number }}">
    </div>

    <!-- Room Number -->
    <div class="col-md-12 mb-3">
        <label for="roomNumber">Room Number</label>
        <input type="text" class="form-control" id="roomNumber" value="{{ $bed->room->room_number }}"
            disabled>
    </div>

         <!-- Room Gender -->
         <div class="col-md-12 mb-3">
            <label for="roomGender">Room Gender</label>
            <input type="text" class="form-control" id="roomGender" value="{{ $bed->room->gender }}"
                disabled>
        </div>

    <!-- Floor Name -->
    <div class="col-md-12 mb-3">
        <label for="floorName">Floor Name</label>
        <input type="text" class="form-control" id="floorName"
            value="{{ $bed->room->floor->floor_number }}" disabled>
    </div>

    <!-- Block Name -->
    <div class="col-md-12 mb-3">
        <label for="blockName">Block Name</label>
        <input type="text" class="form-control" id="blockName"
            value="{{ $bed->room->floor->block->name }}" disabled>
    </div>
    <div class="col-md-12 mb-3">
        <label for="UserControlnumber">Control number</label>
        <input type="text" class="form-control" id="UserControlnumber"
               value="{{ $user->Control_Number ?? 'Not Generated' }}" disabled>
    </div>
    <div class="col-md-12 mb-3">
        <label for="Userpayment">Payment</label>
        <input type="text" class="form-control" id="Userpayment"
        value="{{ $user->payment_status ?? 'Not paid' }}" disabled>





    </div>

    <div class="col-md-12 mb-3 remove" style=" @if($user) display: none; @endif">
        <div class="form-group mt-2">
            <label>Bed Status</label>
            <div class="btn-group-toggle d-flex justify-content-between" data-toggle="buttons">
                <label class="btn btn-outline-danger  mx-1 {{ $bed->status == 'under_maintenance' ? 'active' : '' }}" style="cursor: pointer;">
                    <input type="radio" name="bedStatus" value="under_maintenance" autocomplete="off"
                        {{ $bed->status == 'under_maintenance' ? 'checked' : '' }} style="display: none;">Maintenance
                </label>
                <label class="btn btn-outline-warning mx-1 {{ $bed->status == 'reserve' ? 'active' : '' }}" style="cursor: pointer;">
                    <input type="radio" name="bedStatus" value="reserve" autocomplete="off"
                        {{ $bed->status == 'reserve' ? 'checked' : '' }} style="display: none;"> Reserve
                </label>
                <label class="btn btn-outline-success  mx-1 {{ $bed->status == 'activate' ? 'active' : '' }}" style="cursor: pointer;">
                    <input type="radio" name="bedStatus" value="activate" autocomplete="off"
                        {{ $bed->status == 'activate' ? 'checked' : '' }} style="display: none;"> Activate
                </label>
            </div>
            <small id="bedStatusError" class="form-text text-danger"></small>
        </div>
    </div>





                    </div>

                    <div class="col-md-6 mb-3">


                 <!-- Search Input and Add Button -->
<div class="form-group position-relative remove" style=" @if($user) display: none; @endif">
    <label for="searchStudent">Search for Eligible Students</label>
    <div class="input-group">
        <input type="text" class="form-control" id="searchStudent" placeholder="Enter student name or ID">
        <div class="input-group-append">
            <div id="spinner" class="spinner-border spinner-border-sm text-primary ml-2" role="status" style="display: none;">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
    <!-- Search Results -->
    <div id="searchResults" class="search-result mt-2 position-absolute" style="top: 100%; left: 0; right: 0; max-height: 300px; overflow-y: auto; z-index: 1000;">
        <!-- Results will be populated here -->
    </div>
</div>




                <!-- Additional Non-Editable Details -->
                <div class="row mt-4">
                    <!-- Student Image -->
                    <div class="col-xl-12 mb-3">
                        <label for="studentImage">Student Image</label>
                        <div class="text-center">
                            <img id="image" src="{{ $user->profile_photo_path ?? 'img/placeholder.jpg' }}" alt="Student Image"
                                class="img-fluid  rounded-circle" id="studentImage" style="max-width: 30%; height: auto;">
                                <input type="text" name="image" id="imageInput" hidden>
                        </div>
                    </div>

                    <!-- Student Details -->
                    <div class="col-xl-12">
                        <div class="row" id="reset">
                            <!-- Name -->
                            <div class="col-md-6 mb-3">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" value="{{ $user->name ?? '' }}" disabled>
                            </div>

                            <!-- Registration Number -->
                            <div class="col-md-6 mb-3">
                                <label for="registrationNumber">Registration Number</label>
                                <input type="text" class="form-control" id="registrationNumber" value="{{ $user->registration_number ?? '' }}" disabled>
                            </div>

                            <!-- Sponsorship -->
                            <div class="col-md-6 mb-3">
                                <label for="sponsorship">Sponsorship</label>
                                <input type="text" class="form-control" id="sponsorship" value="{{ $user->sponsorship ?? '' }}" disabled>
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6 mb-3">
                                <label for="phone">Phone</label>
                                <input type="text" class="form-control" id="phone" value="{{ $user->phone ?? '' }}" disabled>
                            </div>

                            <!-- Gender -->
                            <div class="col-md-6 mb-3">
                                <label for="gender">Gender</label>
                                <input type="text" class="form-control" id="gender" value="{{ $user->gender ?? '' }}" disabled>
                            </div>

                            <!-- Nationality -->
                            <div class="col-md-6 mb-3">
                                <label for="nationality">Nationality</label>
                                <input type="text" class="form-control" id="nationality" value="{{ $user->nationality ?? '' }}" disabled>
                            </div>

                            <!-- Course -->
                            <div class="col-md-6 mb-3">
                                <label for="course">Course</label>
                                <input type="text" class="form-control" id="course" value="{{ $user->course ?? '' }}" disabled>
                            </div>

<!-- Email -->
<div class="col-md-6 mb-3">
    <label for="email">Email</label>
    <input type="text" class="form-control" id="email" value="{{ $user->email ?? '' }}" disabled>
</div>

<div class="col-md-6 mb-3">
    <label for="addStudent">Add Student</label>
    <button id="addStudentButton" class="btn btn-outline-success form-control">Add student</button>
</div>
<div class="col-md-6 mb-3">
    <label for="removeStudent">Remove Student</label>
    <button id="removeStudentButton" class="btn btn-outline-danger form-control" style="display: none;">Remove student</button>
</div>

                        </div>

                    </div>
                </div>



                    </div>





                </div>











<style>
    .spinner-border-sm {
        width: 1.2rem;
        height: 1.2rem;
    }
    .search-result {
        background-color: white;
        border-radius: 0.25rem;
        /* border: 1px solid #ccc; */
    }
    .search-result-item {
        padding: 8px;
        cursor: pointer;
    }
    .search-result-item:hover {
        background-color: #f0f0f0;
    }
</style>
<script>
    $(document).ready(function() {
        let searchResults = $('#searchResults');
        let searchStudent = $('#searchStudent');

        // Handle input event on the search field
        searchStudent.on('input', function() {
            let query = $(this).val().trim(); // Trim leading and trailing spaces
            query = query.replace(/\s+/g, ' '); // Replace multiple spaces with a single space

            if (query.length > 2) { // Start searching after 3 characters
                $('#spinner').show(); // Show the spinner

                $.ajax({
                    url: '/students/search',
                    method: 'GET',
                    data: { query: query },
                    success: function(data) {
                        let resultsHtml = '';
                        if (data.length > 0) {
                            data.forEach(student => {
                                resultsHtml +=
                                    `<div class="search-result-item"
                                        data-id="${student.id}"
                                        data-name="${student.student_name}"
                                        data-registration-number="${student.registration_number}"
                                        data-sponsorship="${student.sponsorship}"
                                        data-phone="${student.phone}"
                                        data-email="${student.email}"
                                        data-gender="${student.gender}"
                                        data-nationality="${student.nationality}"
                                        data-image="${student.image}"
                                        data-course="${student.course}">

                                        ${student.student_name} - ${student.registration_number}
                                    </div>`;
                            });
                        } else {
                            resultsHtml = '<div class="text-muted p-3">No records found</div>';
                        }
                        searchResults.html(resultsHtml);
                    },
                    error: function() {
                        searchResults.html('<div class="text-danger">An error occurred. Please try again.</div>');
                    },
                    complete: function() {
                        $('#spinner').hide(); // Hide the spinner after request completes
                    }
                });
            } else {
                searchResults.html('');
                $('#spinner').hide(); // Hide the spinner if query length is less than 3
            }
        });

        // Hide the search results when the input loses focus, but delay it to allow click event registration
        searchStudent.on('blur', function() {
            setTimeout(function() {
                searchResults.html(''); // Clear the search results
            }, 150);
        });

        // Handle selecting a student from the search results
        $(document).on('click', '.search-result-item', function() {
            let id = $(this).data('id');
            let name = $(this).data('name');
            let registrationNumber = $(this).data('registration-number');
            let sponsorship = $(this).data('sponsorship');
            let phone = $(this).data('phone');
            let gender = $(this).data('gender');
            let nationality = $(this).data('nationality');
            let course = $(this).data('course');
            let email = $(this).data('email');
            let image = $(this).data('image');

            // Populate the form fields
            $('#name').val(name);
            $('#registrationNumber').val(registrationNumber);
            $('#sponsorship').val(sponsorship);
            $('#phone').val(phone);
            $('#gender').val(gender);
            $('#nationality').val(nationality);
            $('#course').val(course);
            $('#email').val(email);
            $('#imageInput').val(image);
            $('#image').attr('src', image);


            // Clear the search results after selecting a student
            searchResults.html('');
        });
    });
</script>


            </div>



            <script>
                $(document).ready(function() {
                    $('.dropdown-item').on('click', function() {
                        // Get the selected value and text
                        var selectedText = $(this).text().trim();
                        var selectedValue = $(this).data('value');
                        // Update the button text to show the selected option
                        $('#selectedStatus').text(selectedText);
                        // Optionally, you can also log the selected value
                        console.log('Selected Status Value:', selectedValue);
                    });
                });
            </script>

            {{--
            <h1>Details for Bed {{ $bed->bed_number }}</h1>
            <p>Bed Number: {{ $bed->bed_number }}</p>
            <p>Status: {{ $bed->status ?? 'N/A' }}</p>
            <!-- Adjust based on your schema if 'status' is not available in Bed -->
            <p>Room Number: {{ $bed->room->room_number ?? 'N/A' }}</p>
            <p>Floor Name: {{ $bed->room->floor->floor_number ?? 'N/A' }}</p>
            <p>Block Name: {{ $bed->room->floor->block->name ?? 'N/A' }}</p> --}}

        </form>
    </div>
</div>

<script>
    function floorAction(action, id) {
        const selectors = [
            "#nav_profile",
            "#nav_room",
            "#nav_finish",
            "#nav_result",
        ];
        selectors.forEach(function(selector) {
            $(selector).removeClass("active");
        });
        $("#nav_hostel").addClass("active");
        $("#dash").html(
            '<div class="spinner-container">' +
            '<div class="black show d-flex align-items-center justify-content-center">' +
            '<div class="spinner-border lik" style="width: 3rem; height: 3rem;" role="status">' +
            '<span class="sr-only">Loading...</span>' +
            '</div>' +
            '</div>' +
            '</div>'
        );
        let url;
        switch (action) {
            case 'add':
                url = `{{ url('floor/add') }}/${id}`;
                break;
            case 'update':
                url = `{{ url('floor/update') }}/${id}`;
                break;
            case 'delete':
                url = `{{ url('floor/delete') }}/${id}`;
                break;
            case 'bed':
                url = `{{ url('room/bed') }}/${id}`;
                break;
            default:
                console.error('Invalid action');
                return;
        }
        $("#dash").load(url, (response, status, xhr) => {
            if (status === "error") {
                const msg = `Sorry, but there was an error: ${xhr.status} ${xhr.statusText}`;
                $("#error").html(msg);
            }
        });
    }
</script>


<script>
   // Check initial button state based on whether a student is assigned
   function updateButtonState() {
        var userId = {{ $bed->user_id ? 'true' : 'false' }};
        if (userId) {
            $('#addStudentButton').hide();
            $('#removeStudentButton').show();
        } else {
            $('#addStudentButton').show();
            $('#removeStudentButton').hide();
        }
    }

    // Call function to set initial button state
    updateButtonState();


    function clearInputFields() {
        $('#name').val('');
        $('#email').val('');
        $('#registrationNumber').val('');
        $('#sponsorship').val('');
        $('#phone').val('');
        $('#gender').val('');
        $('#nationality').val('');
        $('#course').val('');
        $('#image').val('');
        $('#image').attr('src', 'img/placeholder.jpg');
    }


$(document).ready(function() {
    // Set up CSRF token for AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Handle "Add student" button click
    $('#addStudentButton').on('click', function(event) {
        event.preventDefault(); // Prevent default button behavior


        // Basic validation
        let isValid = true;
        let errors = [];

        // Check if required fields are filled
        $('#bedForm input').each(function() {
            if ($(this).val() === '' && !$(this).is('[disabled]')) {
                isValid = false;
                errors.push(`Please fill in the ${$(this).prev('label').text().toLowerCase()}.`);
            }
        });

        // Display errors if validation fails
        if (!isValid) {
            var errorToast = $('#error-toast');
            errorToast.find('.toast-body').text('Search eligible student first, then add to this room.');
            errorToast.toast('show');
            return; // Stop further execution
        }

        $('#addStudentButton').addClass('disabled');
        $('#overlay').css('display', 'flex');

        // Collect form data
        let formData = {
            name: $('#name').val(),
            email: $('#email').val(),
            registration_number: $('#registrationNumber').val(),
            sponsorship: $('#sponsorship').val(),
            phone: $('#phone').val(),
            gender: $('#gender').val(),
            nationality: $('#nationality').val(),
            course: $('#course').val(),
            image: $('#imageInput').val(),
            block_id: {{ $bed->room->floor->block->id }},
            floor_id: {{ $bed->room->floor->id }},
            room_id: {{ $bed->room->id }},
            bed_id: {{ $bed->id }} // Ensure this matches the route parameter
        };





        // Perform AJAX request to add the student
        $.ajax({
            url: '{{ route('student.add', ['bedId' => $bed->id]) }}',
            method: 'POST',
            data: formData,
            success: function(response) {
                $('#overlay').fadeOut();
                var successToast = $('#success-toast');
                var errorToast = $('#error-toast');

                if (response.success) {
                    successToast.find('.toast-body').text('Student added successfully!');
                    successToast.toast('show');
                    // Update the button to "Remove Student"
                    $('#addStudentButton').hide();
                    $('#removeStudentButton').show();
                    $('.remove').hide();

                    $('#statusindicator').removeClass('alert-danger').addClass('alert-success');
                    $('#statusindicator').text('This room is occupied by ' + response.user.name);
                } else {
                    errorToast.find('.toast-body').text('Addition failed: ' + response.message);
                    errorToast.toast('show');
                }
            },
            error: function(xhr) {
    console.error('AJAX Error:', xhr);
    var errorToast = $('#error-toast');

    // Initialize a variable to hold the error message
    var errorMessage = 'An error occurred. Please try again.';

    // Check if the response has a JSON body
    if (xhr.responseJSON) {
        // Handle server-side validation errors
        if (xhr.responseJSON.errors) {
            // Combine all validation error messages into a single string
            errorMessage = Object.values(xhr.responseJSON.errors).flat().join(' ');
        } else if (xhr.responseJSON.message) {
            // Handle general error messages from the server
            errorMessage = xhr.responseJSON.message;
        }
    } else {
        // Handle cases where the response is not JSON
        errorMessage = 'An unexpected error occurred.';
    }

    // Set the toast message and display it
    errorToast.find('.toast-body').text(errorMessage);
    errorToast.toast('show');

    // Re-enable the button and hide the overlay
    $('#addStudentButton').removeClass('disabled');
    $('#overlay').fadeOut();
}

        });
    });
});

</script>

<script>
    $(document).ready(function() {
    // Set up CSRF token for AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Ensure only one event handler is attached
    $(document).off('click', '#removeStudentButton').on('click', '#removeStudentButton', function(event) {
        event.preventDefault(); // Prevent default button behavior

        $('#removeStudentButton').addClass('disabled');
        $('#overlay').css('display', 'flex');

        // Get bedId from Laravel's Blade syntax
        var bedId = {{ $bed->id }};

        // Perform AJAX request to remove the student
        $.ajax({
            url: '{{ route('student.remove', ['bedId' => $bed->id]) }}', // Use named route
            method: 'POST',
            success: function(response) {
                $('#overlay').fadeOut();
                var successToast = $('#success-toast');
                var errorToast = $('#error-toast');

                if (response.success) {
                    successToast.find('.toast-body').text('Student removed successfully.');
                    successToast.toast('show');

                    // Clear all fields
                    $('#bedForm')[0].reset();
                    clearInputFields();

                    $('.remove').show();

                    // Restore the button to "Add Student"
                    $('#removeStudentButton').hide();
                    $('#addStudentButton').show();
                    $('#statusindicator').removeClass('alert-success').addClass('alert-danger');
                    $('#statusindicator').text('No student assigned to this room.');

                } else {
                    // Display the error message returned by the server
                    errorToast.find('.toast-body').text('Removal failed: ' + response.message);
                    errorToast.toast('show');
                }
                $('#removeStudentButton').removeClass('disabled');
            },
            error: function(xhr) {
                console.error('AJAX Error:', xhr);

                var errorToast = $('#error-toast');
                // Handle different error responses if needed
                if (xhr.status === 400 || xhr.status === 404) {
                    errorToast.find('.toast-body').text('Error: ' + xhr.responseJSON.message);
                } else {
                    errorToast.find('.toast-body').text('An unexpected error occurred. Please try again.');
                }
                errorToast.toast('show');
                $('#removeStudentButton').removeClass('disabled');
                $('#overlay').fadeOut();
            }
        });
    });
});

</script>



<script>
    $(document).ready(function() {
        $('#delete_button').on('click', function(e) {
            e.preventDefault(); // Prevent default form submission

            $('#delete_button').addClass('disabled');
            $('#overlay').css('display', 'flex');

            $.ajax({
                url: '/bed/' + bedId, // Adjust the URL as per your route
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}' // Include CSRF token if required by your Laravel setup
                },
                success: function(response) {
                    $('#delete_button').removeClass('disabled');
                    $('#overlay').fadeOut();
                    if (response.success) {
                        var successToast = $('#success-toast');
                        successToast.find('.toast-body').text(response.message); // Use the dynamic message from the server
                        successToast.toast('show');
                    }
                },
                error: function(xhr) {
                    $('#delete_button').removeClass('disabled');
                    $('#overlay').fadeOut();

                    var errorToast = $('#error-toast');
                    errorToast.find('.toast-body').empty(); // Clear previous messages

                    // Check if the response contains specific error messages
                    if (xhr.responseJSON.message) {
                        // Show specific error message from the server
                        errorToast.find('.toast-body').text(xhr.responseJSON.message);
                    } else {
                        // Default error message if no specific message is found
                        errorToast.find('.toast-body').text('An error occurred. Please try again.');
                    }
                    errorToast.toast('show');
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#update_button').on('click', function(e) {
            e.preventDefault(); // Prevent the default form submission

            var bedId = '{{ $bed->id }}'; // Assuming you have the bed ID available in your view
            var formData = {
                bed_number: $('#bedNumber').val(),
                bed_status: $('input[name="bedStatus"]:checked').val(),
                _token: '{{ csrf_token() }}' // Include CSRF token for security
            };

            $('#update_button').addClass('disabled'); // Disable the button to prevent multiple submissions
            $('#overlay').css('display', 'flex'); // Show an overlay/spinner while processing

            $.ajax({
                url: '/update-bed/' + bedId, // URL to the update route
                method: 'POST',
                data: formData,
                success: function(response) {
                    $('#update_button').removeClass('disabled');
                    $('#overlay').fadeOut();

                    if (response.success) {
                        var successToast = $('#success-toast');
                        successToast.find('.toast-body').text(response.message);
                        successToast.toast('show');
                    } else {
                        var errorToast = $('#error-toast');
                        errorToast.find('.toast-body').text('Update failed: ' + response.message);
                        errorToast.toast('show');
                    }
                },
                error: function(xhr) {
                    $('#update_button').removeClass('disabled');
                    $('#overlay').fadeOut();

                    var errorToast = $('#error-toast');
                    errorToast.find('.toast-body').empty();

                    // Show specific error message from the server, or a default one if not available
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorToast.find('.toast-body').text(xhr.responseJSON.message);
                    } else {
                        errorToast.find('.toast-body').text('An error occurred. Please try again.');
                    }
                    errorToast.toast('show');
                }
            });
        });
    });
</script>
