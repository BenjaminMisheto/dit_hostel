
<style>
    .table-fixed {
        width: 100%;
        white-space: nowrap;
    }

    .table-fixed thead th {
        position: sticky;
        top: 0;
        /* background-color: #f8f9fa; Optional: to match alert-secondary */
        z-index: 1;
        text-align: left;
    }

    .table-fixed tbody td {
        text-align: left;
    }

    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
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

.btn-lightgreen:hover {
    background-color: #c3e6cb; /* Slightly darker green on hover */
}

.btn-lightred:hover {
    background-color: #f5c6cb; /* Slightly darker red on hover */
}
/* Adjust the positioning and padding of the search icon */
.position-relative {
    position: relative;
}

.input-group-text {
    position: absolute;
    right: 10px; /* Adjust this value as needed */
    top: 50%;
    transform: translateY(-50%);
    background: transparent; /* Optional: to make the background of the icon transparent */
    border: none; /* Optional: remove border if needed */
    padding: 0;
}

.form-control.pl-5 {
    padding-right: 2.5rem; /* Adjust this value to fit the icon */
}

.gd-search {
    font-size: 1rem; /* Adjust font size as needed */
    color: #6c757d; /* Adjust color as needed */
}

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


</style>

<div class="content">
    <div class="py-4 px-3 px-md-4">
        <div class="mb-3 mb-md-4 d-flex justify-content-between">
            <div class="h3 mb-0">Application</div>
            @if(collect($blocks)->pluck('users')->flatten()->count() > 0)
         <div class="custom-control custom-switch">
    <input type="checkbox" class="custom-control-input" id="publishSwitch"
           {{ $publishStatus ? 'checked' : '' }}>
    <label class="custom-control-label" for="publishSwitch">Publish</label>
</div>

            @endif
        </div>



        @if(collect($blocks)->pluck('users')->flatten()->count() > 0)
<script>
    // Function to show toast notifications
    function showToast(toastId, message) {
        var toastElement = $('#' + toastId);
        toastElement.find('.toast-body').text(message);
        toastElement.toast('show');
    }

    // AJAX request to toggle publish status
    $('#publishSwitch').on('change', function() {
        var isChecked = $(this).is(':checked');
        var status = isChecked ? 1 : 0; // Convert boolean to integer (1 for true, 0 for false)

        $.ajax({
    url: '/update-publish-status',
    type: 'POST',
    data: {
        _token: '{{ csrf_token() }}',
        status: status
    },
    success: function(response) {
        if (response.success) {
            showToast('success-toast', 'Publish status updated successfully.');
        } else {
            showToast('error-toast', response.message || 'Failed to update publish status.');
        }
    },
    error: function() {
        showToast('error-toast', 'An error occurred. Please try again.');
    }
});

    });
</script>


<!-- Container for Search Input and Buttons -->
<div class="d-flex justify-content-between mb-3">

<!-- Search Input with Icon and Spinner -->

<div class="form-group position-relative">

    <div class="input-group">
        <input type="text" id="searchInput" class="form-control " placeholder="Search">
        <div class="input-group-append">
            <div id="spinner" class="spinner-border spinner-border-sm text-primary ms-2" role="status" style="display: none;">

            </div>
        </div>
    </div>


</div>

    <!-- Buttons and Switch on the Right -->
    <div class="d-flex align-items-center">
        <button id="apply-yes" class="btn btn-toggle btn-lightgreen ml-2">Yes</button>
        <button id="apply-no" class="btn btn-toggle btn-lightred ml-2">No</button>

    </div>
</div>

<div id="searchResults" class="mt-2 " >
    <!-- Results will be populated here -->
</div>



<ul class="nav nav-tabs d-flex justify-content-between" id="myTab" role="tablist">
    @foreach($blocks as $blockId => $block)
        <li class="nav-item flex-fill" role="presentation">
            <a class="nav-link text-dark {{ $loop->first ? 'active' : '' }}" id="tab-{{ $blockId }}-tab" data-toggle="tab" href="#tab-{{ $blockId }}" role="tab" aria-controls="tab-{{ $blockId }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                {{ $block['name'] }}<br> ({{ $block['user_count'] }})
            </a>
        </li>
    @endforeach
</ul>

