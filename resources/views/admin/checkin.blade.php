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
            <div class="h3 mb-0">Check-In</div>
            <p>{{$semester->name ?? 'No semester found'}}</p>

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
                            <td>
                                @if ($student->checkin === 1)

                                    <button class="btn btn-sm btn-toggle btn-lightred"
                                            data-user-id="{{ $student->id }}"
                                            data-status="check-in"
                                            onclick="toggleStatus(this)">
                                        Check-In
                                    </button>
                                @elseif ($student->checkin === 0)
                                <button class="btn btn-sm btn-toggle alert-warning"  disabled><i class="gd-time"></i> Pending</button>
                                @else

                                @if ($student->checkin === 2)
                                <button class="btn btn-sm btn-toggle btn-lightgreen" disabled>
                                    Checked-In
                                 </button>

                                @else
                                <button class="btn btn-sm btn-toggle btn-lightgreen"
                                data-user-id="{{ $student->id }}"
                                data-status="checked-in"
                                onclick="toggleStatus(this)">
                            Checked-In
                        </button>



                                @endif


                                @endif
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
    $(document).ready(function() {
        $('#searchInput').on('input', function() {
            let query = $(this).val().trim();

            if (query.length >= 3) {
                $('#spinner').show();

                $.ajax({
                    url: "{{ route('search.checkin') }}", // Updated route
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
<script>
    function toggleStatus(button) {
    const userId = button.getAttribute('data-user-id');
    const currentStatus = button.getAttribute('data-status');
    const newStatus = currentStatus === 'checked-in' ? 'check-in' : 'checked-in';

    // Show spinner while processing
    button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
    button.disabled = true;
    button.classList.remove('btn-lightgreen', 'btn-lightred');
    button.classList.add('btn-light'); // Optional: Temporary class while loading

    // Send AJAX request to update status on the server
    fetch(`/update-checkin-status/${userId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ status: newStatus })
    })
    .then(response => response.json())
    .then(data => {
        // Handle success or error
        if (data.success) {
            // Show success toast
            showToast('success-toast', 'Status updated successfully');
            // Update button text and status
            button.textContent = newStatus === 'checked-in' ? 'Checked-In' : 'Check-In';
            button.setAttribute('data-status', newStatus);
            button.classList.add(newStatus === 'checked-in' ? 'btn-lightgreen' : 'btn-lightred');
            button.classList.remove('btn-light'); // Remove the temporary class
        } else {
            // Show error toast
            showToast('error-toast', data.message); // Show server error message
            button.textContent = currentStatus === 'checked-in' ? 'Checked-In' : 'Check-In';
        }
    })
    .catch(error => {
        // Show error toast
        showToast('error-toast', 'An error occurred. Please try again.');
        button.textContent = currentStatus === 'checked-in' ? 'Checked-In' : 'Check-In';
    })
    .finally(() => {
        button.disabled = false; // Re-enable button
    });
}

function showToast(toastId, message) {
    var toastElement = document.getElementById(toastId);
    toastElement.querySelector('.toast-body').textContent = message;
    $(toastElement).toast('show');
}

</script>
