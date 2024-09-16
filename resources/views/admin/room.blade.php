@php
use Carbon\Carbon;
use App\Models\User;

@endphp

<div id="dash">


        <ul class="nav nav-v2 nav-primary nav-justified d-block d-xl-flex  container" role="tablist">
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center py-2 px-3 p-xl-4  active" href="#tabs1-tab1" role="tab" aria-selected="true"
                   data-toggle="tab">{{$block->name}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center py-2 px-3 p-xl-4 " href="#tabs1-tab2" role="tab" aria-selected="false"
                   data-toggle="tab">Manage General Items
                </a>
            </li>
        </ul>



        <div id="tabsContent1" class="card-body tab-content p-0">
            <div class="tab-pane fade show active" id="tabs1-tab1" role="tabpanel">
                @foreach($block->floors as $index => $floor)
                <!-- Modal -->
                <div id="deletefloor{{ $floor->id }}" class="modal fade" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog rounded" role="document">
                        <div class="modal-content">
                            <div class="modal-body text-center">
                                <div class="text-center rounded">
                                    <i class="gd-alert icon-text icon-text-xxl d-block text-danger mb-3 mb-md-4"></i>
                                    <div class="h5 font-weight-semi-bold mb-2">Delete Floor
                                        {{ $floor->floor_number }}</div>
                                    <p class="mb-3 mb-md-4">Deleting this floor will also remove all
                                        associated rooms and beds.</p>
                                    <div class="d-flex justify-content-between mb-4">
                                        <a class="btn btn-outline-success" href="#"
                                            onclick="deleteFloor(event, {{ $floor->id }})">Yes</a>
                                        <a class="btn btn-outline-danger" href="#" data-dismiss="modal">No</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Modal -->
                @endforeach




                <!-- Modal -->
            <div id="deleteBlock" class="modal fade" role="dialog" aria-labelledby="exampleModalLabel"
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

            <!-- Modal -->
            <div id="updateBlock" class="modal fade" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Update {{$block->name}}</h5>
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
                                        background-image: url('{{$block->image_data}}');
                                        background-size: cover;
                                        background-position: center;
                                        position: relative;">
                                        <input type="file" id="imageInput" style="display: none;" accept="image/*">
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button id="uploadButton" class="btn btn-default">Upload Image</button>
                                </div>
                            </div>

                            <input type="file" id="imageInput" accept="image/*" hidden>
                            <form id="blockForm" data-block-id="{{ $block->id }}">
                                <div id="step1">
                                    <div class="form-group">
                                        <label  for="blockName">Block Name</label>
                                        <input type="text" class="form-control" id="blockName" value="{{$block->name}}">
                                        <small id="blockNameError" class=" text-danger"></small>

                                    </div>
                                    <div class="form-group">
                                        <label for="blockManager">Block Manager</label>
                                        <input type="text" class="form-control" id="blockManager" value="{{$block->manager}}">
                                        <small id="blockManagerError" class=" text-danger"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="location">Location</label>
                                        <input type="text" class="form-control" id="location" name="location"
                                            value="{{$block->location}}">
                                        <small id="locationError" class=" text-danger"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="blockPrice">Block Price</label>
                                        <input type="number" class="form-control" id="blockPrice" value="{{$block->price}}">
                                        <small id="blockPriceError" class=" text-danger"></small>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" id="nextButton" onclick="updateblock()">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>



            <!-- End Modal -->

            <script>

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
                    $('#imagePlaceholder').css('background-image', 'none');
                    if (event.target.files && event.target.files[0]) {
                        var file = event.target.files[0];
                        if (file.type.match('image.*')) {
                            var reader = new FileReader();
                            reader.onload = function(e) {
                                if (croppieInstance) {
                                    croppieInstance.destroy();
                                    croppieInstance = null;
                                }
                                croppieInstance = new Croppie($imagePlaceholder[0], {
                                    viewport: { width: 400, height: 400, type: 'square' },
                                    boundary: { width: 400, height: 400 },
                                    showZoomer: true,
                                    enableOrientation: true
                                });
                                croppieInstance.bind({ url: e.target.result });


                            };
                            reader.readAsDataURL(file);
                        }
                    } else {
                        $imageInput.val('');
                    }
                });

                // Form submit function for updating the block with validation
                window.updateblock = function(event) {
                    if (event) {
                        event.preventDefault();
                    }

                    var isValid = true;

                    // Clear previous error messages
                    $('.form-text.text-danger').text('');

                    var blockName = $('#blockName').val().trim();
                    var blockLocation = $('#location').val().trim();
                    var blockManager = $('#blockManager').val().trim();
                    var blockPrice = $('#blockPrice').val().trim();

                    // Validation
                    if (!blockName) {

                        $('#blockNameError').text('Block Name is required.');

                        isValid = false;
                    }

                    if (!blockManager) {
                        $('#blockManagerError').text('Block manager are required.');
                        isValid = false;
                    }

                    if (!blockLocation) {
                        $('#locationError').text('Location is required.');
                        isValid = false;
                    }

                    if (blockPrice === '' || isNaN(blockPrice) || parseFloat(blockPrice) <= 0) {
                        $('#blockPriceError').text('Please enter a valid price.');
                        isValid = false;
                    }

                    if (!isValid) {
                        return; // Stop form submission if validation fails
                    }

                    var imagePromise = croppieInstance ? croppieInstance.result('base64') : Promise.resolve(null);

                    imagePromise.then(function(image) {
                        $('#submitButton').prop('disabled', true);
                        $('#overlay').css('display', 'flex');

                        var csrfToken = $('meta[name="csrf-token"]').attr('content');
                        var blockId = $('#blockForm').data('block-id');

                        var formData = {
                            blockName: blockName,
                            blocklocation: blockLocation,
                            blockManager: blockManager,
                            blockPrice: parseFloat(blockPrice),
                            image: image || null
                        };

                        $.ajax({
                            url: '/blocks/update/' + blockId,
                            type: 'PUT',
                            data: {
                                _token: csrfToken,
                                blocklocation: formData.blocklocation,
                                blockName: formData.blockName,
                                blockManager: formData.blockManager,
                                blockPrice: formData.blockPrice,
                                image: formData.image
                            },
                            success: function(response) {
                                if (response.success) {
                                    showToast('success', 'Block updated successfully!');
                                    closeModalAndExecuteHostel();
                                    $('#overlay').fadeOut();
                                } else {
                                    showToast('error', response.message);
                                    $('#submitButton').prop('disabled', false);
                                }
                            },
                            error: function(xhr) {
                                if (xhr.status === 422) {
                                    var errors = xhr.responseJSON.errors;
                                    for (var key in errors) {
                                        if (errors.hasOwnProperty(key)) {
                                            $('#' + key + 'Error').text(errors[key][0]);
                                        }
                                    }
                                } else {
                                    showToast('error', 'An error occurred while updating the block.');
                                }
                                $('#submitButton').prop('disabled', false);
                                $('#overlay').fadeOut();
                            }
                        });
                    });
                };

                function showToast(type, message) {
                    var $toast = type === 'success' ? $('#success-toast') : $('#error-toast');
                    $toast.find('.toast-body').text(message);
                    $toast.toast({ delay: 3000 }).toast('show');
                }

                function closeModalAndExecuteHostel() {
                    $('#updateBlock').modal('hide');
                    $('#updateBlock').on('hidden.bs.modal', function() {
                        room({{ $block->id }})
                    });
                }
            });


            </script>
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


                                closeModalAndExecuteHostel();

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


                    function  closeModalAndExecuteHostel() {

                        // Close the modal
                        $('#deleteBlock').modal('hide'); // Use the correct ID of your modal
                        // Ensure that hostel() is called after the modal is closed
                        $('#deleteBlock').on('hidden.bs.modal', function() {

                            // Adjust this if you need to call a specific function or update the page
                            hostel();
                        });
                    }

                });
            </script>
