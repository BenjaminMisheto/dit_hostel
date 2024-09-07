<div class="content">


    <div class="py-4 px-3 px-md-4">
        <div class="mb-3 mb-md-4 d-flex justify-content-between">
            <div class="h3 mb-0">Hostel</div>
        </div>
        {{-- @if($blocks->isNotEmpty())
        @if($discrepanciesFound)
        <div class="alert alert-warning">
            <strong>Attention:</strong> Discrepancies in bed counts have been detected. Please review and resolve them by clicking the button below to submit the updated data and proceed.

        </div>
    @else
        <div class="alert alert-success">
            <strong>Success:</strong> All bed counts are accurate. The algorithm is functioning correctly.

        </div>
    @endif --}}
        <div class="row">
            <div class="col-md-6 col-xl-4 mb-3 mb-xl-4">
                <!-- Widget -->
                <div class="card flex-row align-items-center p-3 p-md-4" data-toggle="modal" data-target="#createmodal"
                    style="  cursor: pointer; ">

                    <div>

                        <h6 class="mb-0">Create new</h6>
                    </div>
                    <i class="gd-plus icon-text d-flex  ml-auto"></i>
                </div>
                <!-- End Widget -->
            </div>

        </div>

        <div class="row">
            @if($blocks->isEmpty())
            <div class="col-12">
                <!-- Card -->
                <div class="card">
                    <div class="card-body">
                        <div class="text-center alert alert-warning">
                            <small class="card-title">No block available</small>
                        </div>
                    </div>
                </div>
            </div>
            @else



            @foreach($blocks as $block)
               <!-- Initialize a flag to indicate missing gender -->
    @php
    $hasMissingGender = false;

    // Check each floor in the block
    foreach ($block->floors as $floor) {
        // Check each room in the floor
        foreach ($floor->rooms as $room) {
            // Check if room gender is empty or null
            if (is_null($room->gender) || trim($room->gender) === '') {
                $hasMissingGender = true;
                break 2; // Break out of both loops
            }
        }
    }
@endphp
            <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-3 mb-md-4">

                <!-- Card -->
                <a href="#" onclick="room({{ $block->id }})">
                    <div class="card">

            @if($hasMissingGender)
            <div class="col-12  alert alert-warning">
                <strong>Attention:</strong> Rooms in this block have missing gender information. The operation for this block is disabled.


            </div>
            @else
            <div class="card-body">
                <div class="row text-dark">
                    <div class="col-6">
                        <small class="card-title">{{ $block->name }}</small>
                    </div>
                    <div class="col-6">
                        <small class="">TZS {{ number_format($block->price, 2, '.', ',') }}</small>
                    </div>
                    <div class="col-6">
                        <small class="card-title">Elligable Gender</small>
                    </div>
                    <div class="col-6">
                        <small class="card-title">{{ implode(', ', $blockGenders[$block->id] ?? []) }}</small>
                    </div>



                </div>
            </div>
        @endif

                        <img src="{{ $block->image_data }}" class="card-img-top " alt="Block Image">
                    </div>
                </a>

                <!-- End Card -->
                <div class="row mt-3">
                    <div class="col-4 d-flex justify-content-start">
                        <i class="gd-trash text-danger btn border shadow-sm mx-2" style="cursor: pointer" data-toggle="modal"
                           data-target="#deleteBlock{{ $block->id }}" title="Delete Block {{ $block->name }}"></i>
                    </div>
                    <div class="col-4 d-flex justify-content-start">

                           <a href="#" class="text-dark btn border shadow-sm mx-2" title="Update Block {{$block->name}}" onclick="room({{ $block->id }})">
                            <i class="gd-pencil"></i>
                        </a>
                    </div>
                    <div class="col-4 d-flex justify-content-center align-items-center">
                        @if($hasMissingGender)
                        <div class="custom-control custom-switch " title="on/off">
                            <input type="checkbox" class="custom-control-input status-switch" disabled>
                            <label class="custom-control-label" ></label>
                        </div>
                        @else
                        <div class="custom-control custom-switch" title="on/off">
                            <input type="checkbox" class="custom-control-input status-switch" id="customSwitch{{ $block->id }}" data-block-id="{{ $block->id }}" {{ $block->status ? 'checked' : '' }}>
                            <label class="custom-control-label" for="customSwitch{{ $block->id }}"></label>
                        </div>
                        @endif

                    </div>

                    <div class="col-4 d-flex justify-content-end">

                        <!-- You can add more content here if needed -->
                    </div>
                </div>

            </div>
            @endforeach
            @endif
        </div>


    </div>

