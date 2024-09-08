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
.table td, .table th {
        white-space: nowrap; /* Prevent text from wrapping */
        overflow: hidden; /* Hide overflow */
        text-overflow: ellipsis; /* Add ellipsis for overflowed text */
    }

</style>
<div class="content">
    <div class="py-4 px-3 px-md-4">
        <div class="mb-3 mb-md-4 d-flex justify-content-between align-items-center">
            <div class="h3 mb-0">Eligible Students</div>

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



        @if($paginatedStudents->isEmpty())
            <p>No eligible students found.</p>
        @else
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th >Image</th>
                        <th data-sort="name" style="cursor: pointer">Name</th>
                        <th data-sort="number" style="cursor: pointer">Number</th>
                        <th  data-sort="sponsorship" style="cursor: pointer">Sponsorship</th>
                        <th data-sort="phone" style="cursor: pointer">Phone</th>
                        <th data-sort="gender" style="cursor: pointer">Gender</th>
                        <th  data-sort="course" style="cursor: pointer">Course</th>
                    </tr>
                </thead>
                <tbody id="studentTableBody">
                    @foreach($paginatedStudents as $index => $student)
                        <tr>
                            <td>{{ $paginatedStudents->firstItem() + $index }}</td>
                            <td>
                                @if($student->image)
                                    <img src="{{ $student->image }}" alt="Student Image" style="width: 40px; height: auto;" class="rounded rounded-circle">
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $student->student_name }}</td>
                            <td>{{ $student->registration_number }}</td>
                            <td class="col-sponsor">{{ $student->sponsorship ?? 'N/A' }}</td>
                            <td class="col-phone">{{ $student->phone ?? 'N/A' }}</td>
                            <td class="col-gender">{{ $student->gender ?? 'N/A' }}</td>
                            <td class="col-course">{{ $student->course ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

            <!-- Pagination Controls -->
            <div class="d-flex justify-content-center mt-4">
                {{ $paginatedStudents->onEachSide(1)->links('pagination::bootstrap-4') }}
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
                url: "{{ route('search.students.elligable') }}", // Update with your route
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