<style>
    .responsive-bg {
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        height: 300px; /* Default height for desktops */
        width: 100%;
    }

    @media (max-width: 768px) {
        .responsive-bg {
            height: 200px; /* Adjust height for mobile devices */
        }
    }

    @media (max-width: 576px) {
        .responsive-bg {
            height: 150px; /* Further adjustment for smaller devices */
        }
    }
</style>
                <div class="content py-4 px-3 px-md-4">
                    <div class="">
                        <div class="d-flex justify-content-between mb-4">


                            <button class="btn btn-outline-secondary" onclick="hostel()"><i class="gd-shift-left"></i> </button>
                            <button class="btn btn-outline-secondary" onclick="room({{ $block->id }})"> <i
                                    class="gd-loop "></i></button>


                                    <a href="#" class="text-dark btn border shadow-sm mx-2" title="Update Block {{$block->name}}" data-toggle="modal" data-target="#updateBlock">
                                    <i class="gd-pencil"></i>
                                </a>



                            <button class="btn btn-outline-secondary" data-toggle="modal" data-target="#createModal"
                                title="Add Floor">
                                <i class="gd-plus"></i>
                            </button>

                            <a href="#" class="text-danger btn border shadow-sm mx-2" title="Delete Block {{$block->name}}" data-toggle="modal"data-target="#deleteBlock">
                                <i class="gd-trash text-danger" style="cursor: pointer"  ></i>
                        </a>


                        </div>

                        <div class="row">
                            <!-- Image Column -->
                            <div class="col-xl-6 mb-3">
                                <div class="rounded-start"
                                     style="background-image: url('{{ $block->image_data }}');
                                           background-size: contain;
                                           background-repeat: no-repeat;
                                           background-position: center;
                                           height: 500px; /* Set a default height */
                                           width: 100%;">

                                    <!-- Additional content can go here -->

                                </div>
                            </div>


                            <!-- Add responsive styling using a media query for mobile -->




                            <!-- Block Details Column -->
                            <div class=" col-xl-6 mb-3">
                                <div class="row">
                                    <!-- Block Name -->
                                    <div class="col-12 col-lg-6 mb-3">
                                        <div class="card p-3">
                                            <div class="d-flex justify-content-between">
                                                <h6 class="mb-0">Block Name</h6>
                                                <h6 class="lh-1 mb-0">{{ $block->name }}</h6>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Eligible Gender -->
                                    <div class="col-12 col-lg-6 mb-3">
                                        <div class="card p-3">
                                            <div class="d-flex justify-content-between">
                                                <h6 class="mb-0">Eligible Gender</h6>
                                                <h6 class="lh-1 mb-0">{{ implode(', ', $blockGenders) }}</h6>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Eligible Students -->
                                    <div class="col-12 col-lg-6 mb-3">
                                        <div class="card p-3">
                                            <div class="d-flex justify-content-between">
                                                <h6 class="mb-0">Eligible Students</h6>
                                                <h6 class="lh-1 mb-0">{{ implode(', ', $blockEligibility) }}</h6>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Price/Annual -->
                                    <div class="col-12 col-lg-6 mb-3">
                                        <div class="card p-3">
                                            <div class="d-flex justify-content-between">
                                                <h6 class="mb-0">Price/Annual</h6>
                                                <h6 class="lh-1 mb-0">TZS {{ number_format($block->price, 2, ',', '.') }}</h6>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Block Manager -->
                                    <div class="col-12 col-lg-6 mb-3">
                                        <div class="card p-3">
                                            <div class="d-flex justify-content-between">
                                                <h6 class="mb-0">Block Manager</h6>
                                                <h6 class="lh-1 mb-0">{{ $block->manager }}</h6>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Location -->
                                    <div class="col-12 col-lg-6 mb-3">
                                        <div class="card p-3">
                                            <div class="d-flex justify-content-between">
                                                <h6 class="mb-0">Location</h6>
                                                <h6 class="lh-1 mb-0">{{ $block->location }}</h6>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Total Beds -->
                                    <div class="col-12 mb-3">
                                        <div class="card flex-row align-items-center p-3">
                                            <div class="icon icon-lg bg-soft-warning rounded-circle mr-3">
                                                <i class="gd-key text-warning"></i>
                                            </div>
                                            <div>
                                                <h4 class="lh-1 mb-1">{{ $totalBeds }}</h4>
                                                <h6 class="mb-0">Total Beds</h6>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Total Open Beds -->
                                    <div class="col-12 mb-3">
                                        <div class="card flex-row align-items-center p-3">
                                            <div class="icon icon-lg bg-soft-dark rounded-circle mr-3">
                                                <i class="gd-key text-dark"></i>
                                            </div>
                                            <div>
                                                <h4 class="lh-1 mb-1">{{ $totalOpenBeds }}</h4>
                                                <h6 class="mb-0">Total Open Beds</h6>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Total Remaining Beds -->
                                    <div class="col-12 mb-3">
                                        <div class="card flex-row align-items-center p-3">
                                            <div class="icon icon-lg bg-soft-info rounded-circle mr-3">
                                                <i class="gd-key text-info"></i>
                                            </div>
                                            <div>
                                                <h4 class="lh-1 mb-1">{{ $totalBeds - $totalOccupiedBeds }}</h4>
                                                <h6 class="mb-0">Total Remaining Beds</h6>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <!-- Additional Stats -->
                            <div class="col-12 col-md-6 col-lg-4 mb-3">
                                <div class="card flex-row align-items-center p-3 alert-success">
                                    <div class="icon icon-lg bg-soft-success rounded-circle mr-3">
                                        <i class="gd-key text-light"></i>
                                    </div>
                                    <div>
                                        <h4 class="lh-1 mb-1">{{ $totalOccupiedBeds }}/{{$totalBeds}}</h4>
                                        <h6 class="mb-0">Total Occupied Beds</h6>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-lg-4 mb-3">
                                <div class="card flex-row align-items-center p-3 alert-warning">
                                    <div class="icon icon-lg bg-soft-warning rounded-circle mr-3">
                                        <i class="gd-key text-dark"></i>
                                    </div>
                                    <div>
                                        <h4 class="lh-1 mb-1">{{ $totalReservedBeds }}/{{$totalBeds}}</h4>
                                        <h6 class="mb-0">Total Reserved Beds</h6>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-lg-4 mb-3">
                                <div class="card flex-row align-items-center p-3 alert-danger">
                                    <div class="icon icon-lg bg-soft-danger rounded-circle mr-3">
                                        <i class="gd-key text-light"></i>
                                    </div>
                                    <div>
                                        <h4 class="lh-1 mb-1">{{ $totalUnderMaintenanceBeds }}/{{$totalBeds}}</h4>
                                        <h6 class="mb-0">Total Maintenance Beds</h6>
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>

                    <div class="d-flex justify-content-between mb-4">
                        <div class="container-fluid">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Hostel Occupancy</span>
                                <span id="occupancy-percentage">{{ round($occupancyPercentage, 2) }}%</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div id="occupancy-progress-bar" class="progress-bar" role="progressbar"
                                    style="width: {{ $occupancyPercentage }}%;" aria-valuenow="{{ $occupancyPercentage }}" aria-valuemin="0" aria-valuemax="100">
                                    <span class="sr-only">{{ round($occupancyPercentage, 2) }}% Full</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        $(document).ready(function() {
                            // Get the percentage from the element
                            var percentage = parseInt($('#occupancy-percentage').text(), 10);

                            // Get the progress bar element
                            var $progressBar = $('#occupancy-progress-bar');

                            // Determine the class to apply based on the percentage
                            var progressClass;
                            if (percentage === 100) {
                                progressClass = 'bg-danger';
                            } else if (percentage >= 75) {
                                progressClass = 'bg-warning';
                            } else {
                                progressClass = 'bg-success';
                            }

                            // Apply the determined class to the progress bar
                            $progressBar.removeClass('bg-success bg-warning bg-danger').addClass(progressClass);
                        });
                    </script>
            <!-- Floor Details -->
            <div class="row">
                <div class="col-12">
                    <div class="card mb-3 mb-md-4">
                        <div class="card-header border-bottom p-0">
                            <ul class="nav nav-v2 nav-primary nav-justified d-block d-xl-flex w-100" role="tablist">
                                @if($block->floors->isEmpty())
                                    <li class="nav-item border-bottom border-xl-bottom-0">
                                        <span class="nav-link d-flex align-items-center py-2 px-3 p-xl-4">
                                            No Floors Available
                                        </span>
                                    </li>
                                @else
                                    @foreach($block->floors as $index => $floor)
                                    <li class="nav-item border-bottom border-xl-bottom-0 ">
                                        <a class="nav-link d-flex align-items-center py-2 px-3 p-xl-4 {{ $index === 0 ? 'active' : '' }}"
                                            href="#floor{{ $floor->id }}" role="tab"
                                            aria-selected="{{ $index === 0 ? 'true' : 'false' }}" data-toggle="tab">
                                            <span>Floor {{ $floor->floor_number }}</span>
                                        </a>
                                    </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>

                        <div class="card-body tab-content">
                            @if($block->floors->isEmpty())
                                <div class="text-center">
                                    <button class="btn btn-outline-secondary" data-toggle="modal" data-target="#createModal"
                                        title="Add Floor">
                                        <i class="gd-plus"></i>
                                    </button>
                                </div>
                            @else
                                @foreach($block->floors as $index => $floor)
                                @php
                                // Decode the gender JSON to get allowed genders for the floor
                                $allowedGenders = json_decode($floor->gender, true);

                                // Check if multiple genders are allowed
                                $multipleGendersAllowed = is_array($allowedGenders) && count(array_unique($allowedGenders)) > 1;

                                // Collect all gender values from rooms on this floor, including null
                                $roomGendersArray = $floor->rooms->map(function ($room) {
                                    return $room->gender;
                                });

                                // Check if any room on this floor has a null gender column
                                $roomsWithNullGender = $floor->rooms->filter(function ($room) {
                                    return is_null($room->gender);
                                });

                                // Determine if warning should be displayed for null values
                                $warningForNullGender = $roomsWithNullGender->isNotEmpty();
                            @endphp



                                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="floor{{ $floor->id }}"
                                    role="tabpanel">

                                    <!-- Eligible Gender and Students -->
                                    <div class="row mt-3 text-center">
                                        <div class="col-4">
                                            <h6 class="text-muted">Total Rooms: {{ $floor->rooms->count() }}</h6>
                                        </div>
                                        <div class="col-4">
                                            <h6 class="text-muted">Eligible Gender: {{ implode(', ', $allowedGenders) }}</h6>
                                        </div>
                                        <div class="col-4">
                                            <h6 class="text-muted">Eligible Students: {{ implode(', ', json_decode($floor->eligibility, true)) }}</h6>
                                        </div>
                                    </div>

                                    <!-- Row for Actions -->
                                    <div class="d-flex justify-content-center mb-4 mt-5">
                                        <a href="#" class="text-dark btn border shadow-sm mx-2" title="Update Floor" onclick="floorAction('update',{{ $floor->id }})">
                                            <i class="gd-pencil"></i>
                                        </a>
                                        <a href="#" class="text-danger btn border shadow-sm mx-2" title="Delete Floor {{ $floor->floor_number }}" data-toggle="modal" data-target="#deletefloor{{ $floor->id}}">
                                            <i class="gd-trash"></i>
                                        </a>
                                    </div>
                                    {{-- <h3>Room Gender Data</h3>
                                     <pre>{{ json_encode($roomGendersArray->toArray(), JSON_PRETTY_PRINT) }}</pre> --}}

                                    <!-- Warning if any rooms have empty gender field -->
                                    @if ($warningForNullGender)
                                    <div class="alert alert-warning">

                                        <strong>Warning:</strong> Some rooms on this floor do not have a specified gender, although multiple genders are allowed for this floor. Please ensure that all rooms are assigned a gender to proceed.
                                    </div>
                                    @endif

                                    <!-- Room and Bed Details (Only if no warning) -->
                                    @if (!$warningForNullGender)

                                    <div class="radio-tile-group">
                                        @foreach($floor->rooms as $room)
                                        <div class="card mb-2">
                                            <div class="card-header text-center">
                                                <span class="p-3 shadow-sm cursor-pointer" onclick=" roomitem({{ $room->id }})">Room {{ $room->room_number }} - {{ $room->beds->count() }} beds</span>
                                                 <br><br>
                                                <span  class="badge badge-sm rounded-circle @if(strtolower($room->gender) === 'female') badge-success @elseif(strtolower($room->gender) === 'male') badge-secondary @else badge-secondary @endif" >
                                                    @if(strtolower($room->gender) === 'female')
                                                        F
                                                    @elseif(strtolower($room->gender) === 'male')
                                                        M
                                                    @else
                                                        {{ $room->gender }} <!-- Fallback if the gender is neither 'female' nor 'male' -->
                                                    @endif
                                                </span>
                                            </div>

                                            <div class="card-body">
                                                @foreach($room->beds as $bed)
                                                @php



                                                        $usertime = User::where('semester_id', session('semester_id'))
                                                       ->where('id', $bed->user_id)
                                                        ->first();





                                                        if ($usertime) {
                                                            $expirationDate = $usertime->expiration_date;
                                                      } else {
                                                        $expirationDate = null; // Initialize $expirationDate to null if no user is found
                                                            }
                                                            // Initialize status class and text
                                                            $statusClass = '';
                                                             $statusText = '';

                                                            // Determine the status class and text based on the bed's status and user_id
                                                       if ($bed->user_id &&  $usertime )
                                                       {


                                                         if ($expirationDate && Carbon::now()->greaterThan($expirationDate) && empty($usertime->payment_status) ) {
                                                             $statusClass = 'alert-danger';
                                                               $statusText = 'Expire';
                                                                 } else {
                                                                    $statusClass = 'alert-success';
                                                                      $statusText = 'Taken';
                                                                    }
                                                                  }






                                                    else {
                                                        switch ($bed->status) {
                                                            case 'activate':
                                                                $statusText = 'Open';
                                                                $statusClass = 'text-dark'; // Add this class for active beds
                                                                break;
                                                            case 'under_maintenance':
                                                                $statusText = 'Maintenance';
                                                                $statusClass = 'alert-danger'; // Add this class for beds under maintenance
                                                                break;
                                                            case 'reserve':
                                                                $statusText = 'Reserved';
                                                                $statusClass = 'alert-warning'; // Add this class for reserved beds
                                                                break;
                                                            default:
                                                                $statusText = 'Unknown';
                                                                $statusClass = 'text-muted'; // Add this class for unknown status
                                                                break;
                                                        }
                                                    }
                                                @endphp

                                                <div class="input-container" style="cursor: pointer;">

                                                    <div class="radio-tile {{ $statusClass }}" onclick="floorAction('bed', {{ $bed->id }})"style="cursor: pointer;" >
                                                        <label class="radio-tile-label mt-2" style="cursor: pointer;">
                                                            {{ $room->room_number }} - Bed {{ $bed->bed_number }}
                                                        </label>
                                                        <label class="radio-tile-label  {{ $statusClass }}" style="cursor: pointer;">
                                                            {{ $statusText }}
                                                        </label>

                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>



                </div>


            </div>

            <div class="tab-pane fade" id="tabs1-tab2" role="tabpanel">
                <div class="content py-4 px-3 px-md-4">


                    <!-- Check-Out Items Management -->
                    <div class="row">


                        <!-- Manage Requirements Section -->
                        <div class="col-md-12 mb-3">
                            <div class="container">
                                <h5 class="mb-4">Manage Requirements</h5>
                                <input type="hidden" id="blockId" value="{{ $block->id }}">

                                <div id="requirementsContainer" class="row">
                                    @forelse ($requirements as $requirement)
                                        <div class="col-md-12 mb-3">
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="{{ $requirement->name }}">
                                                <input type="number" class="form-control col-2" value="{{ $requirement->quantity }}" min="1">
                                                <button class="btn btn-outline-default btn-sm" type="button" onclick="removeRequirement(this)">
                                                    <i class="gd-trash text-danger"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @empty
                                       <!-- Notification Message -->
                    <div class="alert alert-info" role="alert">
                        <strong>Notice:</strong> The items and requirements listed below are predefined and have not yet been saved to the database. Customize them as needed and click "Save Changes" to add them to the database.
                    </div>
                                        <!-- Predefined requirements -->
                                        <div class="col-md-12 mb-3">
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="Bucket">
                                                <input type="number" class="form-control col-2" value="1" min="1">
                                                <button class="btn btn-outline-default btn-sm" type="button" onclick="removeRequirement(this)">
                                                    <i class="gd-trash text-danger"></i>
                                                </button>
                                                <span class="text-muted">Predefined Requirement</span>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="Mosquito Net">
                                                <input type="number" class="form-control col-2" value="1" min="1">
                                                <button class="btn btn-outline-default btn-sm" type="button" onclick="removeRequirement(this)">
                                                    <i class="gd-trash text-danger"></i>
                                                </button>
                                                <span class="text-muted">Predefined Requirement</span>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="Sweeper Broom">
                                                <input type="number" class="form-control col-2" value="1" min="1">
                                                <button class="btn btn-outline-default btn-sm" type="button" onclick="removeRequirement(this)">
                                                    <i class="gd-trash text-danger"></i>
                                                </button>
                                                <span class="text-muted">Predefined Requirement</span>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                                <div class="text-center mt-3">
                                    <button id="addRequirement" class="btn btn-outline-primary">
                                        <i class="gd-plus"></i> Add Requirement
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Save Changes Button -->
                    <div class="text-center mt-4">
                        <button id="saveChanges" class="btn btn-outline-success">
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
            <script>
                $(document).ready(function() {
                    // Initialize niceSelect
                    $('select').niceSelect();

                    // Add new requirement
                    $('#addRequirement').click(function() {
                        $('#requirementsContainer').append(
                            `<div class="col-md-12 mb-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="New Requirement">
                                    <input type="number" class="form-control col-2" value="1" min="1">
                                    <button class="btn btn-outline-default btn-sm" type="button" onclick="removeRequirement(this)">
                                        <i class="gd-trash text-danger"></i>
                                    </button>
                                    <input type="hidden" class="requirement-id" value=""> <!-- Hidden input to track requirement IDs -->
                                </div>
                            </div>`
                        );
                    });

                    // Function to show the tab
                    function showTab(tabId) {
                        var tabTriggerEl = $('a[href="' + tabId + '"]');
                        if (tabTriggerEl.length) {
                            var tab = new bootstrap.Tab(tabTriggerEl[0]);
                            tab.show();
                        }
                    }

                    $('#saveChanges').click(function() {
                        // Collect data for requirements
                        let requirements = [];
                        let allRequirementsValid = true;
                        $('#requirementsContainer .input-group').each(function() {
                            let requirementId = $(this).find('.requirement-id').val(); // Track the ID of the requirement
                            let requirementName = $(this).find('input[type="text"]').val();
                            let requirementQuantity = $(this).find('input[type="number"]').val();

                            if (requirementName === '' || requirementQuantity === '' || requirementQuantity <= 0) {
                                allRequirementsValid = false;
                            } else if (requirementId === '' || requirementId === undefined) {
                                requirements.push({ name: requirementName, quantity: requirementQuantity });
                            }
                        });

                        // Validate that at least one requirement exists and all quantities are valid
                        if (requirements.length === 0) {
                            showToast('#error-toast', 'At least one requirement must be added.');
                            return; // Stop the process if validation fails
                        }

                        if (!allRequirementsValid) {
                            showToast('#error-toast', 'Please ensure all quantities are greater than zero for each requirement.');
                            return; // Stop the process if validation fails
                        }

                        // Get the block ID from a hidden input or another source
                        let blockId = $('#blockId').val(); // Assuming you have a hidden input with ID 'blockId'
                        $('#overlay').css('display', 'flex'); // Show the overlay

                        // Send data to server
                        $.ajax({
                            url: '/save-check-out-items', // Adjust the URL according to your route
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}', // Include CSRF token for security
                                block_id: blockId,
                                requirements: requirements
                            },
                            success: function(response) {
                                // Handle success
                                if (response.success) {
                                    showToast('#success-toast', 'Changes saved successfully!');
                                    $('#overlay').fadeOut(); // Hide the overlay

                                    // Call the room function with the block ID
                                    room(blockId);

                                    // Activate the second navigation item
                                    $('.nav-item .nav-link').removeClass('active');
                                    $('.nav-item').eq(1).find('.nav-link').addClass('active');

                                    // Show the second tab
                                    showTab('#tabs1-tab2');
                                } else {
                                    showToast('#error-toast', response.message);
                                    $('#overlay').fadeOut(); // Hide the overlay
                                }
                            },
                            error: function(xhr) {
                                // Handle error
                                showToast('#error-toast', 'An error occurred while saving changes.');
                                $('#overlay').fadeOut(); // Hide the overlay
                            }
                        });
                    });
                });

                function removeRequirement(button) {
                    $(button).closest('.col-md-12').remove();
                }

                function showToast(toastId, message) {
                    var $toast = $(toastId);
                    $toast.find('.toast-body').text(message);
                    $toast.toast({
                        delay: 3000
                    }); // Set the delay for the toast to hide automatically
                    $toast.toast('show');
                }
            </script>




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




