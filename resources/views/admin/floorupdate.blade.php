@php
    use App\Models\Bed;
@endphp

<div class="content">
    <div class="py-4 px-3 px-md-4">

        <div id="success-message" class="w-100 "></div>

        <form id="floorForm" action="{{ route('floor.update', $floor->id) }}" method="POST">
            <div class="d-flex justify-content-between mb-4">
                {{-- <h3>Update Floor {{ $floor->floor_number }}</h3> --}}
                <button class="btn btn-outline-secondary" onclick="room({{ $blockId  }})"> <i
                        class="gd-shift-left"></i></button>
                <button class="btn btn-outline-secondary" onclick="floorAction('update', {{ $floor->id }})"> <i
                        class="gd-loop "></i></button>

                <a href="#" class="text-danger btn border shadow-sm" title="Delete Floor {{ $floor->floor_number }}"
                    data-toggle="modal" data-target="#deletefloor">
                    <i class="gd-trash"></i>
                </a>


                <button id="update_button" type="submit" class="btn shadow-sm"> <i class="gd-upload"></i></button>



            </div>


            @method('PUT')

            <!-- Floor Details -->
            <div class="row">
                <!-- Floor Number -->
                <div class="col-md-6 mb-3">
                    <label for="floorNumber">Floor Name</label>
                    <input type="text" class="form-control" id="floorNumber" name="floor_number"
                        value="{{ $floor->floor_number }}" required>
                </div>

                <!-- Number of Rooms -->
                <div class="col-md-6 mb-3">
                    <label for="numberOfRooms">Number of Rooms</label>
                    <input type="number" class="form-control" id="numberOfRooms2" value="{{ $floor->number_of_rooms }}"
                        disabled>

                    <input type="number" class="form-control" id="numberOfRooms" name="number_of_rooms"
                        value="{{ $floor->number_of_rooms }}" required hidden>
                </div>

            </div>
            <!-- Eligible Students -->
            <div class="form-group mb-4 mt-3">
                <label class="mb-3">Eligible Students</label>
                <div class="btn-group-toggle d-flex flex-wrap" data-toggle="buttons">
                    @foreach (['D1', 'D2', 'D3', 'B1', 'B2', 'B3', 'B4'] as $option)
                    <label
                        class="btn btn-outline-info flex-fill mx-1 {{ in_array($option, $eligibilityOptions) ? 'active' : '' }}"
                        style="cursor: pointer;">
                        <input type="checkbox" name="eligibility[]" value="{{ $option }}"
                            {{ in_array($option, $eligibilityOptions) ? 'checked' : '' }} style="display: none;">
                        {{ $option }}
                    </label>
                    @endforeach
                </div>
                <small id="eligibilityError" class="form-text text-danger" style="display: none;"></small>
            </div>

            <h5 class="mb-3">Rooms and Beds</h5>



            <div id="roomsContainer" class="row">
                @foreach($floor->rooms as $room)

@php
// Check if any bed in the room is assigned to a user
$roomHasUsers = $room->beds->filter(function ($bed) {
    return $bed->user !== null; // Check if the bed has a user assigned
})->count() > 0;

$assignedBedsCount = $room->beds->filter(function ($bed) {
        return $bed->user !== null; // Check if the bed has a user assigned
    })->count();
