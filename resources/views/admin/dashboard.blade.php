@extends('layouts.admin') @section('content')
{{-- <p>This page has been viewed {{ $viewCount }} times.</p> --}}
<div class="content">
    <div class="py-4 px-3 px-md-4">
        <div class="mb-3 mb-md-4 d-flex justify-content-between">
            <div class="h3 mb-0">Dashboard </div>
            <p>{{ session('semester') ?? 'No semester found' }}</p>
        </div>

        <div class="row">
            @php
            // Calculate the maximum value in the data set
            $max_value = max($monthly_views_data);
            // Add some buffer to the max value for better visualization (e.g., 10% more)
            $high_value = $max_value + ($max_value * 0.1);
        @endphp

        <div class="col-md-6 col-xl-4 mb-3 mb-md-4">
            <!-- Card -->
            <div class="card flex-row align-items-center p-3 p-md-4">
                <div>
                    <h5 class="lh-1 mb-0">Views</h5>
                    <small>{{ $total_views }} (+{{ $monthly_views }})</small>
                </div>
                <div class="js-area-chart chart--points-invisible chart--labels-hidden py-1 ml-auto"
                    data-series='[
                        [
                            {"value":"{{ $monthly_views_data[0] ?? 0 }}"},
                            {"value":"{{ $monthly_views_data[1] ?? 0 }}"},
                            {"value":"{{ $monthly_views_data[2] ?? 0 }}"},
                            {"value":"{{ $monthly_views_data[3] ?? 0 }}"},
                            {"value":"{{ $monthly_views_data[4] ?? 0 }}"},
                            {"value":"{{ $monthly_views_data[5] ?? 0 }}"},
                            {"value":"{{ $monthly_views_data[6] ?? 0 }}"},
                            {"value":"{{ $monthly_views_data[7] ?? 0 }}"},
                            {"value":"{{ $monthly_views_data[8] ?? 0 }}"},
                            {"value":"{{ $monthly_views_data[9] ?? 0 }}"},
                            {"value":"{{ $monthly_views_data[10] ?? 0 }}"},
                            {"value":"{{ $monthly_views_data[11] ?? 0 }}"}
                        ]
                    ]'
                    data-width="100"
                    data-height="40"
                    data-high="{{ $high_value }}"
                    data-is-line-smooth='["simple"]'
                    data-line-width='["1px"]'
                    data-line-colors='["#0cdcB9"]'
                    data-fill-opacity=".3"
                    data-is-fill-colors-gradient="true"
                    data-fill-colors='[
                        ["rgba(28,240,221,.6)","rgba(255,255,255,.6)"]
                    ]'
                    data-is-show-tooltips="true"
                    data-is-tooltips-append-to-body="true"
                    data-tooltip-custom-class="chart-tooltip chart-tooltip--none-triangle d-flex align-items-center small text-white p-2 mt-5 ml-5"
                    data-tooltip-badge-markup='<span class="indicator indicator-sm bg-secondary rounded-circle mr-1"></span>'
                    data-is-show-points="true"
                    data-point-custom-class='chart__point--hidden'
                    data-point-dimensions='{"width":8,"height":8}'
                ></div>
            </div>
            <!-- End Card -->
        </div>


        @php
        // Calculate the maximum value in the visitors data set
        $max_visitors_value = max($monthly_visitors_data);
        // Add some buffer to the max value for better visualization (e.g., 10% more)
        $high_visitors_value = $max_visitors_value + ($max_visitors_value * 0.1);
    @endphp

    <!-- Inside your Blade template -->
    <div class="col-md-6 col-xl-4 mb-3 mb-md-4">
        <!-- Card -->
        <div class="card flex-row align-items-center p-3 p-md-4">
            <div>
                <h5 class="lh-1 mb-0">Visitors</h5>
                <small>{{ $total_visitors }} (+{{ $new_visitors }})</small>
            </div>
            <div class="js-area-chart chart--points-invisible chart--labels-hidden py-2 ml-auto"
                data-series='[
                    [
                        {"value":"{{ $monthly_visitors_data['january'] ?? 0 }}"},
                        {"value":"{{ $monthly_visitors_data['february'] ?? 0 }}"},
                        {"value":"{{ $monthly_visitors_data['march'] ?? 0 }}"},
                        {"value":"{{ $monthly_visitors_data['april'] ?? 0 }}"},
                        {"value":"{{ $monthly_visitors_data['may'] ?? 0 }}"},
                        {"value":"{{ $monthly_visitors_data['june'] ?? 0 }}"},
                        {"value":"{{ $monthly_visitors_data['july'] ?? 0 }}"},
                        {"value":"{{ $monthly_visitors_data['august'] ?? 0 }}"},
                        {"value":"{{ $monthly_visitors_data['september'] ?? 0 }}"},
                        {"value":"{{ $monthly_visitors_data['october'] ?? 0 }}"},
                        {"value":"{{ $monthly_visitors_data['november'] ?? 0 }}"},
                        {"value":"{{ $monthly_visitors_data['december'] ?? 0 }}"}
                    ]
                ]'
                data-width="100"
                data-height="40"
                data-high="{{ $high_visitors_value }}"
                data-is-line-smooth='[false]'
                data-line-width='["1px"]'
                data-line-colors='["#8069f2"]'
                data-fill-opacity=".3"
                data-is-fill-colors-gradient="true"
                data-fill-colors='[
                    ["rgba(128,105,242,.7)","rgba(255,255,255,.6)"]
                ]'
                data-is-show-tooltips="true"
                data-is-tooltips-append-to-body="true"
                data-tooltip-custom-class="chart-tooltip chart-tooltip--none-triangle d-flex align-items-center small text-white p-2 mt-5 ml-5"
                data-tooltip-badge-markup='<span class="indicator indicator-sm bg-primary rounded-circle mr-1"></span>'
                data-is-show-points="true"
                data-point-custom-class='chart__point--hidden'
                data-point-dimensions='{"width":8,"height":8}'
            ></div>
        </div>
        <!-- End Card -->
    </div>


    @php
    // Calculate the maximum value in the student applications data set
    $max_student_applications_value = max($monthly_student_applications);
    // Add some buffer to the max value for better visualization (e.g., 10% more)
    $high_student_applications_value = $max_student_applications_value + ($max_student_applications_value * 0.1);