<!-- Modal -->

<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">New Floor</h5>
            </div>
            <div class="modal-body">
                <form id="createForm" method="POST" action="/hostel_create">
                    <meta name="csrf-token" content="{{ csrf_token() }}">

                    <!-- Step 1: Floor Details -->
                    <div id="step11">
                        <div class="form-group">
                            <label for="floorName">Floor Name</label>
                            <input type="text" class="form-control" id="floorName" required>
                            <small class="form-text text-danger" id="floorNameError"></small>
                        </div>
                        <div class="form-group">
                            <label for="numRooms">Number of Rooms</label>
                            <input type="number" class="form-control" id="numRooms" min="1" required>
                            <small class="form-text text-danger" id="numRoomsError"></small>
                        </div>
                        <div class="form-group">
                            <label>Gender</label>
                            <div class="btn-group-toggle d-flex flex-wrap" data-toggle="buttons">
                                <label class="btn btn-outline-primary me-2 mb-2 flex-grow-1" style="cursor: pointer;">
                                    <input type="checkbox" name="gender" value="male" autocomplete="off"> Male
                                </label>
                                <label class="btn btn-outline-primary me-2 mb-2 flex-grow-1" style="cursor: pointer;">
                                    <input type="checkbox" name="gender" value="female" autocomplete="off"> Female
                                </label>
                            </div>
                            <small class="form-text text-danger" id="genderError"></small>
                        </div>
                        <div class="form-group">
                            <label>Eligibility</label>
                            <div class="btn-group-toggle d-flex flex-wrap" data-toggle="buttons">
                                <label class="btn btn-outline-info me-2 mb-2 flex-grow-1" style="cursor: pointer;">
                                    <input type="checkbox" name="eligibility" value="D1" autocomplete="off"> D1
                                </label>
                                <label class="btn btn-outline-info me-2 mb-2 flex-grow-1" style="cursor: pointer;">
                                    <input type="checkbox" name="eligibility" value="D2" autocomplete="off"> D2
                                </label>
                                <label class="btn btn-outline-info me-2 mb-2 flex-grow-1" style="cursor: pointer;">
                                    <input type="checkbox" name="eligibility" value="D3" autocomplete="off"> D3
                                </label>
                                <label class="btn btn-outline-primary me-2 mb-2 flex-grow-1" style="cursor: pointer;">
                                    <input type="checkbox" name="eligibility" value="B1" autocomplete="off"> B1
                                </label>
                                <label class="btn btn-outline-primary me-2 mb-2 flex-grow-1" style="cursor: pointer;">
                                    <input type="checkbox" name="eligibility" value="B2" autocomplete="off"> B2
                                </label>
                                <label class="btn btn-outline-primary me-2 mb-2 flex-grow-1" style="cursor: pointer;">
                                    <input type="checkbox" name="eligibility" value="B3" autocomplete="off"> B3
                                </label>
                                <label class="btn btn-outline-primary me-2 mb-2 flex-grow-1" style="cursor: pointer;">
                                    <input type="checkbox" name="eligibility" value="B4" autocomplete="off"> B4
                                </label>
                            </div>
                            <small class="form-text text-danger" id="eligibilityError"></small>
                        </div>
                    </div>

                    <!-- Step 3: Bed Details -->
                    <div id="step3" style="display:none;">
                        <div id="roomDetails"></div>

                    </div>

                    <!-- Step 5: Confirmation -->
                    <div id="step5" style="display:none;">

                        <div class="row">
                            <div class="col-md-6">
                                <h6>Floor Details</h6>
                                <p><strong>Floor Name:</strong> <span id="confirmFloorName"></span></p>
                                <p><strong>Number of Rooms:</strong> <span id="confirmNumRooms"></span></p>
                                <p><strong>Gender:</strong> <span id="confirmGender"></span></p>
                                <p><strong>Eligibility:</strong> <span id="confirmEligibility"></span></p>
                            </div>
                            <div class="col-md-6">
                                <h6>Total Beds</h6>
                                <p id="confirmTotalBeds"></p>
                            </div>
                        </div>
                        <div id="confirmFloorDetails" class="row"></div>
                        <div id="confirmEligibleStudents" class="row"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="prevButton1" onclick="goToStep1()">Previous</button>
                <button type="button" class="btn btn-primary" id="prevButton3" onclick="goToStep3()">Previous</button>

                <button type="button" class="btn btn-primary" id="nextButton3" onclick="goToStep3()">Next</button>
                <button type="button" class="btn btn-primary" id="nextButton5" onclick="goToStep5()">Next</button>

                <button type="button" class="btn btn-success" id="submitButton" style="display:none;"
                    onclick="submitForm(event)">Submit</button>
            </div>
        </div>
    </div>
