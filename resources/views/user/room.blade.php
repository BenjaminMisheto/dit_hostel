<div class="content py-4 px-3 px-md-4">
    <div class="mb-3 mb-md-4 d-flex justify-content-between">
        <div class="h3 mb-0">{{$block->name}}</div>
    </div>
<!-- Floor Details -->
<div class="row">
    <div class="col-12">
        <div class="card mb-3 mb-md-4">
            <div class="card-header border-bottom p-0">
                <ul class="nav nav-v2 nav-primary nav-justified d-block d-xl-flex w-100" role="tablist">
                    @if($filteredFloors->isEmpty())
                        <li class="nav-item border-bottom border-xl-bottom-0">
                            <span class="nav-link d-flex align-items-center py-2 px-3 p-xl-4">
                                Sorry, {{ $user->name }}.
                            </span>
                        </li>
                    @else
                        @foreach($filteredFloors as $floor)
                            <li class="nav-item border-bottom border-xl-bottom-0 shadow-sm">
                                <a class="nav-link d-flex align-items-center py-2 px-3 p-xl-4 {{ $loop->first ? 'active' : '' }}"
                                   href="#floor{{ $floor->id }}" role="tab"
                                   aria-selected="{{ $loop->first ? 'true' : 'false' }}" data-toggle="tab">
                                    <span>Floor {{ $floor->floor_number }}</span>
                                </a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>

            <div class="card-body tab-content">
                @if($filteredFloors->isEmpty())
                <div class="alert alert-warning">
                    <strong>Notice:</strong>
                    <p>Unfortunately, there is no available space for you in this block. Here are some possible reasons:</p>
                    <ul>
                        <li>The block is fully occupied by students already assigned to rooms and beds.</li>
                        <li>The floors in this block do not match your eligibility criteria, such as gender or course requirements.</li>
                        <li>The rooms available in this block do not have beds that meet your requirements or are suitable for you.</li>
                        <li>Some beds might be under maintenance or reserved, reducing the number of available spaces.</li>
                    </ul>
                    <p>Please consider selecting another block. If you are unable to find a suitable space, please wait for the next selection period, which will be available soon.</p>
                </div>
                @else
                    @foreach($filteredFloors as $floor)
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="floor{{ $floor->id }}"
                             role="tabpanel">
                            <!-- Eligible Gender and Students -->
                            <div class="row mt-3 text-center">
                                <div class="col-4">
                                    <h6 class="text-muted">Total Rooms: {{ $floor->rooms->count() }}</h6>
                                </div>
                                <div class="col-4">
                                    <h6 class="text-muted">Eligible Gender: {{ implode(', ', json_decode($floor->gender, true)) }}</h6>
                                </div>
                                <div class="col-4">
                                    <h6 class="text-muted">Eligible Students: {{ implode(', ', json_decode($floor->eligibility, true)) }}</h6>
                                </div>
                            </div>


        <!-- Room and Bed Details -->
<div class="radio-tile-group">
    @foreach($floor->rooms as $room)
        @if(strtolower($room->gender) == strtolower($user->gender))
            <div class="card mb-2">
                <div class="card-header">
                    Room {{ $room->room_number }} - {{ $room->beds->count() }} beds
                </div>
                <div class="card-body">
                    @foreach($room->beds as $bed)
                        @php
                            $statusClass = '';
                            $statusText = '';
                            $disabled = false;

                            if ($bed->user_id) {
                                $statusClass = 'alert-success';
                                $statusText = 'Taken';
                                $disabled = true;
                            } else {
                                switch ($bed->status) {
                                    case 'activate':
                                        $statusText = 'Open';
                                        break;
                                    case 'under_maintenance':
                                        $statusText = 'Maintenance';
                                        $statusClass = 'alert-danger';
                                        $disabled = true;
                                        break;
                                    case 'reserve':
                                        $statusText = 'Reserved';
                                        $statusClass = 'alert-warning';
                                        $disabled = true;
                                        break;
                                    default:
                                        $statusText = 'Unknown';
                                        $statusClass = 'text-muted';
                                        $disabled = true;
                                        break;
                                }
                            }
                        @endphp

                        <div class="input-container" style="cursor: pointer;">
                            <input id="bed_{{ $bed->id }}" class="radio-button" type="radio" name="bed"
                                   value="{{ $bed->id }}"
                                   data-room-id="{{ $room->id }}"
                                   data-floor-id="{{ $floor->id }}"
                                   data-block-id="{{ $block->id }}"
                                   {{ $disabled ? 'disabled' : '' }}>

                            <div class="radio-tile {{ $statusClass }}">
                                <label for="bed_{{ $bed->id }}"
                                       class="radio-tile-label mt-2">{{ $room->room_number }} - Bed
                                    {{ $bed->bed_number }}</label>
                                <label for="bed_{{ $bed->id }}"
                                       class="radio-tile-label {{ $statusClass }}">{{ $statusText }}</label>
                            </div>
                        </div>

                    @endforeach
                </div>
            </div>
        @endif
    @endforeach
</div>

                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>



@if(!$filteredFloors->isEmpty())
    <div class="text-center mt-4">
        <button id="confirmButton" class="btn btn-outline-secondary px-4 py-2" >Next stage</button>
    </div>
    @endif

</div>
<script>
    $(document).ready(function () {
    // Enable the "Next stage" button when a bed is selected
    $('input[name="bed"]').on('change', function () {
        $('#confirmButton').prop('disabled', false);
    });

    // Handle "Next stage" button click
    $('#confirmButton').on('mousedown', function (e) {
        if (!$('input[name="bed"]:checked').length) {
            e.preventDefault(); // Prevents the button action
            showToast('error-toast', 'Please select a bed before proceeding.');
        } else {
            // Get selected bed details
            var selectedBed = $('input[name="bed"]:checked');
            var bedId = selectedBed.val();
            var roomId = selectedBed.data('room-id');
            var floorId = selectedBed.data('floor-id');
            var blockId = selectedBed.data('block-id');

            $('#overlay').css('display', 'flex');

            // Send data to server via AJAX
            $.ajax({
                url: '/update-user-bed-selection',  // Update with the correct route
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',  // Include the CSRF token
                    bed_id: bedId,
                    room_id: roomId,
                    floor_id: floorId,
                    block_id: blockId
                },
                success: function(response) {
                    $('#gd-hostel').removeClass('gd-close text-danger').addClass('gd-check text-success');
                    $('#overlay').fadeOut();
                    // Show success toast after successful update
                    showToast('success-toast', response.message);
                    finish();
                },
                error: function(xhr) {
                    $('#overlay').fadeOut();
                    // Show error toast with detailed error message
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'An error occurred while saving your selection. Please try again.';
                    showToast('error-toast', errorMessage);

                    // Log detailed error information to the console for debugging
                    console.error('AJAX error response:', xhr.responseText);
                }
            });
        }
    });

    // Function to show toast notification
    function showToast(toastId, message) {
        var toastElement = $('#' + toastId);
        toastElement.find('.toast-body').text(message);
        toastElement.toast({ delay: 3000 });
        toastElement.toast('show');
    }
});

</script>
