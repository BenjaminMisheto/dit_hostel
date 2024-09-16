<style>
    .slider-container {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }

    .slider-label {
        width: 100px;
        font-weight: bold;
        color: #495057;
    }

    .slider-value {
        width: 60px;
        text-align: right;
        margin-left: 1rem;
        border: 1px solid #ced4da;
        border-radius: 5px;
        padding: 0.25rem;
    }

    /* Styling the range sliders */
    .form-control-range {
        -webkit-appearance: none;
        appearance: none;
        width: 100%;
        height: 6px;
        /* Make the slider thinner */
        border-radius: 5px;
        background: #ddd;
        /* Default background for the track */
        outline: none;
        margin: 0;
        cursor: pointer;
    }

    .form-control-range::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #007bff;
        /* Default color for the thumb */
        cursor: pointer;
    }

    .form-control-range::-moz-range-thumb {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #007bff;
        cursor: pointer;
    }

    .error-border {
    border: 2px solid red;

}
</style>
<div class="content">

    <div class="py-4 px-3 px-md-4">
        <div class="mb-3 mb-md-4 d-flex justify-content-between">
            <div class="h3 mb-0">Algorithm</div>

        </div>

        @if($blocks->isNotEmpty())
        @if($discrepanciesFound)
        <div class="alert alert-warning">
            <strong>Attention:</strong> Discrepancies in bed counts have been detected. Please review and resolve them by clicking the button below to submit the updated data and proceed.

        </div>
    @else
        <div class="alert alert-success">
            <strong>Success:</strong> All bed counts are accurate. The algorithm is functioning correctly.

        </div>
    @endif
    <div class="alert alert-info">
        <strong>Note:</strong> We recommend taking the block offline before updating the algorithm to prevent errors.
    </div>
    <!-- Form for Setting Percentages -->
<form action="{{ route('slider.store') }}" method="POST">
    @csrf
    <div class="row">



        @foreach($blocks as $block)


            <div class="col-xl-4 mb-3">
                <div class="card" id="block{{ $block['id'] }}" data-total-beds="{{ array_sum(array_column($block['totalBedsByFloor'], 'count')) }}">
                    <div class="card-body">
                        <h5 class="card-title">
                            {{ $block['name'] }}
                            <small class="text-muted"> (Total Beds: {{ array_sum(array_column($block['totalBedsByFloor'], 'count')) }})</small>
                        </h5>

                        @foreach($block['floors'] as $floorId => $floor)
                            <div class="mb-5">

                                <h6>Floor: {{ $floor['name'] }} (Total Beds: {{ $floor['totalBeds'] }})</h6>

                                <!-- Hidden input field to store bed IDs for JavaScript use -->
                                <input type="hidden" class="bed-ids-data" id="floor{{ $floorId }}-bed-ids" value="{{ json_encode($floor['bed_ids']) }}">
                                <div class="floor-sliders" data-floor-id="{{ $floorId }}">
                                    @foreach($floor['criteria'] as $criteria)
                                        <div class="form-group">
                                            <div class="slider-container">
                                                <label class="slider-label" for="block{{ $block['id'] }}-floor{{ $floorId }}-{{ $criteria }}">{{ $criteria }}</label>
                                                <input type="range"
                                                       id="block{{ $block['id'] }}-floor{{ $floorId }}-{{ $criteria }}"
                                                       name="block{{ $block['id'] }}[floor{{ $floorId }}][{{ $criteria }}]"
                                                       min="0"
                                                       max="{{ $floor['totalBeds'] }}"
                                                       value="{{ $block['sliderData'][$floorId][$criteria] ?? 0 }}"
                                                       class="form-control-range"
                                                       data-criteria="{{ $criteria }}" >
                                                <input type="text"
                                                       id="block{{ $block['id'] }}-floor{{ $floorId }}-{{ $criteria }}-value"
                                                       class="slider-value text-center"
                                                       value="{{ $block['sliderData'][$floorId][$criteria] ?? 0 }}"
                                                       readonly>
                                                <!-- Display distributed bed IDs -->
                                                  <div class="bed-id-container"></div>
                                                  {{-- <p class="distributed-bed-ids"></p> --}}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Single Submit Button for All Blocks -->
    <div class="text-center mb-3">
        <button type="button" class="btn btn-primary" id="submit-all-sliders">Submit All Sliders</button>
    </div>
