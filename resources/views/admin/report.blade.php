<style>
    .pdf-page {
    margin-bottom: 20px; /* Space between pages */
    border: 1px solid #ccc; /* Optional: Border to make pages more distinct */
    padding: 10px; /* Optional: Padding to create a paper-like effect */
    background-color: #fff; /* Optional: White background for pages */
    width: 100%; /* Make the canvas take the full width of its container */
    overflow: hidden; /* Hide overflow to prevent scrollbars */
}

.pdf-canvas {
    width: 100% !important; /* Force the canvas to fit the width of its container */
}
</style>


<div class="content">
    <div class="py-4 px-3 px-md-4">
        <div class="mb-3 mb-md-4 d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Reports</h3>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2 mb-3">
                    <div class="form-floating">
                        <label for="hostelSelect">Block Filter</label>
                        <select id="hostelSelect" class="form-select wide" aria-label="Select Hostel">
                            <option value="" selected>Select a hostel</option>
                            @foreach($blocks as $block)
                                <option value="{{ $block->id }}">{{ $block->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-2 mb-3">
                    <div class="form-floating">
                        <label for="floorSelect">Floor Filter</label>
                        <select id="floorSelect" class="form-select wide" aria-label="Select Floor" disabled>
                            <!-- Options will be populated dynamically -->
                        </select>
                    </div>
                </div>

                <div class="col-md-2 mb-3">
                    <div class="form-floating">
                        <label for="roomSelect">Room Filter</label>
                        <select id="roomSelect" class="form-select wide" aria-label="Select Room" disabled>
                            <!-- Options will be populated dynamically -->
                        </select>
                    </div>
                </div>

                <div class="col-md-2 mb-3">
                    <div class="form-floating">
                        <label for="genderSelect">Gender Filter</label>
                        <select id="genderSelect" class="form-select wide" aria-label="Select Gender"disabled>

                        </select>
                    </div>
                </div>

                <div class="col-md-2 mb-3">
                    <div class="form-floating">
                        <label for="paymentSelect">Payment Filter</label>
                        <select id="paymentSelect" class="form-select wide" aria-label="Select Payment"disabled>

                        </select>
                    </div>
                </div>

                <div class="col-md-2 mb-3">
                    <div class="form-floating">
                        <label for="courseSelect">Course Filter</label>
                        <select id="courseSelect" class="form-select wide" aria-label="Select Course"disabled >

                        </select>
                    </div>
                </div>
            </div>
        </div>







<!-- Placeholder for PDF viewer style="display:none;" -->
<div class="text-center mt-4">
    <span class="btn shadow-sm border" id="printReport" style="cursor: pointer"><i class="gd-loop "></i></span>

</div>

<div class="d-flex justify-content-around mt-4" style="display: none;" id="pdfButtons">
    <!-- Export as Excel button -->
    <button id="exportExcel" class="btn btn-outline-success shadow-sm" style="display: none;">
        {{-- <i class="gd-file text-white"></i> --}}
        <span class="">Export as Excel</span>
    </button>

    <!-- Download PDF button -->
    <button id="downloadPDF" class="btn btn-outline-warning shadow-sm" style="display: none;">
        {{-- <i class="gd-download text-white"></i> --}}
        <span >Download PDF</span>
    </button>

    <!-- Print PDF button -->
    <button id="printPDF" class="btn btn-outline-info shadow-sm" style="display: none;">
        {{-- <i class="gd-printer text-white"></i> --}}
        <span class="">Print PDF</span>
    </button>
</div>












        <div class="container mt-4">
            <div class="row justify-content-center">

                <div class="col-md-8">
                    <div id="pdfCanvasContainer" class="d-flex flex-column justify-content-center align-items-center"></div>
                </div>

            </div>
        </div>


    </div>
</div>



<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.15.349/pdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.15.349/pdf.worker.min.js"></script>


<script>
    $(document).ready(function () {
        // Initialize niceSelect
        $('select').niceSelect();
    });
</script>


<script>
    $(document).ready(function () {
        // Function to change button text to "Generating..."
        function setButtonText(buttonId, text) {
            var button = document.getElementById(buttonId);
            if (button) {
                button.querySelector('span').textContent = text;
            }
        }

        // Function to restore original button text
        function restoreButtonText(buttonId, originalText) {
            var button = document.getElementById(buttonId);
            if (button) {
                button.querySelector('span').textContent = originalText;
            }
        }

        // When a hostel is selected
        $('#hostelSelect').on('change', function () {
            var hostelId = $(this).val();
            console.log("Selected hostel ID: ", hostelId);

            // Clear and disable Floor, Room, Gender, Payment, and Course dropdowns initially
            $('#floorSelect').html('<option value="">Select a floor</option>').prop('disabled', true);
            $('#roomSelect').html('<option value="">Select a room</option>').prop('disabled', true);
            $('#genderSelect').html('<option value="">Select Gender</option>').prop('disabled', true);
            $('#paymentSelect').html('<option value="">Select Payment</option>').prop('disabled', true);
            $('#courseSelect').html('<option value="">Select Course</option>').prop('disabled', true);

            if (hostelId) {
                console.log("Fetching floors for hostel ID: ", hostelId);

                // Fetch floors for the selected hostel via AJAX
                $.ajax({
                    url: '/get-floors/' + hostelId,
                    type: 'GET',
                    success: function (data) {
                        console.log("Floors received: ", data);

                        if (data && data.floors && data.floors.length > 0) {
                            $('#floorSelect').prop('disabled', false);
                            $('#floorSelect').append('<option value="all">All Floors</option>');
                            $.each(data.floors, function (key, floor) {
                                $('#floorSelect').append('<option value="' + floor.id + '">' + floor.floor_number + '</option>');
                            });

                            // Update niceSelect
                            $('#floorSelect').niceSelect('update');
                        } else {
                            console.log("No floors found for this hostel.");
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log("Error retrieving floors:", xhr, status, error);
                        alert('Error retrieving floors.');
                    }
                });
            }
        });

        // When a floor is selected
        $('#floorSelect').on('change', function () {
            var floorId = $(this).val();
            var hostelId = $('#hostelSelect').val();
            console.log("Selected floor ID: ", floorId);

            // Clear and disable the Room dropdown initially
            $('#roomSelect').html('<option value="">Select a room</option>').prop('disabled', true);

            if (floorId === 'all') {
                console.log("Fetching all rooms for hostel ID: ", hostelId);

                // Fetch all rooms for all floors in the selected hostel via AJAX
                $.ajax({
                    url: '/get-rooms-for-block/' + hostelId,
                    type: 'GET',
                    success: function (data) {
                        console.log("Rooms for all floors received: ", data);

                        if (data && data.rooms && data.rooms.length > 0) {
                            $('#roomSelect').prop('disabled', false);
                            $('#roomSelect').append('<option value="all">All Rooms</option>');
                            $.each(data.rooms, function (key, room) {
                                $('#roomSelect').append('<option value="' + room.id + '">' + room.room_number  + '</option>');
                            });

                            // Update niceSelect
                            $('#roomSelect').niceSelect('update');
                        } else {
                            console.log("No rooms found for this block.");
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log("Error retrieving rooms:", xhr, status, error);
                        alert('Error retrieving rooms.');
                    }
                });
            } else if (floorId) {
                console.log("Fetching rooms for floor ID: ", floorId);

                // Fetch rooms for the selected floor via AJAX
                $.ajax({
                    url: '/get-rooms/' + floorId,
                    type: 'GET',
                    success: function (data) {
                        console.log("Rooms received: ", data);

                        if (data && data.rooms && data.rooms.length > 0) {
                            $('#roomSelect').prop('disabled', false);
                            $('#roomSelect').append('<option value="all">All Rooms</option>');
                            $.each(data.rooms, function (key, room) {
                                $('#roomSelect').append('<option value="' + room.id + '">' + room.room_number + '</option>');
                            });

                            // Update niceSelect
                            $('#roomSelect').niceSelect('update');
                        } else {
                            console.log("No rooms found for this floor.");
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log("Error retrieving rooms:", xhr, status, error);
                        alert('Error retrieving rooms.');
                    }
                });
            }
        });

// When a room is selected
$('#roomSelect').on('change', function () {
    var roomId = $(this).val();
    console.log("Selected room ID: ", roomId);

    // Clear and disable Gender, Payment, and Course dropdowns initially
    $('#genderSelect').html('<option value="">Select Gender</option>').prop('disabled', true);
    $('#paymentSelect').html('<option value="">Select Payment</option>').prop('disabled', true);
    $('#courseSelect').html('<option value="">Select Course</option>').prop('disabled', true);

    if (roomId) {
        console.log("Fetching gender options for room ID: ", roomId);

        // Fetch gender options based on the selected room via AJAX
        $.ajax({
            url: '/get-gender-options/' + roomId,
            type: 'GET',
            success: function (data) {
                console.log("Gender options received: ", data);

                if (data && data.genders && data.genders.length > 0) {
                    var genderOptions = '<option value="">Select Gender</option>' +
                                        '<option value="all">All Gender</option>';

                    $.each(data.genders, function (key, gender) {
                        genderOptions += '<option value="' + gender + '">' + gender + '</option>';
                    });

                    $('#genderSelect').prop('disabled', false);
                    $('#genderSelect').html(genderOptions); // Set options with "All Gender" at the top

                    // Update niceSelect
                    $('#genderSelect').niceSelect('update');
                } else {
                    console.log("No gender options found for this room.");
                }
            },
            error: function (xhr, status, error) {
                console.log("Error retrieving gender options:", xhr, status, error);
                alert('Error retrieving gender options.');
            }
        });
    }
});



// When gender is selected
$('#genderSelect').on('change', function () {
    var gender = $(this).val();
    var roomId = $('#roomSelect').val();
    console.log("Selected gender: ", gender);

    // Clear and disable Payment and Course dropdowns initially
    $('#paymentSelect').html('<option value="">Select Payment</option>').prop('disabled', true);
    $('#courseSelect').html('<option value="">Select Course</option>').prop('disabled', true);

    if (gender) {
        console.log("Fetching payment options based on gender: ", gender);

        // Fetch payment options based on the selected gender via AJAX
        $.ajax({
            url: '/get-payment-options/' + gender,
            type: 'GET',
            success: function (data) {
                console.log("Payment options received: ", data);

                if (data && data.payments && data.payments.length > 0) {
                    var paymentOptions = '<option value="">Select Payment</option>' +
                                         '<option value="all">Both</option>';
                    $.each(data.payments, function (key, payment) {
                        paymentOptions += '<option value="' + payment + '">' + payment + '</option>';
                    });

                    $('#paymentSelect').prop('disabled', false);
                    $('#paymentSelect').html(paymentOptions); // Set options with "Both" at the top

                    // Update niceSelect
                    $('#paymentSelect').niceSelect('update');
                } else {
                    console.log("No payment options found for this gender.");
                }
            },
            error: function (xhr, status, error) {
                console.log("Error retrieving payment options:", xhr, status, error);
                alert('Error retrieving payment options.');
            }
        });
    }
});

// When payment is selected
$('#paymentSelect').on('change', function () {
    var payment = $(this).val();
    console.log("Selected payment: ", payment);

    // Clear and disable Course dropdown initially
    $('#courseSelect').html('<option value="">Select Course</option>').prop('disabled', true);

    if (payment) {
        console.log("Fetching course options based on payment: ", payment);

        // Fetch course options based on the selected payment via AJAX
        $.ajax({
            url: '/get-course-options/' + payment,
            type: 'GET',
            success: function (data) {
                console.log("Course options received: ", data);

                if (data && data.courses && data.courses.length > 0) {
                    var courseOptions = '<option value="">Select Course</option>' +
                                        '<option value="all">All Courses</option>';
                    $.each(data.courses, function (key, course) {
                        courseOptions += '<option value="' + course + '">' + course + '</option>';
                    });

                    $('#courseSelect').prop('disabled', false);
                    $('#courseSelect').html(courseOptions); // Set options with "All Courses" at the top

                    // Update niceSelect
                    $('#courseSelect').niceSelect('update');
                } else {
                    console.log("No course options found for this payment.");
                }
            },
            error: function (xhr, status, error) {
                console.log("Error retrieving course options:", xhr, status, error);
                alert('Error retrieving course options.');
            }
        });
    }
});


        // Handle report generation based on all filters
        $('#printReport').on('click', function () {
            var hostelId = $('#hostelSelect').val();
            var floorId = $('#floorSelect').val();
            var roomId = $('#roomSelect').val();
            var gender = $('#genderSelect').val();
            var payment = $('#paymentSelect').val();
            var course = $('#courseSelect').val();




            if (hostelId && floorId && roomId && gender && payment && course) {
                var url = '/generate-report?hostel_id=' + hostelId + '&floor_id=' + floorId + '&room_id=' + roomId + '&gender=' + gender + '&payment=' + payment + '&course=' + course;

                $('#overlay').css('display', 'flex');

                loadPDF(url).then(() => {
                    $('#overlay').fadeOut();
                }).catch((error) => {
                    console.error("Error loading PDF:", error);
                    alert('Error loading the PDF.');
                    $('#overlay').fadeOut();
                });
            } else {
                alert('Please select all filters before generating the report.');
            }
        });

        function loadPDF(url) {
            return new Promise((resolve, reject) => {
                var container = document.getElementById('pdfCanvasContainer');
                container.innerHTML = '';

                var loadingTask = pdfjsLib.getDocument(url);
                loadingTask.promise.then(function (pdf) {
                    var totalPages = pdf.numPages;
                    var pagesPromises = [];

                    for (var pageNumber = 1; pageNumber <= totalPages; pageNumber++) {
                        pagesPromises.push(
                            pdf.getPage(pageNumber).then(function (page) {
                                var pageDiv = document.createElement('div');
                                pageDiv.classList.add('pdf-page');

                                var canvas = document.createElement('canvas');
                                canvas.classList.add('pdf-canvas');
                                var context = canvas.getContext('2d');
                                var viewport = page.getViewport({ scale: 1.5 });

                                canvas.height = viewport.height;
                                canvas.width = viewport.width;
                                pageDiv.appendChild(canvas);
                                container.appendChild(pageDiv);

                                var renderContext = {
                                    canvasContext: context,
                                    viewport: viewport
                                };

                                return page.render(renderContext).promise;
                            })
                        );
                    }

                    Promise.all(pagesPromises).then(function () {
                        console.log('PDF rendered successfully.');

                        document.getElementById('exportExcel').style.display = 'inline-block';
                        document.getElementById('downloadPDF').style.display = 'inline-block';
                        document.getElementById('printPDF').style.display = 'inline-block';

                        resolve();
                    }).catch(function (error) {
                        console.error('Error rendering pages:', error);
                        reject(error);
                    });
                }).catch(function (error) {
                    console.error('Error loading PDF document:', error);
                    reject(error);
                });
            });
        }

        var printButton = document.getElementById('printPDF');
        var downloadButton = document.getElementById('downloadPDF');
        var exportExcelButton = document.getElementById('exportExcel');

        if (printButton) {
            printButton.addEventListener('click', function () {
                setButtonText('printPDF', 'Generating...');

                var hostelId = $('#hostelSelect').val();
                var floorId = $('#floorSelect').val();
                var roomId = $('#roomSelect').val();
                var gender = $('#genderSelect').val();
                var payment = $('#paymentSelect').val();
                var course = $('#courseSelect').val();

                if (hostelId && floorId && roomId && gender && payment && course) {
                    // URL to generate the PDF report
                    var url = '/generate-report-print?hostel_id=' + hostelId + '&floor_id=' + floorId + '&room_id=' + roomId + '&gender=' + gender + '&payment=' + payment + '&course=' + course;

                    // Open the PDF in a new tab
                    var printWindow = window.open(url, '_blank');

                    // Wait for the new tab to load the PDF before triggering print
                    printWindow.onload = function () {
                        printWindow.focus();  // Ensure the new tab is focused
                        printWindow.print();  // Trigger the print dialog
                    };

                    restoreButtonText('printPDF', 'Print PDF');
                } else {
                    alert('Please select all filters before generating the report.');
                    restoreButtonText('printPDF', 'Print PDF');
                }
            });
        }

        if (downloadButton) {
            downloadButton.addEventListener('click', function () {
                setButtonText('downloadPDF', 'Generating...');
                var url = '/generate-report?hostel_id=' + $('#hostelSelect').val() + '&floor_id=' + $('#floorSelect').val() + '&room_id=' + $('#roomSelect').val() + '&gender=' + $('#genderSelect').val() + '&payment=' + $('#paymentSelect').val() + '&course=' + $('#courseSelect').val();
                window.location.href = url;
                restoreButtonText('downloadPDF', 'Download PDF');
            });
        }

        if (exportExcelButton) {
            exportExcelButton.addEventListener('click', function () {
                setButtonText('exportExcel', 'Generating...');
                var url = '/generate-excel-report?hostel_id=' + $('#hostelSelect').val() + '&floor_id=' + $('#floorSelect').val() + '&room_id=' + $('#roomSelect').val() + '&gender=' + $('#genderSelect').val() + '&payment=' + $('#paymentSelect').val() + '&course=' + $('#courseSelect').val();
                window.location.href = url;
                restoreButtonText('exportExcel', 'Export as Excel');
            });
        }
    });
</script>