<!-- Tab Content -->
<div class="tab-content" id="myTabContent">
    @foreach($blocks as $blockId => $block)
        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="tab-{{ $blockId }}" role="tabpanel" aria-labelledby="tab-{{ $blockId }}-tab">
            <div class="table-responsive">
                <table class="table table-striped table-fixed">
                    <thead>
                        <tr>
                            <th scope="col"><input type="checkbox" id="select-all-{{ $blockId }}" class="select-all"></th>
                            <th scope="col" style="cursor: pointer">#</th> <!-- Added Index Column -->
                            <th scope="col" style="cursor: pointer">Img</th>
                            <th scope="col" data-sort="name" style="cursor: pointer">Name</th>
                            <th scope="col" data-sort="number" style="cursor: pointer">Reg No</th>
                            <th scope="col" data-sort="course" style="cursor: pointer">course</th>
                            <th scope="col" data-sort="floor" style="cursor: pointer">Floor</th>
                            <th scope="col" data-sort="room" style="cursor: pointer">Room</th>
                            <th scope="col" data-sort="bed" style="cursor: pointer">Bed</th>
                            <th scope="col" data-sort="pay" style="cursor: pointer">Payment</th>
                            <th scope="col" style="cursor: pointer">View</th>
                            <th scope="col" style="cursor: pointer">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="user-table-body">
                        @foreach($block['users'] as $index => $user)
                            <tr class="user-row">
                                <td><input type="checkbox" class="user-checkbox" data-user-id="{{ $user->id }}"></td>
                                <td>{{ $loop->parent->index + $index + 1 }}</td> <!-- Display Index Number -->
                                <td><img class="avatar rounded-circle" src="{{ $user->profile_photo_path }}" alt="Image Description"></td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->registration_number }}</td>
                                <td>{{ $user->course}}</td>
                                <td>{{ optional($user->bed->floor)->floor_number ?? 'N/A' }}</td>
                                <td>{{ optional($user->bed->room)->room_number ?? 'N/A' }}</td>
                                <td>{{ $user->bed->bed_number ?? 'N/A' }}</td>
                                <td class="{{ $user->payment_status ? 'text-success' : 'text-danger' }}">
                                    {{ $user->payment_status ? 'Paid' : 'Not Paid' }}
                                </td>
                                <td>
                                    <button class="btn btn-sm shadow-sm" onclick="floorAction('bed', {{ $user->bed->id }})">
                                        <i class="gd-arrow-top-right"></i>
                                    </button>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-toggle {{ $user->status === 'approved' ? 'btn-lightgreen' : 'btn-lightred' }}" data-user-id="{{ $user->id }}" data-status="{{ $user->status }}" onclick="toggleStatus(this)">
                                        {{ $user->status === 'approved' ? 'Yes' : 'No' }}
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
</div>

<!-- Pagination Controls -->
<div class="d-flex justify-content-center mt-4">
    {{ $paginatedStudents->onEachSide(1)->links('pagination::bootstrap-4') }}
</div>




        <!-- No Results Found Message -->
        <div id="noResultsMessage" class="alert alert-danger" style="display: none;">
            No results found.
        </div>
    </div>

    @else
    <p class="text-center alert alert-danger">No application yet</p>
@endif

</div>
<script>
    $(document).ready(function() {
        let debounceTimer;

        // Event handler for search input
        $('#searchInput').on('input', function() {
            let query = $(this).val().trim();

            // Clear the previous debounce timer
            clearTimeout(debounceTimer);

            // Only make an AJAX request if the query is not empty and has at least 3 characters
            if (query.length >= 3) {
                // Show the spinner
                $('#spinner').show();

                debounceTimer = setTimeout(function() {
                    $.ajax({
                        url: "{{ route('admin.application.search') }}", // Your search route
                        method: 'GET',
                        data: {
                            query: query
                        },
                        success: function(response) {
                            $('#searchResults').html(response); // Update the results container
                        },
                        error: function(xhr) {
                            const msg = `Sorry, but there was an error: ${xhr.status} ${xhr.statusText}`;
                            $('#searchResults').html(`<p class="text-danger">${msg}</p>`);
                        },
                        complete: function() {
                            // Hide the spinner when the request is complete
                            $('#spinner').hide();
                        }
                    });
                }, 300); // Debounce time in milliseconds (adjust as needed)
            } else {
                $('#searchResults').empty(); // Clear results if the query is empty
                $('#spinner').hide(); // Hide the spinner if the query is empty
            }
        });
    });
    </script>