</form>


        @else

 <div class="container full-height d-flex align-items-center justify-content-center" style="height: 70vh;">
    <div class="" style="width: 18rem;">
        <div class="card-body text-center">
            <i class="gd-alert text-danger" style="font-size: 3rem;"></i><br>
            <small class="card-title">No data available</small>
        </div>
    </div>
</div>




        @endif


    </div>
</div>

<script>
    $(document).ready(function() {
        @foreach($blocks as $block)
            // Set background gradient for the block
            $('#block{{ $block['id'] }} .form-control-range').each(function() {
                $(this).css('background', 'linear-gradient(to right, {{ $block['gradient_start'] }}, {{ $block['gradient_end'] }})');
            });

            // Optionally, log the floor names and other details for debugging
            // console.log('Block Name: {{ $block['name'] }}');
            @foreach($block['floors'] as $floorId => $floor)
                // console.log('Floor ID: {{ $floorId }} - Name: {{ $floor['name'] }}');
            @endforeach
        @endforeach
    });
</script>


<script>
    // Function to show toast notifications
    function showToast(toastId, message) {
        var toastElement = $('#' + toastId);
        toastElement.find('.toast-body').text(message);
        toastElement.toast('show');
    }
</script>

<script>
    $(document).ready(function() {
        function showToast(toastId, message) {
            var toastElement = $('#' + toastId);
            toastElement.find('.toast-body').text(message);
            toastElement.toast('show');
        }

        function initializeSliders() {
            $('.floor-sliders').each(function() {
                var $sliders = $(this).find('.form-control-range');
                var totalBeds = parseInt($sliders.first().attr('max'), 10);
                var bedIds = JSON.parse($(this).siblings('.bed-ids-data').val()); // Retrieve bed IDs from hidden input
                var numSliders = $sliders.length;

                // Check if sliders already have values set
                var alreadyInitialized = $sliders.toArray().some(slider => parseInt($(slider).val()) > 0);

                if (!alreadyInitialized) {
                    // If no data is present, initialize the sliders with default values
                    var initialValue = Math.floor(totalBeds / numSliders);
                    var remainder = totalBeds % numSliders;
                    var bedIdsIndex = 0;

                    $sliders.each(function(index) {
                        var value = initialValue + (index < remainder ? 1 : 0);
                        $(this).val(value);
                        $(this).siblings('.slider-value').val(value);
                        $(this).attr('max', totalBeds); // Set max value for slider

                        var sliderBedIds = bedIds.slice(bedIdsIndex, bedIdsIndex + value);
                        $(this).data('bed-ids', sliderBedIds);
                        var $bedIdContainer = $(this).siblings('.bed-id-container');
                        $bedIdContainer.empty(); // Clear previous inputs

                        sliderBedIds.forEach(function(bedId) {
                            $bedIdContainer.append(
                                `<input type="hidden" name="bed_ids[${bedId}]" value="${bedId}" />`
                            );
                        });

                        $(this).siblings('.distributed-bed-ids').text('Distributed Bed IDs: ' + sliderBedIds.join(', '));
                        bedIdsIndex += value; // Move to the next segment of bed IDs
                    });
                } else {
                    // If data exists, still update the bed IDs based on the current values
                    updateBedIds();
                }

                // Function to update bed IDs and inputs based on slider values
                function updateBedIds() {
                    var total = 0;
                    var sliderValues = [];
                    $sliders.each(function() {
                        var value = parseInt($(this).val(), 10);
                        sliderValues.push(value);
                        total += value;
                    });

                    if (total > totalBeds) {
                        showToast('error-toast', 'Total number of beds cannot exceed ' + totalBeds);
                        return; // Stop further processing if the total exceeds
                    }

                    var bedIdIndex = 0;
                    $sliders.each(function(index) {
                        var value = sliderValues[index];
                        var sliderBedIds = bedIds.slice(bedIdIndex, bedIdIndex + value);

                        // Update the distributed bed IDs
                        $(this).data('bed-ids', sliderBedIds);
                        var $bedIdContainer = $(this).siblings('.bed-id-container');
                        $bedIdContainer.empty(); // Clear previous inputs

                        sliderBedIds.forEach(function(bedId) {
                            $bedIdContainer.append(
                                `<input type="hidden" name="bed_ids[${bedId}]" value="${bedId}" />`
                            );
                        });

                        $(this).siblings('.distributed-bed-ids').text('Distributed Bed IDs: ' + sliderBedIds.join(', '));

                        bedIdIndex += value; // Move to the next segment of bed IDs
                    });
                }

                // Event handler for slider input
                $sliders.on('input', function() {
                    var total = 0;
                    $sliders.each(function() {
                        total += parseInt($(this).val(), 10);
                    });

                    if (total > totalBeds) {
                        showToast('error-toast', 'Total number of beds cannot exceed ' + totalBeds);
                        $(this).val(Math.max(parseInt($(this).attr('min')), parseInt($(this).val()) - (total - totalBeds)));
                        total -= (total - totalBeds); // Adjust total
                    }

                    $(this).siblings('.slider-value').val($(this).val());
                    updateBedIds(); // Update bed IDs when slider value changes
                });

                // Initialize bed IDs based on existing values or default values
                updateBedIds();
            });
        }

        function validateSliders() {
            var isValid = true;

            $('.floor-sliders').each(function() {
                var $sliders = $(this).find('.form-control-range');
                var totalBeds = parseInt($sliders.first().attr('max'), 10);
                var total = 0;

                // Reset any previous error indication for all sliders in this group
                $sliders.removeClass('error-border');

                $sliders.each(function() {
                    total += parseInt($(this).val(), 10);
                });

                if (total !== totalBeds) {
                    // Add error class to all sliders in this group if total doesn't match
                    $sliders.addClass('error-border');

                    // Display error toast
                    showToast('error-toast', 'The sum of slider values does not match the total beds for a floor.');

                    isValid = false;
                    return false; // Exit each loop early
                }
            });

            return isValid;
        }

        initializeSliders();

        $('#submit-all-sliders').on('click', function(event) {
            event.preventDefault(); // Prevent default form submit behavior

            $('#overlay').css('display', 'flex');
            $('#submit-all-sliders').prop('disabled', true);

            // Validate sliders before submitting
            if (!validateSliders()) {
                $('#overlay').fadeOut();
                $('#submit-all-sliders').prop('disabled', false);
                return; // Stop submission if validation fails
            }

            var blockData = {};

            $('.card').each(function() {
                var id = $(this).attr('id');
                if (id) {
                    var blockId = id.replace('block', '');
                    var sliders = $(this).find('.floor-sliders');

                    sliders.each(function() {
                        var floorId = $(this).data('floor-id');
                        var $sliders = $(this).find('.form-control-range');
                        var sliderData = {};
                        var bedIdsData = {}; // To store bed IDs associated with each criterion

                        $sliders.each(function() {
                            var criteria = $(this).data('criteria');
                            var value = parseInt($(this).val(), 10); // Ensure the value is an integer
                            sliderData[criteria] = value;

                            // Retrieve hidden bed IDs for this criteria
                            var bedIds = $(this).siblings('.bed-id-container').find('input[type="hidden"]').map(function() {
                                return $(this).val();
                            }).get();
                            bedIdsData[criteria] = bedIds;
                        });

                        if (!blockData[blockId]) {
                            blockData[blockId] = {};
                        }
                        blockData[blockId][floorId] = {
                            sliderData: sliderData,
                            bedIdsData: bedIdsData
                        };
                    });
                } else {
               //     console.error('ID is undefined for card element');
                }
            });

            // Log blockData to verify its structure
          //  console.log('Block Data:', JSON.stringify(blockData));

            // Make sure blockData is not empty before sending
            if (Object.keys(blockData).length === 0) {
                showToast('error-toast', 'No data to save.');
                return;
            }

            $.ajax({
                url: '{{ route('slider.store') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    data: blockData
                },
                success: function(response) {
                    $('#overlay').fadeOut();
                    $('#submit-all-sliders').prop('disabled', false);
                    control(); // Call any additional functions you need
                    showToast('success-toast', 'Data saved successfully.');
                },
                error: function(xhr) {
    $('#overlay').fadeOut();
    $('#submit-all-sliders').prop('disabled', false);

    // Log the raw response to inspect it
    console.log('Raw response:', xhr.responseText);

    var errorMessage = 'An error occurred.';
    try {
        var responseJSON = JSON.parse(xhr.responseText);
        var errors = responseJSON.errors;

        if (errors) {
            errorMessage = Object.values(errors).join(' ');
        }
    } catch (e) {
        errorMessage = 'Failed to parse server response.';
    }

    showToast('error-toast', errorMessage);
}

            });
        });
    });
    </script>