@endphp
                <div class="col-xl-6 mb-3 room-item" id="room-{{ $room->id }}">
                    <label for="room-number-{{ $room->id }}" class="mr-2">Students {{$assignedBedsCount}}</label>
                    <div class="d-flex align-items-center">
                        <label for="room-number-{{ $room->id }}" class="mr-2">Name</label>
                        <input type="text" class="form-control col-3 mr-2" id="room-number-{{ $room->id }}"
                            name="rooms[{{ $room->id }}][room_number]" value="{{ $room->room_number }}"
                            placeholder="Room Number">

                        <label for="room-{{ $room->id }}" class="mr-2">Beds</label>
                        <input type="number" class="form-control col-3 mr-2" id="room-{{ $room->id }}"
                            name="rooms[{{ $room->id }}][number_of_beds]" value="{{ $room->beds->count() }}" min="{{$assignedBedsCount}}"
                            placeholder="Beds">

                            @if (!$roomHasUsers)
                            <small class="form-text text-muted ml-2">Current beds: {{ $room->beds->count() }}</small>
                            <button type="button" class="alert alert-danger btn-sm ml-2" id="remove-{{ $room->id }}"><i
                                    class="gd-trash"></i></button>

                                    @else
                                    <small class="form-text text-muted ml-2">Current beds: {{ $room->beds->count() }}</small>
                                    <button type="button" class="btn-sm ml-2" hidden></button>



                            @endif


                    </div>

                    @php
                    // Check if the floor has any gender
                    $genders = json_decode($floor->gender, true);
                    // Ensure gender values are only 'male' or 'female'
                    $validGenders = ['male', 'female'];
                    $genders = array_intersect($genders, $validGenders);

                    // Get the current gender for the room
                    $currentRoomGender = $room->gender;
                    @endphp

@php
// Decode the JSON gender field from the floor table
$availableGenders = json_decode($floor->gender, true);
@endphp

<div class="mt-2">
<label>Specify Gender: </label>
<div class="btn-group btn-group-toggle" role="group" aria-label="Gender Selection">
    @foreach($availableGenders as $gender)

        @php
            $isActive = $currentRoomGender === $gender;
        @endphp


        @if ($roomHasUsers)
        <label class="btn btn-outline-primary gender-btn mx-1 {{ $isActive ? 'active' : '' }}"
        data-room-id="{{ $room->id }}" data-gender="{{ $gender }}"
        style="pointer-events: none; opacity: 0.6;">
     {{ ucfirst($gender) }}
     <input type="radio" name="rooms[{{ $room->id }}][gender]" value="{{ $gender }}" style="display: none;" {{ $isActive ? 'checked' : '' }} readonly disabled>

 </label>





        @else
        <label class="btn btn-outline-primary gender-btn mx-1 {{ $isActive ? 'active' : '' }}"
        data-room-id="{{ $room->id }}" data-gender="{{ $gender }}"
        onclick="selectGender(this)">
     {{ ucfirst($gender) }}
     <input type="radio" name="rooms[{{ $room->id }}][gender]" value="{{ $gender }}" style="display: none;" {{ $isActive ? 'checked' : '' }}>
 </label>
        @endif

    @endforeach
</div>
</div>


                    <script>
                        function selectGender(button) {
                            // Remove 'active' class from all buttons
                            const buttons = button.parentElement.querySelectorAll('.gender-btn');
                            buttons.forEach(btn => btn.classList.remove('active'));

                            // Add 'active' class to the clicked button
                            button.classList.add('active');

                            // Update the hidden input
                            const input = button.querySelector('input[type="radio"]');
                            input.checked = true;

                            // Optionally, handle this selection further
                            const roomId = button.getAttribute('data-room-id');
                            const selectedGender = button.getAttribute('data-gender');
                            console.log(`Room ${roomId} gender selected: ${selectedGender}`);
                        }

                        // Add event listeners to gender buttons for each room
                        document.querySelectorAll('.gender-btn').forEach(button => {
                            button.addEventListener('click', function() {
                                selectGender(this);
                            });
                        });
                    </script>

<script>
    (function() {
        var roomId = "{{ $room->id }}";
        var removeButton = document.getElementById('remove-' + roomId);

        if (removeButton) {
            removeButton.addEventListener('click', function() {
                var roomItem = document.getElementById('room-' + roomId);
                if (roomItem) {
                    roomItem.remove();
                    let roomCount = document.querySelectorAll('.room-item:visible').length;
                    document.getElementById('numberOfRooms').value = roomCount;
                }
            });
        }
    })();
