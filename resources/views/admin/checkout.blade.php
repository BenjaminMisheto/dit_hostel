<style>
    /* Hide columns progressively based on screen size */
    /* @media (max-width: 1200px) {
        .col-sponsor, .col-phone, .col-gender, .col-nationality, .col-course, .col-email, .col-payment-status { display: none; }
    }
    @media (max-width: 992px) {
        .col-phone, .col-gender, .col-nationality, .col-course, .col-email, .col-payment-status { display: none; }
    }
    @media (max-width: 768px) {
        .col-gender, .col-nationality, .col-course, .col-email, .col-payment-status { display: none; }
    }
    @media (max-width: 576px) {
        .col-nationality, .col-course, .col-email, .col-payment-status { display: none; }
    }
    @media (max-width: 400px) {
        .col-course, .col-email, .col-payment-status { display: none; }
    } */

    th.sorted {
    background-color: #f8f9fa; /* Light gray background for the sorted column */
    color: #007bff; /* Blue text color for the sorted column */
    font-weight: bold; /* Bold text for the sorted column */
}

th.asc::after,
th.desc::after {
    content: " "; /* Add space after the text */
    display: inline-block;
    margin-left: 5px;
}

th.asc::after {
    content: "▲"; /* Up arrow for ascending order */
}

th.desc::after {
    content: "▼"; /* Down arrow for descending order */
}
.btn-lightgreen {
    background-color: #d4edda; /* Light green */
    color: #155724; /* Dark green for text */
    border: 1px solid #c3e6cb; /* Green border */
}

.btn-lightred {
    background-color: #f8d7da; /* Light red */
    color: #721c24; /* Dark red for text */
    border: 1px solid #f5c6cb; /* Red border */
}
.table td, .table th {
        white-space: nowrap; /* Prevent text from wrapping */
        overflow: hidden; /* Hide overflow */
        text-overflow: ellipsis; /* Add ellipsis for overflowed text */
    }
</style>
<div class="content">
    <div class="py-4 px-3 px-md-4">
        <div class="mb-3 mb-md-4 d-flex justify-content-between align-items-center">
            <div class="h3 mb-0">Check-Out</div>

        </div>
        <div class="form-group position-relative">
            <div class="input-group">
                <input type="text" id="searchInput" class="form-control" placeholder="Search by name or number">
                <div class="input-group-append">
                    <div id="spinner" class="spinner-border spinner-border-sm text-primary ms-2" role="status" style="display: none;">

                    </div>
                </div>
            </div>
        </div>

        <!-- Container for search results -->
        <div id="searchResults" class="mt-2">
            <!-- Results will be populated here -->
        </div>



        @if($paginatedUsers->isEmpty())

        <div class="container full-height d-flex align-items-center justify-content-center" style="height: 70vh;">
            <div class="" style="width: 18rem;">
                <div class="card-body text-center">
                    <i class="gd-alert text-danger" style="font-size: 3rem;"></i><br>
                    <small class="card-title">No data available</small>
                </div>
            </div>
        </div>

        @else
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col" style="cursor: pointer">#</th>
                        <th scope="col" style="cursor: pointer">Img</th>
                        <th scope="col" data-sort="name" style="cursor: pointer">Name</th>
                        <th scope="col" data-sort="number" style="cursor: pointer">Reg No</th>
                        <th scope="col" data-sort="course" style="cursor: pointer">course</th>
                        <th scope="col" data-sort="floor" style="cursor: pointer">Floor</th>
                        <th scope="col" data-sort="room" style="cursor: pointer">Room</th>
                        <th scope="col" data-sort="bed" style="cursor: pointer">Bed</th>
                        <th scope="col" data-sort="pay" style="cursor: pointer">Payment</th>
                        <th scope="col" data-sort="pay" style="cursor: pointer">Status</th>
                        <th scope="col" style="cursor: pointer">Action</th>
                    </tr>
                </thead>
                <tbody id="studentTableBody">
                    @foreach($paginatedUsers as $index => $student)
                        <tr>
                            <td>{{ $paginatedUsers->firstItem() + $index }}</td>
                            <td><img class="avatar rounded-circle" src="{{ $student->profile_photo_path }}" alt="Image Description"></td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->registration_number }}</td>
                            <td class="col-sponsor">{{ $student->course ?? 'N/A' }}</td>
                            <td class="col-phone">{{optional($student->bed->floor)->floor_number ?? 'N/A' }}</td>
                            <td class="col-gender">{{ optional($student->bed->room)->room_number ?? 'N/A' }}</td>
                            <td class="col-course">{{ $student->bed->bed_number ?? 'N/A' }}</td>
                            <td class="col-course"> {{ !empty($student->payment_status) ? 'Paid' : 'Not Paid' }}</td>
                            <td class="col-course">  @if ($student->checkout === 1) <span class="text-success">Check-Out</span>  @else <span class="text-danger">Waiting</span>  @endif </td>
                            <td>
                                <button class="btn btn-sm shadow-sm" onclick="checkoutAction({{ $student->bed->id }})">
                                    <i class="gd-arrow-top-right"></i>
                                </button>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

            <!-- Pagination Controls -->
            <div class="d-flex justify-content-center mt-4">
                {{ $paginatedUsers->onEachSide(1)->links('pagination::bootstrap-4') }}
            </div>

        @endif
    </div>