@endphp

<div class="col-md-6 col-xl-4 mb-3 mb-md-4">
    <!-- Card -->
    <div class="card flex-row align-items-center p-3 p-md-4">
        <div>
            <h5 class="lh-1 mb-0">Application</h5>
            <small>{{ $total_students }} (+{{ $new_students_count }})</small>
        </div>
        <div class="js-area-chart chart--points-invisible chart--labels-hidden py-2 ml-auto"
             data-series='[
               [
                 {"value":"{{ $monthly_student_applications[0] ?? 0 }}"},
                 {"value":"{{ $monthly_student_applications[1] ?? 0 }}"},
                 {"value":"{{ $monthly_student_applications[2] ?? 0 }}"},
                 {"value":"{{ $monthly_student_applications[3] ?? 0 }}"},
                 {"value":"{{ $monthly_student_applications[4] ?? 0 }}"},
                 {"value":"{{ $monthly_student_applications[5] ?? 0 }}"},
                 {"value":"{{ $monthly_student_applications[6] ?? 0 }}"},
                 {"value":"{{ $monthly_student_applications[7] ?? 0 }}"},
                 {"value":"{{ $monthly_student_applications[8] ?? 0 }}"},
                 {"value":"{{ $monthly_student_applications[9] ?? 0 }}"},
                 {"value":"{{ $monthly_student_applications[10] ?? 0 }}"},
                 {"value":"{{ $monthly_student_applications[11] ?? 0 }}"}
               ]
             ]'
             data-is-hide-area="true"
             data-width="123"
             data-height="42"
             data-high="{{ $high_student_applications_value }}"
             data-is-line-smooth='[false]'
             data-line-width='["2px"]'
             data-line-colors='["#8069f2"]'
             data-is-show-tooltips="true"
             data-is-tooltips-append-to-body="true"
             data-tooltip-custom-class="chart-tooltip chart-tooltip--none-triangle d-flex align-items-center small text-white p-2 mt-5 ml-5"
             data-tooltip-badge-markup='<span class="indicator indicator-sm bg-primary rounded-circle mr-1"></span>'
             data-is-show-points="true"
             data-point-custom-class='chart__point--hidden'
             data-point-dimensions='{"width":8,"height":8}'></div>
    </div>
    <!-- End Card -->