</script>

                    <hr>
                </div>
                @endforeach
            </div>


            <!-- Add Room Button -->
            <div class="text-center mb-3">
                <button type="button" id="addRoom" class="alert alert-success"> <i class="gd-plus "></i>
                    @csrf</button>
            </div>



@php
    $floorId = $floor->id;

$hasOccupiedBed = Bed::whereHas('room', function ($query) use ($floorId) {
    $query->where('floor_id', $floorId);
})->whereHas('user')->exists();


// $hasOccupiedBed = Bed::whereHas('room', function ($query) use ($floorId) {
//     $query->where('floor_id', $floorId);
// })->whereHas('user', function ($query) {
//     $query->where('semester_id', session('semester_id')); // Ensure the user belongs to the current semester
// })->exists();

if ($hasOccupiedBed) {
    echo "At least one bed is currently occupied by a user. Consequently, gender selection has been disabled to prevent both male and female occupants from being assigned to the same room.";
}

@endphp


@if ($hasOccupiedBed)
<!-- Eligible Gender -->
<div class="form-group mb-4">
    <label>Eligible Gender</label>
    <div class="btn-group-toggle d-flex justify-content-between" data-toggle="buttons">
        <label
            class="btn btn-outline-primary flex-fill mx-1 {{ in_array('male', json_decode($floor->gender, true)) ? 'active' : '' }}"
            style="cursor: not-allowed; opacity: 0.6;" readonly>
            <input type="checkbox" name="gender[]" value="male"
                {{ in_array('male', json_decode($floor->gender, true)) ? 'checked' : '' }}
                style="display: none;" disabled> Male
            <input type="hidden" name="gender[]" value="male" {{ in_array('male', json_decode($floor->gender, true)) ? 'checked' : '' }}>
        </label>
        <label
            class="btn btn-outline-primary flex-fill mx-1 {{ in_array('female', json_decode($floor->gender, true)) ? 'active' : '' }}"
            style="cursor: not-allowed; opacity: 0.6;" readonly>
            <input type="checkbox" name="gender[]" value="female"
                {{ in_array('female', json_decode($floor->gender, true)) ? 'checked' : '' }}
                style="display: none;" disabled> Female
            <input type="hidden" name="gender[]" value="female" {{ in_array('female', json_decode($floor->gender, true)) ? 'checked' : '' }}>
        </label>
    </div>
    <small id="genderError" class="form-text text-danger" style="display: none;"></small>
</div>





@else
<!-- Eligible Gender -->
<div class="form-group mb-4">
    <label>Eligible Gender</label>
    <div class="btn-group-toggle d-flex justify-content-between" data-toggle="buttons">
        <label
            class="btn btn-outline-primary flex-fill mx-1 {{ in_array('male', json_decode($floor->gender, true)) ? 'active' : '' }}"
            style="cursor: pointer;">
            <input type="checkbox" name="gender[]" value="male"
                {{ in_array('male', json_decode($floor->gender, true)) ? 'checked' : '' }}
                style="display: none;"> Male
        </label>
        <label
            class="btn btn-outline-primary flex-fill mx-1 {{ in_array('female', json_decode($floor->gender, true)) ? 'active' : '' }}"
            style="cursor: pointer;">
            <input type="checkbox" name="gender[]" value="female"
                {{ in_array('female', json_decode($floor->gender, true)) ? 'checked' : '' }}
                style="display: none;"> Female
        </label>
    </div>
    <small id="genderError" class="form-text text-danger" style="display: none;"></small>
</div>

@endif


        </form>

    </div>
</div>
<!-- Modal -->
<div id="deletefloor" class="modal fade" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog rounded" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="text-center rounded">
                    <i class="gd-alert icon-text icon-text-xxl d-block text-danger mb-3 mb-md-4"></i>
                    <div class="h5 font-weight-semi-bold mb-2">Delete Floor {{ $floor->floor_number }}</div>
                    <p class="mb-3 mb-md-4">Deleting this floor will also remove all associated rooms and beds.</p>
                    <div class="d-flex justify-content-between mb-4">
                        <a class="btn btn-outline-success" href="#" onclick="deleteFloor(event, {{ $floor->id }})">Yes</a>
                        <a class="btn btn-outline-danger" href="#" data-dismiss="modal">No</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->

