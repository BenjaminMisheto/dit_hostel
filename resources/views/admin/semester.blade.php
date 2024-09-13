<div class="content">
    <div class="py-4 px-3 px-md-4">
        <div class="mb-3 mb-md-4 d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Semester Settings</h3>
        </div>

        <!-- Check if there are existing semesters -->
        @if($semesters->isNotEmpty())
            <!-- Display existing semesters as a list -->
            <div class="p-4 container">
                <h4>Existing Semesters</h4>
                <ul class="list-group">
                    @foreach($semesters as $semester)
                        <li class="list-group-item">{{ $semester->name }}</li>
                    @endforeach
                </ul>
            </div>
        @else
            <!-- Display Semester Format Selection Form -->
            <div class="p-4 container">
                <form id="semesterFormatForm" class="row g-3">
                    <div class="form-group col-md-12">
                        <label for="semesterFormat" class="form-label">Select Semester Format</label>
                        <select id="semesterFormat" class="form-select wide" name="format">
                            <option value="" disabled selected>Select a format</option>
                            <option value="academic_year_2023_2024" {{ $semesterFormat == 'academic_year_2023_2024' ? 'selected' : '' }}>
                                2023/2024
                            </option>
                            <option value="semester_1_2024_2025" {{ $semesterFormat == 'semester_1_2024_2025' ? 'selected' : '' }}>
                                Semester 1 2024/2025
                            </option>
                            <option value="academic_year_semester_1_2024" {{ $semesterFormat == 'academic_year_semester_1_2024' ? 'selected' : '' }}>
                                Academic Year 2024 - Semester 1
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
                        <button type="submit" class="btn btn-outline-primary">Start Semester</button>
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

        // Function to generate semester options based on selected format
        function generateSemesterOptions(format) {
            const currentYear = new Date().getFullYear();
            const nextYear = currentYear + 1;
            let options = '';

            if (format === 'academic_year_2023_2024') {
                options = `<option value="${currentYear}_${nextYear}">${currentYear}/${nextYear}</option>`;
            } else if (format === 'semester_1_2024_2025') {
                options = `<option value="${nextYear}_semester_1">Semester 1 ${nextYear}/${nextYear + 1}</option>`;
            } else if (format === 'academic_year_semester_1_2024') {
                options = `<option value="${currentYear}_semester_1">${currentYear} - Semester 1</option>`;
                options += `<option value="${nextYear}_semester_1">${nextYear} - Semester 1</option>`;
            }

            options += '<option value="manual">Enter Manually</option>'; // Add option for manual entry

            $('#semesterStart').html(options).niceSelect('update');
        }

        // Show semester start options and button based on format selection
        $('#semesterFormat').on('change', function() {
            const format = $(this).val();
            if (format) {
                $('#semesterStartGroup').slideDown(); // Slide down animation
                $('#submitButtonGroup').slideDown(); // Slide down animation
                generateSemesterOptions(format);
            } else {
                $('#semesterStartGroup').slideUp(); // Slide up animation
                $('#submitButtonGroup').slideUp(); // Slide up animation
                $('#semesterStart').html('').niceSelect('update');
                $('#manualEntryGroup').slideUp(); // Slide up animation for manual entry
            }
        });

        // Show manual entry option when "Enter Manually" is selected
        $('#semesterStart').on('change', function() {
            const selectedValue = $(this).val();
            if (selectedValue === 'manual') {
                $('#manualEntryGroup').slideDown(); // Slide down animation
            } else {
                $('#manualEntryGroup').slideUp(); // Slide up animation
                $('#manualEntry').val(''); // Clear manual entry
            }
        });

        // Form submission via AJAX
        $('#semesterFormatForm').on('submit', function(e) {
            e.preventDefault(); // Prevent the form from submitting normally

            var format = $('#semesterFormat').val(); // Get the selected format
            var start = $('#semesterStart').val(); // Get the selected start
            var manualEntry = $('#manualEntry').val(); // Get the manual entry if provided

            // Determine the name to send to the server
            var semesterName = manualEntry.trim() !== '' ? manualEntry : start;

            $.ajax({
                url: '{{ route("admin.updateSemesterFormat") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // Include CSRF token
                    name: semesterName // Send the formatted name
                },
                success: function(response) {
                    semester();
                    showToast('#success-toast', response.message);
                },
                error: function(xhr) {
                    var errorMessage = 'Failed to update format: ';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage += xhr.responseJSON.message;
                    } else {
                        errorMessage += xhr.responseText;
                    }
                    showToast('#error-toast', errorMessage);
                }
            });
        });

        // Function to show toast notifications
        function showToast(toastId, message) {
            var $toast = $(toastId);
            $toast.find('.toast-body').text(message);
            $toast.toast({
                delay: 3000
            }); // Set the delay for the toast to hide automatically
            $toast.toast('show');
        }
    });
</script>
