@php
use Carbon\Carbon;
use App\Models\User;

@endphp
<div class="content">
    <div class="py-4 px-3 px-md-4">
        <div class="d-flex justify-content-between mb-4">
            <h3 class="h3 mb-0">Room {{ $room->room_number }}</h3>
            <button class="btn btn-outline-secondary" onclick="hostel()"><i class="gd-shift-left"></i> </button>
            <button class="btn btn-outline-secondary" onclick="roomitem({{ $room->id }})"> <i class="gd-loop"></i></button>
        </div>
        <ul class="nav nav-v2 nav-primary nav-justified d-block d-xl-flex  container" role="tablist">
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center py-2 px-3 p-xl-4  active" href="#tabs1-tab1" role="tab" aria-selected="true"
                   data-toggle="tab">Room {{ $room->room_number }} Students
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center py-2 px-3 p-xl-4 " href="#tabs1-tab2" role="tab" aria-selected="false"
                   data-toggle="tab">Manage Room Items
                </a>
            </li>
        </ul>




        <div id="tabsContent1" class="card-body tab-content p-0">
            <div class="tab-pane fade show active" id="tabs1-tab1" role="tabpanel">
                <div class="card-body">
                    <input type="hidden" id="blockId" value="{{ $block->id }}">

                    <input type="hidden" id="floorId" value="{{ $room->floor_id }}">
                    <input type="hidden" id="roomId" value="{{ $room->id }}">
                    @if($room->users->isEmpty())

                    <div class="container full-height d-flex align-items-center justify-content-center" style="height: 70vh;">
                        <div class="" style="width: 18rem;">
                            <div class="card-body text-center">
                                <i class="gd-alert text-danger" style="font-size: 3rem;"></i><br>
                                <small class="card-title">No data available</small>
                            </div>
                        </div>
                    </div>





                    @else
                        <div class="row">
                            @foreach($room->users as $student)
                                <div class="col-md-4 mb-4">
                                    <div class="profile-card p-4 border rounded">
                                        <div class="text-center mb-2">
                                            <img class="profile-image img-fluid rounded-circle border "
                                                src="{{ $student->profile_photo_path ?? 'img/placeholder.jpg' }}"
                                                alt="Profile Image" style="max-width: 120px;">
                                        </div>
                                        <div class="text-center">
                                            <label class="fw-bold">Reg No {{ $student->registration_number}}</label>

                                            <input type="text" class="form-control" disabled value="Name: {{ $student->name }}">

                                            <input type="text" class="form-control" disabled value="Bed: {{ $student->bed->bed_number }}">
                                            <input type="text"
                                            class="form-control {{ $student->checkin == 2 ? 'text-success' : 'text-danger' }}"
                                            disabled
                                            value="{{ $student->checkin == 2 ? 'Official Student' : 'Not Official' }}">

                                            <button class="btn btn-sm shadow-sm mt-3"
                                            onclick="floorAction('bed', {{ $student->bed->id }})">
                                            <i class="gd-arrow-top-right"></i>
                                        </button>


                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            <div class="tab-pane fade" id="tabs1-tab2" role="tabpanel">
                <div class="content py-4 px-3 px-md-4">


                    <!-- Check-Out Items Management -->
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="container">
                                <h5 class="mb-4">Manage Check-Out Items</h5>
                                <input type="hidden" id="blockId" value="{{ $block->id }}">
                                <div id="itemsContainer" class="row">
                                    @forelse ($checkOutItems as $item)
                                        <div class="col-md-12 mb-3">
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="{{ $item->name }}">
                                                <select class="nice-select" data-placeholder="Select Condition">
                                                    <option value="Good" {{ $item->condition == 'Good' ? 'selected' : '' }} selected>Good</option>

                                                </select>
                                                <button class="btn btn-outline-default btn-sm" type="button" onclick="removeItem(this)">
                                                    <i class="gd-trash text-danger"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @empty
                                       <!-- Notification Message -->
                                              <div class="alert alert-info" role="alert">
                                           <strong>Notice:</strong> The items and requirements listed below are predefined and have not yet been saved to the database. Customize them as needed and click "Save Changes" to add them to the database.
                                        </div>
                                        <!-- Predefined items -->
                                        <div class="col-md-12 mb-3">
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="Mattress">
                                                <select class="nice-select" data-placeholder="Select Condition">
                                                    <option value="Good" selected>Good</option>

                                                </select>
                                                <button class="btn btn-outline-default btn-sm" type="button" onclick="removeItem(this)">
                                                    <i class="gd-trash text-danger"></i>
                                                </button>
                                                <span class="text-muted">Predefined Item</span>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="Room Key">
                                                <select class="nice-select" data-placeholder="Select Condition">
                                                    <option value="Good" selected>Good</option>

                                                </select>
                                                <button class="btn btn-outline-default btn-sm" type="button" onclick="removeItem(this)">
                                                    <i class="gd-trash text-danger"></i>
                                                </button>
                                                <span class="text-muted">Predefined Item</span>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="Cleaning Done">
                                                <select class="nice-select" data-placeholder="Select Condition">
                                                    <option value="Good" selected>Good</option>

                                                </select>
                                                <button class="btn btn-outline-default btn-sm" type="button" onclick="removeItem(this)">
                                                    <i class="gd-trash text-danger"></i>
                                                </button>
                                                <span class="text-muted">Predefined Item</span>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                                <div class="text-center mt-3">
                                    <button id="addItem" class="btn btn-outline-primary">
                                        <i class="gd-plus"></i> Add Item
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
        </div>




    </div>