<script>
$(document).ready(function() {
    $("#success-message").hide();
    let removedRooms = []; // Array to track removed rooms
    let existingRoomNumbers = new Set(); // Set to track existing room numbers
    let highestRoomNumber = 0; // Variable to track the highest room number

    // Function to initialize existing room numbers from the server or pre-existing data
    function initializeExistingRoomNumbers() {
        $('.room-item').each(function() {
            let roomNumber = parseInt($(this).find('input[name$="[room_number]"]').val());
            if (!isNaN(roomNumber)) {
                existingRoomNumbers.add(roomNumber);
                if (roomNumber > highestRoomNumber) {
                    highestRoomNumber = roomNumber;
                }
            }
        });
    }

    // Function to recalculate the highest room number based on the current DOM
    function recalculateHighestRoomNumber() {
        let maxRoomNumber = 0;
        $('.room-item').each(function() {
            let roomNumber = parseInt($(this).find('input[name$="[room_number]"]').val());
            if (!isNaN(roomNumber) && roomNumber > maxRoomNumber) {
                maxRoomNumber = roomNumber;
            }
        });
        highestRoomNumber = maxRoomNumber;
    }

    // Function to get the next available room number
    function getNextRoomNumber() {
        recalculateHighestRoomNumber();
        return ++highestRoomNumber;
    }

    // Initialize existing room numbers on document ready
    initializeExistingRoomNumbers();
    updateRoomCount(); // Initial room count update


// Assuming you have an array of available genders
let availableGenders = {!! json_encode($genders) !!};

$('#addRoom').on('click', function() {
    let newRoomNumber = getNextRoomNumber(); // Get the next room number
    let genderHtml = '';

    // Loop through the available genders to create the radio buttons
    availableGenders.forEach(function(gender) {
        genderHtml += `
            <label class="btn btn-outline-primary flex-fill mx-1 gender-btn"
                   data-room-id="new-${newRoomNumber}" data-gender="${gender}"
                   onclick="selectGender(this)">
                ${gender.charAt(0).toUpperCase() + gender.slice(1)}
                <input type="radio" name="rooms[new-${newRoomNumber}][gender]" value="${gender}" style="display: none;">
            </label>`;
    });

    let newRoomHtml = `
        <div class="col-md-6 mb-3 room-item" id="room-new-${newRoomNumber}">
            <div class="d-flex align-items-center">
                <label for="room-number-new-${newRoomNumber}" class="mr-2">Name</label>
                <input type="text" class="form-control col-3 mr-2" id="room-number-new-${newRoomNumber}"
                    name="rooms[new-${newRoomNumber}][room_number]" value="${newRoomNumber}" min="1"
                    placeholder="Room Number">

                <label for="room-new-${newRoomNumber}" class="mr-2">Beds</label>
                <input type="number" class="form-control col-3 mr-2" id="room-new-${newRoomNumber}"
                    name="rooms[new-${newRoomNumber}][number_of_beds]" value="0" min="0"
                    placeholder="Beds">

                <small class="form-text text-mute ml-2 text-success">Added room</small>
                <button type="button" class="alert alert-danger btn-sm ml-2 remove-room"
                    data-room-number="new-${newRoomNumber}"><i class="gd-trash"></i></button>
            </div>

            <div class="mt-2">
                <label for="room-gender-new-${newRoomNumber}" class="mr-2">Specify Gender:</label>
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    ${genderHtml}
                </div>
            </div>
            <hr>
        </div>`;

    // Append the new room HTML
    $('#roomsContainer').append(newRoomHtml);

    // Update room count after adding a new room
    updateRoomCount();
});




    // Handle the removal of rooms
    $(document).on('click', '.remove-room', function() {
        let roomNumber = $(this).data('room-number').replace('new-', ''); // Extract room number
        let roomItem = $(`#room-new-${roomNumber}`);
        if (roomItem.length) {
            roomItem.remove();
            removedRooms.push(roomNumber); // Track removed room numbers
            // Log the number of rooms before updating
            console.log('Before update:', $('.room-item').length);
            // Update room count after removing a room
            updateRoomCount();
        }
    });

    // Function to update the room count in the DOM
    function updateRoomCount() {
        // Count only visible room items
        let roomCount = $('.room-item:visible').length;
        // Update the input field with the visible room count
        $('#numberOfRooms, #numberOfRooms2').val(roomCount);
    }

    // Handle changes to room numbers
    $(document).on('change', 'input[name^="rooms["][name$="[room_number]"]', function() {
        let newRoomNumber = parseInt($(this).val());
        if (!isNaN(newRoomNumber)) {
            existingRoomNumbers.add(newRoomNumber);
            recalculateHighestRoomNumber(); // Recalculate the highest room number after change
            updateRoomCount(); // Update room count after room number change
        }
    });

    // Handle form submission
    $('#floorForm').on('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        // Validation flags
        let isValid = true;
        let errorMessage = '';

        // Validate Floor Number
        const floorNumber = $('#floorNumber').val();
        if (floorNumber === '' || floorNumber <= 0) {
            isValid = false;
            errorMessage += 'Floor number is required and must be greater than zero.<br>';
        }

        // Validate Number of Rooms
        const numberOfRooms = $('#numberOfRooms').val();
        if (numberOfRooms === '' || numberOfRooms <= 0) {
            isValid = false;
            errorMessage += 'Number of rooms is required and must be greater than zero.<br>';
        }

        // Validate Number of Beds
        let bedsValid = true;
        $('input[name^="rooms["][name$="[number_of_beds]"]').each(function() {
            const numberOfBeds = $(this).val();
            if (numberOfBeds === '' || numberOfBeds <= 0) {
                bedsValid = false;
                $(this).addClass('is-invalid'); // Add Bootstrap class for invalid input
            } else {
                $(this).removeClass('is-invalid'); // Remove Bootstrap class for valid input
            }
        });
        if (!bedsValid) {
            isValid = false;
            errorMessage += 'All bed numbers must be greater than zero.<br>';
        }

        // Validate Gender for each room
        let allRoomsValid = true;
        $('.room-item').each(function() {
            let genderSelected = $(this).find('input[name$="[gender]"]:checked').val();
            if (!genderSelected) {
                allRoomsValid = false;
                $(this).find('input[name$="[gender]"]').addClass('is-invalid');
            } else {
                $(this).find('input[name$="[gender]"]').removeClass('is-invalid');
            }
        });
        if (!allRoomsValid) {
            isValid = false;
            errorMessage += 'Gender must be selected for each room.<br>';
        }

        // Validate Eligible Gender
        const genderChecked = $('input[name="gender[]"]:checked').length > 0;
        if (!genderChecked) {
            isValid = false;
            $('#genderError').text('Please select at least one gender.').show();



            $('#error-toast .toast-body').text('Please select at least one gender.');


        } else {
            $('#genderError').hide();
        }

        // Validate Eligible Students
        const eligibilityChecked = $('input[name="eligibility[]"]:checked').length > 0;
        if (!eligibilityChecked) {
            isValid = false;
            $('#eligibilityError').text('Please select at least one eligibility option.').show();
        } else {
            $('#eligibilityError').hide();
        }

        if (!isValid) {
            // Show error toast
            $('#error-toast .toast-body').html(errorMessage);
            $('#error-toast').toast('show');
            // Re-enable the button and hide the overlay
            $('#update_button').prop('disabled', false);
            $('#overlay').fadeOut();
            return; // Stop form submission
        }

        // If form is valid, proceed with AJAX submission
        $('#update_button').prop('disabled', true);
        $('#overlay').css('display', 'flex');
        var form = $(this);
        // Create a FormData object
        var formData = new FormData(form[0]);
        // Append removed rooms to FormData
        removedRooms.forEach(roomNumber => {
            formData.append('removed_rooms[]', roomNumber);
        });
        // Append all existing and new room numbers to FormData
        $('input[name^="rooms["][name$="[room_number]"]').each(function() {
            const roomNumber = $(this).val();
            const numberOfBeds = $(this).closest('.room-item').find('input[name$="[number_of_beds]"]').val() || '';
            const gender = $(this).closest('.room-item').find('input[name$="[gender]"]:checked').val() || '';
            formData.append(`rooms[${roomNumber}][room_number]`, roomNumber);
            formData.append(`rooms[${roomNumber}][number_of_beds]`, numberOfBeds);
            formData.append(`rooms[${roomNumber}][gender]`, gender);
        });

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                floorAction('update', {{ $floor->id }});

                $('#success-message').hide();
                $('#success-toast .toast-body').text(response.message || 'Update successfully');
                $('#success-toast').toast('show');
                $('#update_button').prop('disabled', false);
                $('#overlay').fadeOut();
                removedRooms = [];
            },
            error: function(xhr) {
    // Hide success message and show error toast
    $('#success-message').hide();
    $('#error-toast .toast-body').text('An error occurred while updating.');
    $('#error-toast').toast('show');

    // Log detailed error information
    console.log('AJAX error response:', xhr);
    console.log('Status:', xhr.status);
    console.log('Status Text:', xhr.statusText);
    console.log('Response Text:', xhr.responseText);
    console.log('Response JSON:', xhr.responseJSON); // If the response is JSON

    // Enable the button and hide the overlay
    $('#update_button').prop('disabled', false);
    $('#overlay').fadeOut();
}

        });
    });
});




    function floorAction(action, floorId) {
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
                url = `{{ url('floor/add') }}/${floorId}`;
                break;
            case 'update':
                url = `{{ url('floor/update') }}/${floorId}`;
                break;
            case 'delete':
                url = `{{ url('floor/delete') }}/${floorId}`;
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
    $(document).ready(function() {
    // Function to handle the floor deletion
    window.deleteFloor = function(event, floorId) {
        event.preventDefault(); // Prevent default action

        // Show the overlay
        $('#overlay').css('display', 'flex');

        // Disable the button to prevent multiple submissions
        $('#deletefloor .btn-outline-success').prop('disabled', true);

        // Get the CSRF token from the meta tag in the HTML
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Make the AJAX request to delete the floor
        $.ajax({
            url: '/floors/' + floorId, // Dynamic URL for the AJAX request
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken // Include the CSRF token in the request headers
            },
            success: function(response) {
                // Show success toast with the message from the server
                showToast('success-toast', response.message || 'Floor and associated rooms and beds deleted successfully.');

                // Call the function to close the modal and execute related actions
                closeModalAndExecuteHostel();
                $('#overlay').fadeOut(); // Hide the overlay
            },
            error: function(xhr) {
                // Get error message from server response
                var errorMessage = 'An error occurred while deleting the floor.';

                // If the server provides a specific error message, use that
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                // Show error toast with the message from the server
                showToast('error-toast', errorMessage);
            },
            complete: function() {
                // Always hide the overlay and re-enable the button
                $('#overlay').fadeOut('fast', function() {
                    $('#deletefloor .btn-outline-success').prop('disabled', false);
                });
            }
        });
    };

    // Function to show toast notifications
    function showToast(toastId, message) {
        var toastElement = $('#' + toastId);
        toastElement.find('.toast-body').text(message);
        toastElement.toast('show');
    }

    // Function to close the modal and execute any further actions
    function closeModalAndExecuteHostel() {
        // Close the modal
        $('#deletefloor').modal('hide'); // Use the correct ID of your modal

        // Ensure that room() is called after the modal is closed
        $('#deletefloor').on('hidden.bs.modal', function() {
            room({{ $blockId }}); // Replace room() with the actual function you want to call
        });
    }
});

</script>