</div>


<script>
    function setBedsForAllRooms() {
        let beds = $('#setAllBeds').val().trim();
        $('#setAllBedsError').text('');
        if (beds === '' || beds <= 0) {
            $('#setAllBedsError').text('Please enter a valid number of beds.');
            return;
        }
        $('#roomDetails .form-group').each(function() {
            $(this).find('input[type="number"]').val(beds);
        });
    }

    function goToStep1() {
        $('#step11').show();
        $('#step3').hide();
        $('#step5').hide();
        $('#nextButton3').show();
        $('#nextButton5').hide();
        $('#submitButton').hide();
        $('#prevButton1').hide();
        $('#prevButton3').hide();
        $('#progressBar').css('width', '25%').attr('aria-valuenow', 25);
    }

    function goToStep3() {
        if (validateStep1()) {
            $('#step11').hide();
            $('#step3').show();
            $('#step5').hide();
            $('#nextButton3').hide();
            $('#nextButton5').show();
            $('#prevButton3').hide();
            $('#prevButton1').show();
            $('#submitButton').hide();
            $('#progressBar').css('width', '75%').attr('aria-valuenow', 75);
            generateRoomInputs();
        }
    }

    function validateStep1() {
        let valid = true;
        $('.form-text.text-danger').text('');
        if ($('#floorName').val().trim() === '') {
            $('#floorNameError').text('Floor Name is required.');
            valid = false;
        }
        if ($('#numRooms').val().trim() === '') {
            $('#numRoomsError').text('Number of Rooms is required.');
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
        return valid;
    }
    function generateRoomInputs() {
    $('#roomDetails').empty();
    let numRooms = $('#numRooms').val();
    $('#roomDetails').append('<h5>Enter the number of beds for each room:</h5>');
    $('#roomDetails').append(`
        <div class="form-group">
            <label for="setAllBeds">Set Number of Beds for All Rooms:</label>
            <input type="number" class="form-control" id="setAllBeds" min="1">
            <small class="form-text text-danger" id="setAllBedsError"></small>
            <button type="button" class="btn btn-primary mt-2" onclick="setBedsForAllRooms()">Apply to All Rooms</button>
        </div>
    `);
    for (let i = 1; i <= numRooms; i++) {
        $('#roomDetails').append(`<div class="form-group">
            <label for="bedCountRoom${i}">Number of Beds in Room ${i}:</label>
            <input type="number" class="form-control" id="bedCountRoom${i}" min="1" required>
            <small class="form-text text-danger" id="bedCountRoom${i}Error"></small>
        </div>`);
    }
}


    function goToStep5() {
        if (validateStep3()) {
            $('#step11').hide();
            $('#step3').hide();
            $('#step5').show();
            $('#submitButton').show();
            $('#nextButton3').hide();
            $('#nextButton5').hide();
            $('#prevButton1').hide();
            $('#prevButton3').show();
            $('#progressBar').css('width', '100%').attr('aria-valuenow', 100);
            populateSummary();
        }
    }

    function setBedsForAllRooms() {
        let beds = $('#setAllBeds').val().trim();
        $('#setAllBedsError').text('');
        if (beds === '' || beds <= 0) {
            $('#setAllBedsError').text('Please enter a valid number of beds.');
            return;
        }
        $('#roomDetails .form-group').each(function() {
            $(this).find('input[type="number"]').val(beds);
        });
    }

    function validateStep3() {
        let valid = true;
        $('#roomDetails .form-group').each(function() {
            let value = $(this).find('input').val();
            let id = $(this).find('input').attr('id');
            // Skip validation for the "Set Number of Beds for All Rooms" input
            if (id === 'setAllBeds') {
                return;
            }
            // Validate room input fields
            if (value === '' || value <= 0) {
                $(`#${id}Error`).text('Please enter a valid number of beds.');
                valid = false;
            } else {
                $(`#${id}Error`).text('');
            }
        });
        return valid;
    }

    function populateSummary() {
        $('#confirmFloorName').text($('#floorName').val());
        $('#confirmNumRooms').text($('#numRooms').val());
        $('#confirmGender').text($('input[name="gender"]:checked').map(function() {
            return $(this).val();
        }).get().join(', '));
        $('#confirmEligibility').text($('input[name="eligibility"]:checked').map(function() {
            return $(this).val();
        }).get().join(', '));
        $('#confirmFloorDetails').empty();
        let numRooms = $('#numRooms').val();
        let totalBeds = 0;
        for (let i = 1; i <= numRooms; i++) {
            let numBeds = $(`#bedCountRoom${i}`).val();
            $('#confirmFloorDetails').append(`<div class="col-md-6 mb-3">
                <h6>Room ${i}</h6>
                <p><strong>Number of Beds:</strong> ${numBeds}</p>
            </div>`);
            totalBeds += parseInt(numBeds);
        }
        $('#confirmTotalBeds').text(totalBeds);
    }
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
    function submitForm(event) {
    if (event) {
        event.preventDefault();
    }
    // Prevent multiple submissions
    if ($('#submitButton').hasClass('disabled')) {
        console.log('Submit button is disabled. Form is already being processed.');
        return;
    }
    // Disable button and show overlay
    $('#submitButton').addClass('disabled');
    $('#overlay').css('display', 'flex');
    // CSRF token and block ID
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    var blockId = {{$block->id}}; // Ensure this variable is correctly set in your view
    // Form data
    var formData = {
        floorName: $('#floorName').val(),
        numRooms: $('#numRooms').val(),
        gender: $('input[name="gender"]:checked').map(function() {
            return $(this).val();
        }).get(),
        eligibility: $('input[name="eligibility"]:checked').map(function() {
            return $(this).val();
        }).get(),
        rooms: []
    };
    // Collect room data, excluding the "Set Number of Beds for All Rooms" input
    $('#roomDetails .form-group').each(function(index) {
        var numBeds = $(this).find('input[type="number"]').val();
        if (numBeds && $(this).find('input[type="number"]').attr('id') !== 'setAllBeds') {
            formData.rooms.push({
                roomNumber: index, // Start numbering from 1
                bedCount: parseInt(numBeds, 10)
            });
        }
    });
    console.log('Form Data to be sent to the backend:', formData);
    $.ajax({
        url: `/floor_create/${blockId}`, // Correct URL with block ID
        type: 'POST',
        data: {
            _token: csrfToken,
            floorName: formData.floorName,
            numRooms: formData.numRooms,
            gender: formData.gender,
            eligibility: formData.eligibility,
            rooms: formData.rooms
        },
        success: function(response) {
            console.log('Response:', response);
            if (response.success) {
                $('#success-toast .toast-body').text(response.message);
                $('#success-toast').toast('show');
                $('#overlay').fadeOut();
                closeModalAndExecuteHostel();
            } else {
                $('#error-toast .toast-body').text(response.message);
                $('#error-toast').toast('show');
                $('#submitButton').removeClass('disabled');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error occurred:', status, error);
            $('#error-toast .toast-body').text('An error occurred. Please try again.');
            $('#error-toast').toast('show');
            $('#submitButton').removeClass('disabled');
            $('#overlay').fadeOut();
        }
    });
}

    function closeModalAndExecuteHostel() {
        // Close the modal
        $('#createModal').modal('hide'); // Use the correct ID of your modal
        // Ensure that hostel() is called after the modal is closed
        $('#createModal').on('hidden.bs.modal', function() {
            room({{$block-> id}});
        });
    }
    $(document).ready(function() {
        goToStep1();
        $('#nextButton').off('click').on('click', function() {
            goToStep3();
        });
        $('#submitButton').off('click').on('click', function(event) {
            submitForm(event);
        });
        $('#prevButton').off('click').on('click', function() {
            goToStep1();
        });
        $('#closeModalButton').off('click').on('click', function() {
            $('#createModal').modal('hide');
        });
    });



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
                // Show success toast
                showToast('success-toast', 'Floor and associated rooms and beds deleted successfully.');
                @foreach($block->floors as $index => $floor)
                closeModalAndExecuteHostel_{{ $floor->id }}();
                @endforeach
                $('#overlay').fadeOut(); // Hide the overlay
            },
            error: function(xhr) {
                // Show error toast
                showToast('error-toast', 'An error occurred while deleting the floor.');
            },
            complete: function() {
                // Always hide the overlay and re-enable the button
                $('#overlay').fadeOut('fast', function() {
                    $('#deletefloor .btn-outline-success').prop('disabled', false);
                });
            }
        });
    };

    function showToast(toastId, message) {
        var toastElement = $('#' + toastId);
        toastElement.find('.toast-body').text(message);
        toastElement.toast('show');
    }


    @foreach($block->floors as $index => $floor)
    // Create a unique function for each floor
    function closeModalAndExecuteHostel_{{ $floor->id }}() {
        // Close the specific modal for the floor
        $('#deletefloor{{ $floor->id }}').modal('hide'); // Use the correct ID of your modal
        // Ensure that hostel() is called after the modal is closed
        $('#deletefloor{{ $floor->id }}').on('hidden.bs.modal', function() {
            room({{ $block->id }});
        });
    }

@endforeach

});



</script>


