
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

th[data-sort] {
    cursor: pointer;
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
<div class="d-flex justify-content-between align-items-center mb-3 container-fluid">

    <!-- Search Input with Icon and Spinner -->
    <div class="flex-grow-1 me-3">
        <div class="input-group">
            <input type="text" id="searchInput" class="form-control" placeholder="Search">
            <div class="input-group-append">
                <div id="spinner" class="spinner-border spinner-border-sm text-primary ms-2" role="status" style="display: none;"></div>
            </div>
        </div>
    </div>

    <!-- Buttons and Switch on the Right -->
    <div class="d-flex">
        <button id="apply-yes" class="btn btn-toggle btn-lightgreen me-2">Yes</button>
        <button id="apply-no" class="btn btn-toggle btn-lightred">No</button>
    </div>

</div>



<div id="searchResults" class="mt-2 " >
    <!-- Results will be populated here -->
</div>



<ul class="nav nav-tabs d-flex justify-content-between" id="myTab" role="tablist">
    @foreach($blocks as $blockId => $block)
        <li class="nav-item flex-fill" role="presentation">
            <a class="nav-link text-dark {{ $loop->first ? 'active' : '' }}" id="tab-{{ $blockId }}-tab"
               data-toggle="tab" href="#" data-block-id="{{ $blockId }}" role="tab">
                {{ $block['name'] }}<br> ({{ $block['user_count'] }})
            </a>
        </li>
    @endforeach
</ul>
<div class="table-responsive">
    <table class="table table-striped table-fixed">
        <thead>
            <tr>
                <th><input type="checkbox" id="select-all" class="select-all"></th>
                <th data-sort>#</th>
                <th>Img</th>
                <th data-sort>Name</th>
                <th data-sort>Reg No</th>
                <th data-sort>Course</th>
                <th data-sort>Floor</th>
                <th data-sort>Room</th>
                <th data-sort>Bed</th>
                <th data-sort>Payment</th>
                <th data-sort>Time left</th>
                <th>View</th>
                <th data-sort>Actions</th>
            </tr>
        </thead>


        <tbody id="user-table-body">

            @include('admin.application_ajax', ['users' => $paginatedStudents])
        </tbody>
    </table>
</div>


<!-- Pagination Controls -->
<div class="d-flex justify-content-center mt-4" id="pagination-links">
    {{ $paginatedStudents->onEachSide(1)->links('pagination::bootstrap-4') }}
</div>


<script>$(document).ready(function () {
    // Select/Deselect all checkboxes on the current page
    $('#select-all').on('change', function () {
        let isChecked = $(this).prop('checked');
        // Select all checkboxes for the current page
        $('.user-checkbox').prop('checked', isChecked);
    });

    // Handle the tab click and load users for the clicked block
    $('.nav-link').on('click', function (e) {
        e.preventDefault();
        let blockId = $(this).data('block-id');
        if (!blockId) return;
        loadUsers(blockId, 1);
    });

    // Load users based on the selected block and page
    function loadUsers(blockId, page = 1) {
        $.ajax({
            url: "{{ route('admin.getUsersByBlock') }}",
            method: "GET",
            data: { block_id: blockId, per_page: 10, page: page },
            beforeSend: function () {
                $('#user-table-body').html('<tr><td colspan="12" class="text-center">Loading...</td></tr>');
            },
            success: function (response) {
                // Update table body and pagination controls
                $('#user-table-body').html(response.html);
                $('#pagination-links').html(response.pagination);

                // Attach event listener to pagination links
                $('#pagination-links a').on('click', function (e) {
                    e.preventDefault();
                    let url = new URL($(this).attr('href'));
                    let page = url.searchParams.get("page");
                    loadUsers(blockId, page);
                });

                // Reapply the sort functionality after the table is updated
                applySorting();

                // Reinitialize "Select All" checkbox for the current page
                $('#select-all').prop('checked', false); // Uncheck Select All when new page is loaded
            },
            error: function () {
                alert('Error fetching data.');
            }
        });
    }

    // Apply sorting to table columns
    function applySorting() {
        $('th[data-sort]').on('click', function() {
            var table = $(this).closest('table');
            var tbody = table.find('tbody');
            var rows = tbody.find('tr').toArray();
            var index = $(this).index();  // Index of clicked header
            var order = $(this).hasClass('asc') ? 'desc' : 'asc'; // Toggle order

            // Remove 'asc', 'desc', and 'sorted' classes from all headers
            table.find('th').removeClass('asc desc sorted');

            // Add the current order class and the 'sorted' class to the clicked header
            $(this).addClass(order + ' sorted');

            // Sort rows based on the column index
            rows.sort(function(a, b) {
                var aValue = $(a).find('td').eq(index).text().toLowerCase();
                var bValue = $(b).find('td').eq(index).text().toLowerCase();

                // Handle sorting for numeric and string values
                if ($.isNumeric(aValue) && $.isNumeric(bValue)) {
                    return order === 'asc' ? aValue - bValue : bValue - aValue;
                }

                // For string comparison
                return order === 'asc' ? aValue.localeCompare(bValue) : bValue.localeCompare(aValue);
            });

            // Append sorted rows back to the tbody
            tbody.append(rows);
        });
    }

    // Load initial data for the first block only if the page has elements
    let firstBlockId = $('.nav-link.active').data('block-id');
    if (firstBlockId) {
        loadUsers(firstBlockId, 1);
    }
});

</script>


        <!-- No Results Found Message -->
        <div id="noResultsMessage" class="alert alert-danger" style="display: none;">
            No results found.
        </div>
    </div>

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
    function floorAction(action, id, status) {
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
                url = `{{ url('floor/add') }}/${id}?status=${status}`;
                break;
            case 'update':
                url = `{{ url('floor/update') }}/${id}?status=${status}`;
                break;
            case 'delete':
                url = `{{ url('floor/delete') }}/${id}?status=${status}`;
                break;
            case 'bed':
                url = `{{ route('room.bed', '') }}/${id}?status=${status}`;
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