</div>

<script>
    $(document).ready(function() {
        // Initialize niceSelect
        $('select').niceSelect();

        // Add new check-out item
        $('#addItem').click(function() {
            $('#itemsContainer').append(
                `<div class="col-md-12 mb-3">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="New Item">
                        <select class="nice-select" data-placeholder="Select Condition">
                            <option value="">Select Condition</option>
                            <option value="Good" selected>Good</option>
                        </select>
                        <button class="btn btn-outline-default btn-sm" type="button" onclick="removeItem(this)">
                            <i class="gd-trash text-danger"></i>
                        </button>
                        <input type="hidden" class="item-id" value=""> <!-- Hidden input to track item IDs -->
                    </div>
                </div>`
            );
            // Reinitialize niceSelect for the new select elements
            $('select').niceSelect();
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
            // Collect data for check-out items
            let items = [];
            let allItemsValid = true;
            $('#itemsContainer .input-group').each(function() {
                let itemId = $(this).find('.item-id').val(); // Track the ID of the item
                let itemName = $(this).find('input[type="text"]').val();
                let itemCondition = $(this).find('select').val();

                if (itemName === '' || itemCondition === '' || itemCondition === undefined) {
                    allItemsValid = false;
                } else if (itemId === '' || itemId === undefined) {
                    items.push({ name: itemName, condition: itemCondition });
                }
            });

            // Validate that at least one item exists and all conditions are selected
            if (items.length === 0) {
                showToast('#error-toast', 'At least one check-out item must be added.');
                return; // Stop the process if validation fails
            }

            // Check if all items have conditions selected
            if (!allItemsValid) {
                showToast('#error-toast', 'Please select a condition for each check-out item.');
                return; // Stop the process if validation fails
            }

            // Get IDs from hidden inputs or other sources
            let blockId = $('#blockId').val(); // Assuming you have a hidden input with ID 'blockId'
            let floorId = $('#floorId').val(); // Assuming you have a hidden input with ID 'floorId'
            let roomId = $('#roomId').val(); // Assuming you have a hidden input with ID 'roomId'
            $('#overlay').css('display', 'flex'); // Show the overlay

            // Send data to server
            $.ajax({
                url: '/save-check-out-items-room', // Adjust the URL according to your route
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // Include CSRF token for security
                    block_id: blockId,
                    floor_id: floorId,
                    room_id: roomId,
                    items: items
                },
                success: function(response) {
                    // Handle success
                    if (response.success) {
                        showToast('#success-toast', 'Changes saved successfully!');
                        $('#overlay').fadeOut(); // Hide the overlay

                        // Call the room function with the block ID
                        roomitem(roomId);

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

    function removeItem(button) {
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
