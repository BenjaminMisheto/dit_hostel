<div class="content">
    <div class="py-4 px-3 px-md-4">
        <div class="mb-3 mb-md-4 d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Semester Settings</h3>
        </div>

        <!-- Check if there are existing semesters -->
        @if($semesters->isNotEmpty())
            <!-- Display existing semesters as a list -->
            <div class="p-4 container-fluid">
                <!-- If all semesters are closed, show "Create New Semester" button -->
                @if($allClosed)
                    <div class="mb-4">
                        <button type="button" class="btn btn-outline-success" id="create-semester-btn">
                            Create New Semester
                        </button>
                    </div>
                @endif

                <ul class="list-group">
                    @foreach($semesters as $semester)
                        <li class="list-group-item d-flex justify-content-between align-items-center mt-3">
                            {{ $semester->name }}
                            @if($semester->is_closed)
                                <button type="button" class="btn btn-outline-danger btn-sm" disabled>
                                    Closed
                                </button>
                            @else
                                <small>Current semester</small>
                                <button type="button" class="btn btn-outline-warning btn-sm"
                                        onclick="onCloseSemesterClick({{ $semester->id }})">
                                    Close Semester
                                </button>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <!-- Display Semester Format Selection Form -->
            <div class="p-4 container-fluid">
                <form id="semesterFormatForm" class="row g-3">
                    <div class="form-group col-md-12">
                        <label for="semesterFormat" class="form-label">Select Semester Format</label>
                        <select id="semesterFormat" class="form-select wide" name="format">
                            <option value="" disabled selected>Select a format</option>
                            <option value="semester_1_2024_2025" {{ $semesterFormat == 'semester_1_2024_2025' ? 'selected' : '' }}>
                                Semester 1 2024/2025
                            </option>
                            <!-- Add more formats as needed -->
                        </select>
                    </div>

                    <div class="form-group col-md-12" id="semesterStartGroup" style="display: none;">
                        <label for="semesterStart" class="form-label">Select Starting Semester</label>
                        <select id="semesterStart" class="form-select wide" name="start">
                            <!-- Options will be dynamically added here -->
                        </select>

                        <div class="mt-5" id="manualEntryGroup" style="display: none;">
                            <label for="manualEntry" class="form-label">Or Enter Manually</label>
                            <input type="text" id="manualEntry" class="form-control" name="manual_entry" placeholder="Enter starting semester">
                        </div>
                    </div>

                    <div class="col-md-12" id="submitButtonGroup" style="display: none;">
                        <button type="submit" class="btn btn-outline-primary" id="submit-btn">Start Semester</button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>

<script>
    $(document).ready(function() {
        // Initialize Nice Select
        $('select').niceSelect();

        let isRequestInProgress = false;

        // Function to create a new semester
        function onCreateSemesterClick() {
            if (confirm('Are you sure you want to create a new semester? This action will reset all student accounts to their default settings, allowing eligible students to begin the process of applying for hostel accommodation for the newly created semester. Please note that once created, the semester cannot be deleted.')) {
                if (isRequestInProgress) return; // Prevent multiple submissions

                isRequestInProgress = true;
                $('#overlay').css('display', 'flex');

                $.ajax({
                    url: '/create-new-semester',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        showToast('#success-toast', response.message); // Show server message
                        // Optionally, refresh the semester list
                        $('#overlay').fadeOut();
                        semester();
                    },
                    error: function(xhr) {
                        $('#overlay').fadeOut();
                        showToast('#error-toast', xhr.responseJSON?.message || 'Error creating new semester.'); // Show server message
                    },
                    complete: function() {
                        $('#overlay').fadeOut();
                        isRequestInProgress = false;
                    }
                });
            }
        }

        // Function to generate semester options based on the selected format
        function generateSemesterOptions(format) {
            const currentYear = new Date().getFullYear();
            const nextYear = currentYear + 1;
            let options = '';

            if (format === 'academic_year_2023_2024') {
                options = `<option value="${currentYear}_${nextYear}">${currentYear}/${nextYear}</option>`;
            } else if (format === 'semester_1_2024_2025') {
                options = `<option value="Semester 1 ${nextYear}/${nextYear + 1}">Semester 1 ${nextYear}/${nextYear + 1}</option>`;
            } else if (format === 'academic_year_semester_1_2024') {
                options = `<option value="${currentYear}_semester_1">${currentYear} - Semester 1</option>`;
                options += `<option value="${nextYear}_semester_1">${nextYear} - Semester 1</option>`;
            }

            $('#semesterStart').html(options).niceSelect('update');
        }

        // Handle format change and show/hide the appropriate inputs
        function onFormatChange() {
            const format = $('#semesterFormat').val();
            if (format) {
                $('#semesterStartGroup').slideDown();
                $('#submitButtonGroup').slideDown();
                generateSemesterOptions(format);
            } else {
                $('#semesterStartGroup').slideUp();
                $('#submitButtonGroup').slideUp();
                $('#semesterStart').html('').niceSelect('update');
            }
        }

        // Handle form submission to update semester format
        function onSubmitSemesterFormat(e) {
            e.preventDefault();

            if (isRequestInProgress) return; // Prevent multiple submissions

            isRequestInProgress = true;
            $('#submit-btn').prop('disabled', true); // Disable the submit button

            var format = $('#semesterFormat').val();
            var start = $('#semesterStart').val();
            $('#overlay').css('display', 'flex');

            $.ajax({
                url: '{{ route("admin.updateSemesterFormat") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    name: start
                },
                success: function(response) {
                    showToast('#success-toast', response.message); // Show server message
                    // Optionally, reload or update the semester list
                    $('#overlay').fadeOut();
                    semester();
                },
                error: function(xhr) {
                    $('#overlay').fadeOut();
                    var errorMessage = 'Failed to update format: ';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage += xhr.responseJSON.message;
                    } else {
                        errorMessage += xhr.responseText;
                    }
                    showToast('#error-toast', errorMessage); // Show server message
                },
                complete: function() {
                    $('#overlay').fadeOut();
                    isRequestInProgress = false; // Re-enable the submit button
                    $('#submit-btn').prop('disabled', false);
                }
            });
        }

        // Attach event handlers for form and buttons
        $('#semesterFormat').on('change', onFormatChange);
        $('#semesterFormatForm').on('submit', onSubmitSemesterFormat);

        // Attach event handler for "Create New Semester" button
        $('#create-semester-btn').on('click', onCreateSemesterClick);
    });

    // Function to close a specific semester
    function onCloseSemesterClick(semesterId) {
        // Show confirmation dialog
        if (confirm('Are you sure you want to close this semester? This action is irreversible and the semester cannot be reopened.')) {
            $('#overlay').css('display', 'flex');

            $.ajax({
                url: '/semesters/' + semesterId + '/close',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}' // Include CSRF token
                },
                success: function(response) {
                    showToast('#success-toast', response.message); // Show server message
                    // Optionally, reload or update the semester list
                    semester();
                },
                error: function(xhr) {
                    showToast('#error-toast', xhr.responseJSON?.message || 'Error closing semester.'); // Show server message
                },
                complete: function() {
                    $('#overlay').fadeOut();
                }
            });
        }
    }

    // Show toast message
    function showToast(toastId, message) {
        var $toast = $(toastId);
        $toast.find('.toast-body').text(message);
        $toast.toast({
            delay: 3000
        });
        $toast.toast('show');
    }
</script>