</div>



<script>
    $(document).ready(function() {
        // Add event listeners to all switches
        @foreach($blocks as $block)
        $('#customSwitch{{ $block->id }}').on('change', function() {
            var blockId = {{ $block->id }};
            var status = this.checked ? 1 : 0;
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: '/blocks/update-status/' + blockId,
                type: 'PUT',
                data: {
                    _token: csrfToken,
                    status: status
                },
                success: function(response) {
                    if (response.success) {
                        var message = status === 1 ? 'Block activated successfully!' : 'Block deactivated successfully!';
                        showToast('success', message);
                    } else {
                        showToast('error', 'Failed to update status for block ' + blockId);
                    }
                },
                error: function(xhr) {
                    showToast('error', 'An error occurred while updating status for block ' + blockId);
                }
            });
        });

        // Function to display Toast notifications
        function showToast(type, message) {
            var $toast = type === 'success' ? $('#success-toast') : $('#error-toast');
            $toast.find('.toast-body').text(message); // Set the message
            $toast.toast({ delay: 3000 }).toast('show'); // Show the toast and set delay
        }
        @endforeach
    });
</script>



@if($blocks->isEmpty())
@else
@foreach($blocks as $block)
<!-- Modal -->
<div id="deleteBlock{{ $block->id }}" class="modal fade" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog rounded" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="text-center rounded">
                    <i class="gd-alert icon-text icon-text-xxl d-block text-danger mb-3 mb-md-4"></i>
                    <div class="h5 font-weight-semi-bold mb-2">Delete Block {{ $block->name }} </div>
                    <p class="mb-3 mb-md-4">Deleting this Block will also remove all associated floors, rooms, and beds.
                    </p>
                    <div class="d-flex justify-content-between mb-4">
                        <a class="btn btn-outline-success" href="#"
                            onclick="deleteBlock(event, {{ $block->id }})">Yes</a>
                        <a class="btn btn-outline-danger" href="#" data-dismiss="modal">No</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- End Modal -->
@endforeach

@endif


<script>
    $(document).ready(function() {
        // Function to handle the block deletion
        window.deleteBlock = function(event, blockId) {
            event.preventDefault(); // Prevent default action
            // Show the overlay
            $('#overlay').css('display', 'flex');
            // Disable the button to prevent multiple submissions
            $('#deleteblock .btn-outline-success').prop('disabled', true);
            // Get the CSRF token from the meta tag in the HTML
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            // Make the AJAX request to delete the block
            $.ajax({
                url: '/blocks/' + blockId, // Dynamic URL for the AJAX request
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Include the CSRF token in the request headers
                },
                success: function(response) {
                    // Show success toast
                    showToast('success-toast',
                        'Block and associated data deleted successfully.');
                    @if($blocks->isEmpty())
                    @else
                    @foreach($blocks as $block)

                    closeModalAndExecuteHostel_{{$block->id}}();
                    @endforeach
                    @endif
                    $('#overlay').fadeOut(); // Hide the overlay
                },
                error: function(xhr) {
                    // Show error toast
                    showToast('error-toast', 'An error occurred while deleting the block.');
                },
                complete: function() {
                    // Always hide the overlay and re-enable the button
                    $('#overlay').fadeOut('fast', function() {
                        $('#deleteblock .btn-outline-success').prop('disabled', false);
                    });
                }
            });
        };

        function showToast(toastId, message) {
            var toastElement = $('#' + toastId);
            toastElement.find('.toast-body').text(message);
            toastElement.toast('show');
        }
        @if($blocks->isEmpty())
        @else
        @foreach($blocks as $block)

        function  closeModalAndExecuteHostel_{{$block->id}}() {

            // Close the modal
            $('#deleteBlock{{ $block->id }}').modal('hide'); // Use the correct ID of your modal
            // Ensure that hostel() is called after the modal is closed
            $('#deleteBlock{{ $block->id }}').on('hidden.bs.modal', function() {

                // Adjust this if you need to call a specific function or update the page
                hostel();
            });
        }
        @endforeach
        @endif
    });
</script>