</div>


            <div class="col-md-6 col-xl-4 mb-3 mb-xl-4">
                <!-- Widget -->
                <div class="card flex-row align-items-center p-3 p-md-4">
                    <div class="icon icon-lg bg-soft-warning rounded-circle mr-3">
                        <i class="gd-key icon-text d-inline-block text-warning"></i>
                    </div>
                    <div>
                        <h4 class="lh-1 mb-1">{{ $total_beds }}</h4> <!-- Display the total number of beds -->
                        <h6 class="mb-0">Total Beds</h6>
                    </div>
                </div>
                <!-- End Widget -->
            </div>

            <div class="col-md-6 col-xl-4 mb-3 mb-xl-4">
                <!-- Widget -->
                <div class="card flex-row align-items-center p-3 p-md-4">
                    <div class="icon icon-lg bg-soft-secondary rounded-circle mr-3">
                        <i class="gd-key icon-text d-inline-block text-success"></i>
                    </div>
                    <div>
                        <h4 class="lh-1 mb-1">{{ $total_occupied_beds }}/{{$total_beds}}</h4> <!-- Display the total number of occupied beds -->
                        <h6 class="mb-0">Total Occupied Bed</h6>
                    </div>
                </div>
                <!-- End Widget -->
            </div>


            <div class="col-md-6 col-xl-4 mb-3 mb-xl-4">
                <!-- Widget -->
                <div class="card flex-row align-items-center p-3 p-md-4">
                    <div class="icon icon-lg bg-soft-info rounded-circle mr-3">
                        <i class="gd-key icon-text d-inline-block text-info"></i>
                    </div>
                    <div>
                        <h4 class="lh-1 mb-1">{{$total_beds -$total_occupied_beds}}</h4>
                        <h6 class="mb-0">Total Remain Bed</h6>
                    </div>

                </div>
                <!-- End Widget -->
            </div>

            <div class="col-md-6 col-xl-4 mb-3 mb-xl-4">
                <!-- Widget -->
                <div class="card flex-row align-items-center p-3 p-md-4 alert-success">
                    <div class="icon icon-lg bg-soft-success rounded-circle mr-3">
                        <i class="gd-key icon-text d-inline-block text-success"></i>
                    </div>
                    <div>
                        <h4 class="lh-1 mb-1">{{$total_Open_beds}}/{{ $total_beds }}</h4>
                        <h6 class="mb-0">Total Open Bed</h6>
                    </div>

                </div>
                <!-- End Widget -->
            </div>
            <div class="col-md-6 col-xl-4 mb-3 mb-xl-4">
                <!-- Widget -->
                <div class="card flex-row align-items-center p-3 p-md-4 alert-warning">
                    <div class="icon icon-lg bg-soft-warning rounded-circle mr-3">
                        <i class="gd-key icon-text d-inline-block text-warning"></i>
                    </div>
                    <div>
                        <h4 class="lh-1 mb-1">{{$total_reserve_beds}}/{{ $total_beds }}</h4>
                        <h6 class="mb-0">Total Reserved Bed</h6>
                    </div>

                </div>
                <!-- End Widget -->
            </div>
            <div class="col-md-6 col-xl-4 mb-3 mb-xl-4">
                <!-- Widget -->
                <div class="card flex-row align-items-center p-3 p-md-4 alert-danger">
                    <div class="icon icon-lg bg-soft-danger rounded-circle mr-3">
                        <i class="gd-key icon-text d-inline-block text-danger"></i>
                    </div>
                    <div>
                        <h4 class="lh-1 mb-1">{{$total_under_maintenance_beds}}/{{ $total_beds }}</h4>
                        <h6 class="mb-0">Total Maintanance Bed</h6>
                    </div>

                </div>
                <!-- End Widget -->
            </div>
            <div class="col-md-12 col-xl-12 mb-3 mb-xl-4">
                <div class="d-flex justify-content-between mb-1">
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
                    </div>


            <div class="col-md-12 col-xl-4 mb-3 mb-md-4">
                <!-- Card -->
                <div class="card h-100">
                    <div class="card-header d-flex">
                        <h5 class="h6 font-weight-semi-bold text-uppercase mb-0">Gender Ratio</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="border-bottom text-center p-3 p-md-4 pb-md-6">
                            <div class="js-donut-chart position-relative d-flex mx-auto mb-3 mb-md-4"
                                style="width: 130px; height: 130px;"
                                data-series="[{{ $male_students }}, {{ $female_students }}]"
                                data-border-width="12"
                                data-slice-margin="2"
                                data-start-angle="0"
                                data-fill-colors='["#add8e6", "#ff69b4"]'
                                data-is-show-tooltips="true"
                                data-tooltip-currency="%"
                                data-is-tooltip-currency-reverse="true"
                                data-tooltip-custom-class="chart-tooltip chart-tooltip--triangle-right chart-tooltip--black small text-white px-2 py-1 mt-5 ml-n5">
                            </div>

                            <div class="small text-muted">Total Students ≈ {{ $total_students }}</div>
                        </div>

                        <div class="border-bottom media align-items-center p-3">
                            <div class="media-body d-flex align-items-center mr-2">
                                <!-- Color indicator -->
                                <div class="indicator" style="width: 12px; height: 12px; background-color: #add8e6; border-radius: 50%; margin-right: 8px;"></div>
                                <span>Male Students</span>
                                <span class="ml-auto" id="male-students-count">{{ $male_students }} ({{ $male_percentage }}%)</span>
                            </div>

                            <i class="gd-check icon-text icon-text-xs d-flex text-primary ml-auto"></i>
                        </div>

                        <div class="media align-items-center p-3">
                            <div class="media-body d-flex align-items-center mr-2">
                                <!-- Color indicator -->
                                <div class="indicator" style="width: 12px; height: 12px; background-color: #ff69b4; border-radius: 50%; margin-right: 8px;"></div>
                                <span>Female Students</span>
                                <span class="ml-auto" id="female-students-count">{{ $female_students }} ({{ $female_percentage }}%)</span>
                            </div>

                            <i class="gd-check icon-text icon-text-xs d-flex text-primary ml-auto"></i>
                        </div>
                    </div>
                </div>
                <!-- End Card -->
            </div>



            <div class="col-lg-6 col-xl-8 mb-3 mb-lg-4">
                <!-- Card -->
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="h6 text-uppercase font-weight-semi-bold mb-0">Recent Applications</h5>
                    </div>
                    <div class="card-body p-0">
                        @forelse($recent_applications as $application)
                            <div class="border-top p-3 px-md-4 mx-0">
                                <div class="row justify-content-between small mb-2">
                                    <div class="col">
                                        <span class="text-primary mr-3">{{ $application->block->name ?? 0 }}</span>

                                        <span class="mr-1">{{ $application->payment_status }}</span>
                                        <i class="gd-check text-success mr-3"></i>
                                    </div>

                                    <div class="col-auto text-muted">
                                        {{ $application->created_at->diffForHumans() }}
                                    </div>
                                </div>

                                {{ $application->name }}
                            </div>
                        @empty
                            <div class="border-top p-3 px-md-4 mx-0">
                                <p>No recent applications.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                <!-- End Card -->
            </div>





        </div>

    </div>
</div>
@endsection