</div>
<script>
    function checkoutAction(bedId) {
    // Deactivate all navigation links
    const selectors = [
        "#nav_profile",
        "#nav_aplication",
        "#nav_elligable",
        "#nav_result",
        "#nav_control",
        "#nav_setting",
        "#nav_report",
        "#nav_checkout",
        "#nav_checkin",
    ];

    selectors.forEach(function(selector) {
        $(selector).removeClass("active");
    });
    $("#nav_checkout").addClass("active"); // Set checkout as active

    // Show loading spinner
    $("#dash").html(
        '<div class="spinner-container">' +
        '<div class="black show d-flex align-items-center justify-content-center">' +
        '<div class="spinner-border lik" style="width: 3rem; height: 3rem;" role="status">' +
        '<span class="sr-only">Loading...</span>' +
        '</div>' +
        '</div>' +
        '</div>'
    );

    // Define the checkout URL dynamically
    let url = `{{ url('bed/checkout') }}/${bedId}`;

    // Load the checkout page for the bed ID
    $("#dash").load(url, (response, status, xhr) => {
        if (status === "error") {
            const msg = `Sorry, but there was an error: ${xhr.status} ${xhr.statusText}`;
            $("#error").html(msg); // Display error message
        }
    });
}

</script>


<script>
    $(document).ready(function() {
        $('#searchInput').on('input', function() {
            let query = $(this).val().trim();

            if (query.length >= 3) {
                $('#spinner').show();

                $.ajax({
                    url: "{{ route('search.checkout') }}", // Updated route
                    method: 'GET',
                    data: {
                        query: query
                    },
                    success: function(response) {
                        $('#searchResults').html(response); // Update the results container with HTML
                    },
                    error: function(xhr) {
                        const msg = `Sorry, but there was an error: ${xhr.status} ${xhr.statusText}`;
                        $('#searchResults').html(`<p class="text-danger">${msg}</p>`);
                    },
                    complete: function() {
                        $('#spinner').hide(); // Hide spinner once request is complete
                    }
                });
            } else {
                $('#searchResults').empty(); // Clear results if query is less than 3 characters
            }
        });
    });
</script>


<script>
    $(document).ready(function() {
        $('th[data-sort]').on('click', function() {
            var table = $(this).closest('table');
            var tbody = table.find('tbody');
            var rows = tbody.find('tr').toArray();
            var index = $(this).index();
            var order = $(this).hasClass('asc') ? 'desc' : 'asc';

            // Remove 'asc', 'desc', and 'sorted' classes from all headers
            table.find('th').removeClass('asc desc sorted');

            // Add the current order class and the 'sorted' class to the clicked header
            $(this).addClass(order + ' sorted');

            // Sort rows based on the column index
            rows.sort(function(a, b) {
                var aValue = $(a).find('td').eq(index).text().toLowerCase();
                var bValue = $(b).find('td').eq(index).text().toLowerCase();

                // Handle numeric values
                if ($.isNumeric(aValue) && $.isNumeric(bValue)) {
                    return order === 'asc' ? aValue - bValue : bValue - aValue;
                }

                // Handle text values
                return order === 'asc' ? aValue.localeCompare(bValue) : bValue.localeCompare(aValue);
            });

            // Append sorted rows back to the tbody
            tbody.append(rows);
        });
    });
</script>