<!-- Modal HTML -->
<div id="createmodal" class="modal fade" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">New Block</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container text-center">
                    <div class="d-flex justify-content-center align-items-center mb-4">
                        <div id="imagePlaceholder" style="
                            width: 400px;
                            height: 450px;
                            background-image: url('img/placeholder.jpg');
                            background-size: cover;
                            background-position: center;

                            position: relative; /* Required for stacking context */
                        ">
                            <input type="file" id="imageInput" style="display: none;" accept="image/*">
                        </div>
                    </div>

                    <div class="text-center">
                        <button id="uploadButton" class="btn btn-default">Upload Image</button>
                    </div>
                </div>

                <div class="progress mb-3 mt-5">
                    <div id="progressBar" class="progress-bar bg-success" role="progressbar" style="width: 25%;"
                        aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <input type="file" id="imageInput" accept="image/*" hidden>
                <form id="blockForm">
                    <!-- Step 1 -->
                    <div id="step1">
                        <div class="form-group">
                            <label for="blockName">Block Name</label>
                            <input type="text" class="form-control" id="blockName">
                            <small id="blockNameError" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="blockManager">Block Manager</label>
                            <input type="text" class="form-control" id="blockManager">
                            <small id="blockManagerError" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="numFloors">Number of Floors</label>
                            <input type="number" class="form-control" id="numFloors">
                            <small id="numFloorsError" class="form-text text-danger"></small>
                        </div>

                        <div class="form-group">
                            <label for="location">Location</label>
                            <input type="text" class="form-control" id="location" name="location"
                                value="{{ old('location') }}">
                            <small id="locationError"
                                class="form-text text-danger">{{ $errors->first('location') }}</small>
                        </div>

                        <div class="form-group">
                            <label for="blockPrice">Block Price</label>
                            <input type="number" class="form-control" id="blockPrice">
                            <small id="blockPriceError" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label>Gender</label>
                            <div class="btn-group-toggle d-flex justify-content-between" data-toggle="buttons">
                                <label class="btn btn-outline-primary flex-fill mx-1" style="cursor: pointer;">
                                    <input type="checkbox" name="gender" value="male"> Male
                                </label>
                                <label class="btn btn-outline-primary flex-fill mx-1" style="cursor: pointer;">
                                    <input type="checkbox" name="gender" value="female"> Female
                                </label>
                            </div>
                            <small id="genderError" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label>Eligibility</label>
                            <div class="btn-group-toggle d-flex justify-content-between" data-toggle="buttons">
                                <label class="btn btn-outline-info flex-fill mx-1" style="cursor: pointer;">
                                    <input type="checkbox" name="eligibility" value="diploma"> Diploma
                                </label>
                                <label class="btn btn-outline-primary flex-fill mx-1" style="cursor: pointer;">
                                    <input type="checkbox" name="eligibility" value="bachelor"> Bachelor
                                </label>
                            </div>
                            <small id="eligibilityError" class="form-text text-danger"></small>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="nextButton"
                                onclick="goToStep2()">Next</button>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div id="step2" style="display:none;">
                        <!-- Navigation Button at the Top -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="goToStep1()">Back to Step
                                1</button>
                            <button type="button" class="btn btn-primary" id="nextButton"
                                onclick="goToStep3()">Next</button>
                        </div>

                        <div class="form-group mt-4">
                            <label for="setAllRooms">Set Number of Rooms for All Floors:</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="setAllRooms"
                                    placeholder="Enter number of rooms">
                                <div class="input-group mt-2">
                                    <button class="btn btn-primary" type="button" onclick="setRoomsForAllFloors()">Set
                                        for All Floors</button>
                                </div>
                            </div>
                            <small class="form-text text-danger" id="setAllRoomsError"></small>
                        </div>

                        <div id="floorInputs"></div>

                        <!-- Navigation Button at the Bottom -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="goToStep1()">Back to Step
                                1</button>
                            <button type="button" class="btn btn-primary" id="nextButton"
                                onclick="goToStep3()">Next</button>
                        </div>

                    </div>

                    <!-- Step 3 HTML -->
                    <div id="step3" style="display: none;">
                        <!-- Navigation Button at the Top -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="goToStep2()">Back to Step
                                2</button>
                            <button type="button" class="btn btn-primary" id="nextButton"
                                onclick="goToStep4()">Next</button>
                        </div>

                        <div id="roomDetails">
                            <!-- Input for setting beds for all rooms -->
                            <div class="form-group">
                                <label for="setAllBeds">Set Number of Beds for All Rooms:</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="number" class="form-control" id="setAllBeds" min="1">
                                        <small class="form-text text-danger" id="setAllBedsError"></small>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-primary mt-2"
                                            onclick="setBedsForAllRooms()">Apply to All Rooms</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <small class="form-text text-danger" id="bedCountError"></small>

                        <!-- Navigation Button at the Bottom -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="goToStep2()">Back to Step
                                2</button>
                            <button type="button" class="btn btn-primary" id="nextButton"
                                onclick="goToStep4()">Next</button>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div id="step4" style="display:none;">
                        <!-- Eligibility for Each Floor -->
                        <div class="form-group">
                            <label>Eligibility for Each Floor</label>
                            <div id="floorEligibilityInputs"></div>
                            <small id="floorEligibilityError" class="form-text text-danger"></small>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="goToStep3()">Back to Step
                                3</button>
                            <button type="button" class="btn btn-primary" id="nextButton"
                                onclick="goToStep5()">Next</button>
                        </div>
                    </div>

                    <!-- Step 5 -->
                    <div id="step5" style="display:none;">
                        <!-- Block Details -->
                        <div class="mb-4">
                            <h5 class="mb-3 "><b>Block Details</b></h5>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Block Name:</strong> <span id="confirmBlockName"></span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Block Manager:</strong> <span id="confirmBlockManager"></span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Number of Floors:</strong> <span id="confirmNumFloors"></span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Gender:</strong> <span id="confirmGender"></span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Eligibility:</strong> <span id="confirmEligibility"></span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Block Price:</strong> <span id="confirmBlockPrice"></span>
                                </div>

                            </div>
                        </div>

                        <!-- Floor Details -->
                        <div class="mb-4">
                            <h5 class="mb-3 "><b>Floor Details</b></h5>
                            <div id="confirmFloorDetails" class="row">
                                <!-- Floor details will be populated here -->
                            </div>
                        </div>

                        <!-- Eligible Students -->
                        <div class="mb-4">
                            <h5 class="mb-3 "><b>Eligible Students</b></h5>
                            <div id="confirmEligibleStudents" class="row">
                                <!-- Eligible students will be populated here -->
                            </div>
                        </div>

                        <!-- Total Number of Beds -->
                        <div class="mb-4 bold">
                            <h5 class="mb-3">Total Number of Beds</h5>

                            <span id="confirmTotalBeds"></span>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="goToStep4()">Back to Step
                                4</button>
                            <button type="button" class="btn btn-primary" id="submitButton"
                                onclick="submitForm(event)">Submit</button>

                        </div>

                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function setRoomsForAllFloors() {
        let numRooms = $('#setAllRooms').val().trim();
        // Clear previous errors
        $('#setAllRoomsError').text('');
        if (numRooms === '' || numRooms <= 0) {
            $('#setAllRoomsError').text('Please enter a valid number of rooms.');
            return;
        }
        $('#floorInputs input[id^="numRoomsFloor"]').each(function() {
            $(this).val(numRooms);
        });
    }

    function goToStep1() {
        $('#step1').show();
        $('#step2').hide();
        $('#step3').hide();
        $('#step4').hide();
        $('#nextButton').show();
        $('#progressBar').css('width', '25%').attr('aria-valuenow', 25);
    }

    function goToStep2() {
        let valid = true;
        // Clear previous errors
        $('.form-text.text-danger').text('');
        // Validate Step 1 fields
        if ($('#blockName').val().trim() === '') {
            $('#blockNameError').text('Block Name is required.');
            valid = false;
        }
        if ($('#blockManager').val().trim() === '') {
            $('#blockManagerError').text('Block Manager is required.');
            valid = false;
        }
        if ($('#numFloors').val().trim() === '') {
            $('#numFloorsError').text('Number of Floors is required.');
            valid = false;
        }
        if ($('#blockPrice').val().trim() === '') {
            $('#blockPriceError').text('Block Price is required.');
            valid = false;
        }
        if ($('input[name="gender"]:checked').length === 0) {
            $('#genderError').text('Please select at least one gender.');
            valid = false;
        }
        if ($('input[name="eligibility"]:checked').length === 0) {
            $('#eligibilityError').text('Please select at least one eligibility criterion.');
            valid = false;
        }
        if ($('#location').val().trim() === '') {
            $('#locationError').text('location Name is required.');
            valid = false;
        }
        if (valid) {
            let numFloors = $('#numFloors').val();
            window.selectedGender = $('input[name="gender"]:checked').map(function() {
                return $(this).val();
            }).get();
            window.selectedEligibility = $('input[name="eligibility"]:checked').map(function() {
                return $(this).val();
            }).get();
            window.floorGender = {}; // Initialize empty object for floor genders
            let floorInputs = $('#floorInputs');
            floorInputs.empty();
            for (let i = 1; i <= numFloors; i++) {
                // Initialize floorGender for each floor
                window.floorGender[i] = [];
                let genderOptions = window.selectedGender.map(gender =>
                    `<label class="btn btn-outline-primary me-2 mb-2 flex-grow-1" style="cursor: pointer;">
                    <input type="checkbox" name="floorGender${i}" value="${gender}" autocomplete="off">
                    ${gender.charAt(0).toUpperCase() + gender.slice(1)}
                </label>`
                ).join('');
                floorInputs.append(
                    `<div class="form-group" id="floor${i}Input">
                    <label for="numRoomsFloor${i}">Number of Rooms on Floor ${i}</label>
                    <input type="number" class="form-control" id="numRoomsFloor${i}">
                    <small class="form-text text-danger" id="numRoomsFloor${i}Error"></small>
                    <label>Gender for Floor ${i}</label>
                    <div class="btn-group-toggle d-flex flex-wrap" data-toggle="buttons">
                        ${genderOptions}
                    </div>
                    <small class="form-text text-danger" id="floorGender${i}Error"></small>
                </div>`
                );
            }
            // Automatically check all checkboxes if only one gender is selected
            if (window.selectedGender.length === 1) {
                $(`#floorInputs input[value="${window.selectedGender[0]}"]`).prop('checked', true);
            }
            $('#step1').hide();
            $('#step2').show();
            $('#step3').hide();
            $('#step4').hide();
            $('#nextButton').show();
            $('#progressBar').css('width', '50%').attr('aria-valuenow', 50);
        }
    }

    function goToStep3() {
        if (validateStep2()) {
            $('#step1').hide();
            $('#step2').hide();
            $('#step3').show();
            $('#step4').hide();
            $('#nextButton').hide();
            $('#progressBar').css('width', '75%').attr('aria-valuenow', 75);
            generateRoomInputs();
        }
    }

    function validateStep2() {
        let valid = true;
        // Clear previous errors
        $('#floorInputs .form-group').each(function() {
            $(this).find('.form-text.text-danger').text('');
        });
        $('#floorInputs .form-group').each(function() {
            let numRooms = $(this).find('input[type="number"]').val().trim();
            let genderSelected = $(this).find('input[type="checkbox"]:checked').length;
            // Validate number of rooms
            if (numRooms === '' || numRooms <= 0) {
                $(this).find('.form-text.text-danger').first().text(
                    'Number of rooms is required and must be greater than 0.');
                valid = false;
            }
            // Validate selected gender
            if (genderSelected === 0) {
                $(this).find('.form-text.text-danger').last().text(
                    'Please select at least one gender for this floor.');
                valid = false;
            }
        });
        return valid;
    }

    function generateRoomInputs() {
        $('#roomDetails').empty();
        let numFloors = $('#numFloors').val();
        $('#roomDetails').append('<h5>Enter the number of beds for each room on each floor:</h5>');
        // Add input field for setting beds for all rooms
        $('#roomDetails').append(`
        <div class="form-group">
            <label for="setAllBeds">Set Number of Beds for All Rooms:</label>
            <input type="number" class="form-control" id="setAllBeds" min="1">
            <small class="form-text text-danger" id="setAllBedsError"></small>
            <button type="button" class="btn btn-primary mt-2" onclick="setBedsForAllRooms()">Apply to All Rooms</button>
        </div>
    `);
        for (let i = 1; i <= numFloors; i++) {
            let numRooms = $('#numRoomsFloor' + i).val();
            $('#roomDetails').append(`<div class="floor-room-details">
            <h6>Floor ${i}</h6>
            <div id="floor${i}Rooms"></div>
        </div>`);
            let floorRoomsContainer = $(`#floor${i}Rooms`);
            for (let j = 1; j <= numRooms; j++) {
                floorRoomsContainer.append(`<div class="form-group">
                <label for="bedCountFloor${i}Room${j}">Number of Beds in Room ${j}:</label>
                <input type="number" class="form-control" id="bedCountFloor${i}Room${j}" min="1" required>
                <small class="form-text text-danger" id="bedCountFloor${i}Room${j}Error"></small>
            </div>`);
            }
        }
    }

    function setBedsForAllRooms() {
        let beds = $('#setAllBeds').val().trim();
        $('#setAllBedsError').text('');
        if (beds === '' || beds <= 0) {
            $('#setAllBedsError').text('Please enter a valid number of beds.');
            return;
        }
        // Apply the number of beds to all rooms on all floors
        $('#roomDetails .floor-room-details').each(function() {
            $(this).find('input[type="number"]').val(beds);
        });
    }

    function goToStep4() {
        if (validateStep3()) {
            $('#step1').hide();
            $('#step2').hide();
            $('#step3').hide();
            $('#step4').show();
            $('#step5').hide();
            $('#nextButton').hide();
            $('#progressBar').css('width', '100%').attr('aria-valuenow', 100);
            generateFloorEligibilityInputs();
        }
    }

    function validateStep3() {
        let valid = true;
        $('#roomDetails .form-group').each(function() {
            let value = $(this).find('input').val();
            let id = $(this).find('input').attr('id');
            if (value === '' || value <= 0) {
                $(`#${id}Error`).text('Please enter a valid number of beds.');
                valid = false;
            } else {
                $(`#${id}Error`).text(''); // Clear previous error message
            }
        });
        return valid;
    }

    function generateFloorEligibilityInputs() {
        $('#floorEligibilityInputs').empty();
        let numFloors = $('#numFloors').val();
        let selectedEligibility = $('input[name="eligibility"]:checked').map(function() {
            return $(this).val();
        }).get();
        // Clear previous stored eligibility data
        window.floorEligibility = {};
        for (let i = 1; i <= numFloors; i++) {
            let eligibilityOptions = '';
            if (selectedEligibility.includes('diploma')) {
                eligibilityOptions +=
                    `<label class="btn btn-outline-info me-2 mb-2 flex-grow-1" style="cursor: pointer;">
                    <input type="checkbox" name="floorEligibility${i}" value="D1" autocomplete="off"> D1
                </label>
                <label class="btn btn-outline-info me-2 mb-2 flex-grow-1" style="cursor: pointer;">
                    <input type="checkbox" name="floorEligibility${i}" value="D2" autocomplete="off"> D2
                </label>
                <label class="btn btn-outline-info me-2 mb-2 flex-grow-1" style="cursor: pointer;">
                    <input type="checkbox" name="floorEligibility${i}" value="D3" autocomplete="off"> D3
                </label>`;
            }
            if (selectedEligibility.includes('bachelor')) {
                eligibilityOptions +=
                    `<label class="btn btn-outline-primary me-2 mb-2 flex-grow-1" style="cursor: pointer;">
                    <input type="checkbox" name="floorEligibility${i}" value="B1" autocomplete="off"> B1
                </label>
                <label class="btn btn-outline-primary me-2 mb-2 flex-grow-1" style="cursor: pointer;">
                    <input type="checkbox" name="floorEligibility${i}" value="B2" autocomplete="off"> B2
                </label>
                <label class="btn btn-outline-primary me-2 mb-2 flex-grow-1" style="cursor: pointer;">
                    <input type="checkbox" name="floorEligibility${i}" value="B3" autocomplete="off"> B3
                </label>
                <label class="btn btn-outline-primary me-2 mb-2 flex-grow-1" style="cursor: pointer;">
                    <input type="checkbox" name="floorEligibility${i}" value="B4" autocomplete="off"> B4
                </label>`;
            }
            $('#floorEligibilityInputs').append(
                `<div class="form-group">
                <label>Eligibility for Floor ${i}</label>
                <div class="btn-group-toggle d-flex flex-wrap" data-toggle="buttons">
                    ${eligibilityOptions}
                </div>
            </div>`
            );
            // Initialize stored eligibility data for each floor
            window.floorEligibility[i] = [];
        }
        // Attach event listeners to checkboxes to update stored data
        $('#floorEligibilityInputs').on('change', 'input[type="checkbox"]', function() {
            let floorNumber = $(this).attr('name').replace('floorEligibility', '');
            if (this.checked) {
                if (!window.floorEligibility[floorNumber].includes($(this).val())) {
                    window.floorEligibility[floorNumber].push($(this).val());
                }
            } else {
                window.floorEligibility[floorNumber] = window.floorEligibility[floorNumber].filter(value =>
                    value !== $(this).val());
            }
        });
    }

    function validateStep4() {
        let valid = true;
        let allChecked = true;
        $('#floorEligibilityInputs .form-group').each(function() {
            let checked = $(this).find('input[type="checkbox"]:checked').length > 0;
            if (!checked) {
                allChecked = false;
            }
        });
        if (!allChecked) {
            $('#floorEligibilityError').text('Please select at least one eligibility option for each floor.');
            valid = false;
        } else {
            $('#floorEligibilityError').text('');
        }
        return valid;
    }

    function goToStep5() {
        if (validateStep4()) {
            $('#step1').hide();
            $('#step2').hide();
            $('#step3').hide();
            $('#step4').hide();
            $('#step5').show();
            //$('#submitButton').hide();
            $('#progressBar').css('width', '100%').attr('aria-valuenow', 100);
            populateSummary();
        }
    }

    function populateSummary() {
        // Populate block details
        $('#confirmBlockName').text($('#blockName').val());
        $('#confirmBlockManager').text($('#blockManager').val());
        $('#confirmNumFloors').text($('#numFloors').val());
        $('#confirmGender').text(window.selectedGender.join(', '));
        $('#confirmEligibility').text(window.selectedEligibility.join(', '));
        $('#confirmBlockPrice').text($('#blockPrice').val());
        // Populate floor details
        $('#confirmFloorDetails').empty();
        let numFloors = $('#numFloors').val();
        let totalBeds = 0;
        for (let i = 1; i <= numFloors; i++) {
            let numRooms = $(`#numRoomsFloor${i}`).val();
            let floorDetails = `<div class="col-md-6 mb-3">
            <h6>Floor ${i}</h6>
            <p><strong>Number of Rooms:</strong> ${numRooms}</p>
            <p><strong>Rooms:</strong></p>`;
            for (let j = 1; j <= numRooms; j++) {
                let numBeds = $(`#bedCountFloor${i}Room${j}`).val();
                floorDetails += `<p>Room ${j}: ${numBeds} beds</p>`;
                totalBeds += parseInt(numBeds); // Calculate total number of beds
            }
            floorDetails += `</div>`;
            $('#confirmFloorDetails').append(floorDetails);
        }
        // Populate eligible students
        $('#confirmEligibleStudents').empty();
        for (let i = 1; i <= numFloors; i++) {
            let eligibleStudents = [];
            if (window.floorEligibility[i]) {
                eligibleStudents = Array.from(new Set(window.floorEligibility[i])); // Remove duplicates
            }
            let studentsHtml = `<div class="col-md-6 mb-3">
            <h6>Floor ${i} Eligible Students</h6>
            <p>${eligibleStudents.join(', ')}</p>
        </div>`;
            $('#confirmEligibleStudents').append(studentsHtml);
        }
        // Populate total number of beds
        $('#confirmTotalBeds').text(totalBeds);
    }
    // Event listener to remove errors when user interacts with inputs
    $(document).on('input change', '#floorInputs input[type="number"]', function() {
        let floorNum = $(this).attr('id').replace('numRoomsFloor', '');
        if ($(this).val() > 0) {
            $(`#numRoomsFloor${floorNum}Error`).text('');
        }
    });
    $(document).on('change', '#floorInputs input[type="checkbox"]', function() {
        let floorNum = $(this).closest('.form-group').index() + 1;
        if ($('#floorInputs .form-group').eq(floorNum - 1).find('input[type="checkbox"]:checked').length > 0) {
            $(`#floorGender${floorNum}Error`).text('');
        }
    });
    $(document).ready(function() {
        var $imageInput = $('#imageInput');
        var $uploadButton = $('#uploadButton');
        var $imagePlaceholder = $('#imagePlaceholder');
        var croppieInstance;
        // Trigger file input click on upload button click
        $uploadButton.on('click', function() {
            $imageInput.trigger('click');
        });
        // Handle file input change
        $imageInput.on('change', function(event) {
            if (event.target.files && event.target.files[0]) {
                var file = event.target.files[0];
                if (file.type.match('image.*')) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        // Destroy existing Croppie instance if it exists
                        if (croppieInstance) {
                            croppieInstance.destroy();
                            croppieInstance = null;
                        }
                        // Initialize Croppie instance
                        croppieInstance = new Croppie($imagePlaceholder[0], {
                            viewport: {
                                width: 400,
                                height: 400,
                                type: 'square'
                            },
                            boundary: {
                                width: 400,
                                height: 400
                            },
                            showZoomer: true,
                            enableOrientation: true
                        });
                        // Bind the image to Croppie
                        croppieInstance.bind({
                            url: e.target.result
                        });
                    };
                    reader.readAsDataURL(file);
                }
            } else {
                $imageInput.val('');
            }
        });
        // Form submit function
        window.submitForm = function(event) {
            if (event) {
                event.preventDefault(); // Prevent default form submission behavior
            }
            var imagePromise = croppieInstance ? croppieInstance.result('base64') : Promise.resolve(null);
            imagePromise.then(function(image) {
                $('#submitButton').prop('disabled', true);
                $('#overlay').css('display', 'flex');
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                var formData = {
                    blockName: $('#blockName').val(),
                    blocklocation: $('#location').val(),
                    blockManager: $('#blockManager').val(),
                    numFloors: $('#numFloors').val(),
                    blockPrice: parseFloat($('#blockPrice')
                        .val()), // Convert blockPrice to a number
                    gender: $('input[name="gender"]:checked').map(function() {
                        return $(this).val();
                    }).get(),
                    eligibility: $('input[name="eligibility"]:checked').map(function() {
                        return $(this).val();
                    }).get(),
                    floors: [], // Initialize floors array
                    image: image // Add base64 image data, might be null or invalid
                };
                // Collect eligibility data
                $('#floorEligibilityInputs .form-group').each(function(index) {
                    var eligibilitySelected = window.floorEligibility[index + 1] || [];
                    formData.eligibility.push(eligibilitySelected);
                });
                // Collect floor data
                $('#floorInputs .form-group').each(function(index) {
                    var numRooms = $(this).find('input[type="number"]').val();
                    var genderSelected = $(this).find('input[name^="floorGender"]:checked')
                        .map(function() {
                            return $(this).val();
                        }).get();
                    var eligibilitySelected = window.floorEligibility[index + 1] || [];
                    var roomsData = [];
                    for (let j = 1; j <= numRooms; j++) {
                        var bedCount = $(`#bedCountFloor${index + 1}Room${j}`)
                            .val(); // Adjusted to capture bed count per room
                        if (bedCount) { // Ensure bed count is present
                            roomsData.push({
                                roomNumber: j, // Room number (1, 2, 3, etc.)
                                bedCount: parseInt(bedCount,
                                    10) // Convert bedCount to an integer
                            });
                        }
                    }
                    formData.floors.push({
                        number_of_rooms: numRooms,
                        gender: genderSelected, // Include selected gender options for this floor
                        eligibility: eligibilitySelected, // Include selected eligibility options for this floor
                        rooms: roomsData // Include rooms data with bed counts
                    });
                });
                console.log('Form Data to be sent to the backend:', formData);
                $.ajax({
                    url: '/hostel_create', // Adjust the URL to match your server endpoint
                    type: 'POST',
                    data: {
                        _token: csrfToken,
                        blocklocation: formData.blocklocation,
                        blockName: formData.blockName,
                        blockManager: formData.blockManager,
                        numFloors: formData.numFloors,
                        blockPrice: formData.blockPrice,
                        gender: formData.gender,
                        eligibility: formData.eligibility,
                        floors: formData.floors,
                        image: formData.image ||
                            null // Send null if the image is invalid or not provided
                    },
                    success: function(response) {
                        if (response.success) {
                            closeModalAndExecuteHostel();
                            $('#overlay')
                                .fadeOut(); // Close modal and call hostel function
                        } else {
                            alert(response.message);
                            $('#submitButton').prop('disabled',
                                false); // Re-enable the submit button
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        $('#submitButton').prop('disabled',
                            false); // Re-enable the submit button
                        alert('An error occurred while submitting the form.');
                        $('#overlay').fadeOut(); // Close modal and call hostel function
                    }
                });
            });
        };
    });

    function closeModalAndExecuteHostel() {
        // Close the modal
        $('#createmodal').modal('hide'); // Use the correct ID of your modal
        // Ensure that hostel() is called after the modal is closed
        $('#createmodal').on('hidden.bs.modal', function() {
            hostel();
        });
    }
</script>
