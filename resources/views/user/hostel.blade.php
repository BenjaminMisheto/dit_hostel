<div class="content">
    <div class="py-4 px-3 px-md-4">
        <div class="mb-3 mb-md-4 d-flex justify-content-between">
            <div class="h3 mb-0">Hostel</div>
        </div>
        @if ($user->application == 1)
        <div class="row justify-content-start">
            <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-3 mb-md-4">
                <!-- Card -->
                <div class="card block-card">
                    <div class="card-body">
                        <div class="row text-dark">
                            <div class="col-6">
                                <small class="card-title">{{ $user->block->name }}</small>
                            </div>
                            <div class="col-6">
                                <small>TZS {{ number_format($user->block->price, 2, '.', ',') }}</small>
                            </div>
                        </div>
                    </div>

                    <div style="overflow: hidden">
                        <img src="{{ $user->block->image_data }}" class="card-img-top block-img" alt="Block Image">
                        <button class="select-btn">Selected</button>
                    </div>
                    <div class="card-body">
                        <div class="row text-dark">
                            <div class="col-6">
                                <small class="card-title">Floor number {{ $user->floor->floor_number }}</small>
                            </div>
                            <div class="col-6">
                                <small class="card-title">Bed number {{ $user->bed->bed_number }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        @if ($user->confirmation != 1)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Warning!</strong> Please search and confirm your profile before proceeding to this stage.
        </div>
        @else
        <div class="row">
            @if ($blocks->isEmpty())
            <div class="col-12">
                <!-- Card -->
                <div class="card alert alert-warning">
                    <div class="card-body ">
                        <div class="text-center ">
                            <small class="card-title">Sorry! {{$user->name}}<br>No block available</small>
                        </div>
                    </div>
                </div>
            </div>
            @else
            @foreach ($blocks as $block)
            @php
                // Initialize a flag to check if any floor matches the user's gender
                $shouldDisplayBlock = false;

                // Convert user gender to lowercase
                $userGenderLower = strtolower($user->gender);

                // Filter the floors of the block to check if any floor has the user's gender in its gender JSON data
                $matchingFloors = $block->floors->filter(function($floor) use ($userGenderLower, &$shouldDisplayBlock) {
                    // Ensure the gender field is an array (decoded from JSON)
                    $genderArray = is_array($floor->gender) ? $floor->gender : json_decode($floor->gender, true);
                    // Convert gender array values to lowercase
                    $genderArrayLower = array_map('strtolower', $genderArray);

                    // Check if the user's gender is in the gender array of the floor (case-insensitive)
                    if (in_array($userGenderLower, $genderArrayLower)) {
                        $shouldDisplayBlock = true; // Set flag to true if a match is found
                    }
                    return $shouldDisplayBlock;
                });
            @endphp

            @if ($shouldDisplayBlock)
                <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-3 mb-md-4">
                    <!-- Card -->
                    <a href="#" onclick="selectBlock({{ $block->id }})" id="block-{{ $block->id }}" class="block-link">
                        <div class="card block-card">
                            <div class="card-body">
                                <div class="row text-dark">
                                    <div class="col-6">
                                        <small class="card-title">{{ $block->name }}</small>
                                    </div>
                                    <div class="col-6">
                                        <small class="">TZS
                                            {{ number_format($block->price, 2, '.', ',') }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="" style="overflow: hidden">
                                <img src="{{ $block->image_data }}" class="card-img-top block-img" alt="Block Image">
                                <button class="select-btn">Select</button>
                            </div>
                        </div>
                    </a>
                </div>
            @endif
            @endforeach
            @endif
        </div>
        @endif
        @endif
    </div>
</div>


<style>
    .block-card {
        position: relative;
        overflow: hidden;
    }

    .block-img {
        transition: transform 0.3s ease-in-out;
    }

    .block-card:hover .block-img {
        transform: scale(1.1);
    }

    .select-btn {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: rgba(65, 124, 233, 0.7);
        color: white;
        border: none;
        padding: 10px 20px;
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
        cursor: pointer;
    }

    .block-card:hover .select-btn {
        opacity: 1;
    }

    /* Make the button always visible on mobile devices */
    @media (max-width: 767.98px) {
        .select-btn {
            opacity: 1;
        }
    }
</style>