<script>
    // Your custom JavaScript code
    function toggleStatus(button) {
        const userId = button.getAttribute('data-user-id');
        const currentStatus = button.getAttribute('data-status');
        const newStatus = currentStatus === 'approved' ? 'disapproved' : 'approved';

        // Change button to show spinner
        button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
        button.disabled = true;
        button.classList.remove('btn-lightgreen', 'btn-lightred');
        button.classList.add('btn-light'); // Optional: Add a temporary class while loading

        // Send AJAX request to update status on the server
        fetch(`/update-status/${userId}`, {
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
                button.textContent = newStatus === 'approved' ? 'Yes' : 'No';
                button.setAttribute('data-status', newStatus);
                button.classList.add(newStatus === 'approved' ? 'btn-lightgreen' : 'btn-lightred');
                button.classList.remove('btn-light'); // Remove the temporary class
            } else {
                // Show error toast
                showToast('error-toast', data.message); // Show the server error message
                // Reset button text
                button.textContent = currentStatus === 'approved' ? 'Yes' : 'No';
            }
        })
        .catch(error => {
            // Show error toast
            showToast('error-toast', 'An error occurred. Please try again.');
            // Reset button text
            button.textContent = currentStatus === 'approved' ? 'Yes' : 'No';
        })
        .finally(() => {
            button.disabled = false; // Re-enable button
        });
    }

    function showToast(toastId, message) {
        var toastElement = $('#' + toastId);
        toastElement.find('.toast-body').text(message);
        toastElement.toast('show');
    }
</script>


<script>
    function floorAction(action, id) {
        const selectors = [
            "#nav_profile",
            "#nav_finish",
            "#nav_result",
            "#nav_aplication",
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



<script>
    $(document).ready(function() {
        // Get CSRF token from the meta tag
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Add CSRF token to AJAX request headers
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });

        // Handle select all checkbox
        $('.select-all').on('change', function() {
            var blockId = $(this).attr('id').split('-')[2]; // Extract block ID from the checkbox ID
            var isChecked = $(this).is(':checked');
            $('#tab-' + blockId).find('.user-checkbox').prop('checked', isChecked);
        });

        // Handle individual row checkbox change
        $(document).on('change', '.user-checkbox', function() {
            var allChecked = $('.user-checkbox').length === $('.user-checkbox:checked').length;
            $('.select-all').prop('checked', allChecked);
        });

        // Handle Apply Yes button click
        $('#apply-yes').on('click', function() {
            var selectedUsers = $('.user-checkbox:checked').map(function() {
                return $(this).data('user-id');
            }).get();

            // Perform the action, e.g., send an AJAX request
            if (selectedUsers.length > 0) {
                $.ajax({
                    url: '/apply-yes', // Update with your endpoint
                    method: 'POST',
                    data: { user_ids: selectedUsers },
                    success: function(response) {
                        aplication();


                        // Handle success response
                        showToast('success-toast', 'Applied Yes to selected users');
                    },
                    error: function(error) {
                        // Handle error response
                        showToast('error-toast', 'Error applying Yes: ' + error.responseText);
                    }
                });
            } else {
                showToast('error-toast', 'No users selected.');
            }
        });

        // Handle Apply No button click
        $('#apply-no').on('click', function() {
            var selectedUsers = $('.user-checkbox:checked').map(function() {
                return $(this).data('user-id');
            }).get();

            // Perform the action, e.g., send an AJAX request
            if (selectedUsers.length > 0) {
                $.ajax({
                    url: '/apply-no', // Update with your endpoint
                    method: 'POST',
                    data: { user_ids: selectedUsers },
                    success: function(response) {
                        aplication();

                        // Handle success response
                        showToast('success-toast', 'Applied No to selected users');
                    },
                    error: function(error) {
                        // Handle error response
                        showToast('error-toast', 'Error applying No: ' + error.responseText);
                    }
                });
            } else {
                showToast('error-toast', 'No users selected.');
            }
        });
    });

    /// good code
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

            if ($.isNumeric(aValue) && $.isNumeric(bValue)) {
                return order === 'asc' ? aValue - bValue : bValue - aValue;
            }

            return order === 'asc' ? aValue.localeCompare(bValue) : bValue.localeCompare(aValue);
        });

        // Append sorted rows back to the tbody
        tbody.append(rows);
    });
});

</script>
