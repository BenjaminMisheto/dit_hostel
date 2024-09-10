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

<script>      $('select').niceSelect();</script>
<div class="content">
    <div class="py-4 px-3 px-md-4">
        <div class="mb-3 mb-md-4 d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Reports</h3>
        </div>


<ul class="nav nav-v2 nav-primary nav-justified d-block d-xl-flex container" role="tablist">
    <li class="nav-item">
        <a class="nav-link d-flex align-items-center py-2 px-3 p-xl-4 active" href="#tabs1-tab1" role="tab" aria-selected="true"
           data-toggle="tab">Block Reports
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link d-flex align-items-center py-2 px-3 p-xl-4" href="#tabs1-tab2" role="tab" aria-selected="false"
           data-toggle="tab">Iterms Reports
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link d-flex align-items-center py-2 px-3 p-xl-4" href="#tabs1-tab3" role="tab" aria-selected="false"
           data-toggle="tab">Statistics Reports
        </a>
    </li>
</ul>

<div id="tabsContent1" class="card-body tab-content p-0">
    <div class="tab-pane fade show active" id="tabs1-tab1" role="tabpanel">

        <div class="container-fluid mt-5">
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
    <div class="tab-pane fade" id="tabs1-tab2" role="tabpanel">




        <div class="container-fluid mt-5">
            <div class="row">
                <!-- Block Filter -->
                <div class="col-md-3 mb-3">
                    <div class="form-floating">
                        <label for="blockSelectNew">Block Filter</label>
                        <select id="blockSelectNew" class="form-select wide" aria-label="Select Block">
                            <option value="" selected>Select a block</option>
                            @foreach($blocks as $block)
                                <option value="{{ $block->id }}">{{ $block->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Check-in/Check-out Filter -->
                <div class="col-md-3 mb-3">
                    <div class="form-floating">
                        <label for="checkinCheckoutSelectNew">Check-in/Check-out Filter</label>
                        <select id="checkinCheckoutSelectNew" class="form-select wide" aria-label="Select Status" disabled>

                        </select>
                    </div>
                </div>

                <!-- Gender Filter -->
                <div class="col-md-3 mb-3">
                    <div class="form-floating">
                        <label for="genderSelectNew">Gender Filter</label>
                        <select id="genderSelectNew" class="form-select wide" aria-label="Select Gender" disabled>

                        </select>
                    </div>
                </div>

                <!-- Course Filter -->
                <div class="col-md-3 mb-3">
                    <div class="form-floating">
                        <label for="courseSelectNew">Course Filter</label>
                        <select id="courseSelectNew" class="form-select wide" aria-label="Select Course" disabled>

                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Placeholder for PDF viewer and report buttons -->
        <div class="text-center mt-4">
            <span class="btn shadow-sm border" id="printReportNew" style="cursor: pointer"><i class="gd-loop"></i></span>
        </div>

        <div class="d-flex justify-content-around mt-4" style="display: none;" id="pdfButtonsNew">
            <!-- Export as Excel button -->
            <button id="exportExcelNew" class="btn btn-outline-success shadow-sm" style="display: none;">
                <span>Export as Excel</span>
            </button>

            <!-- Download PDF button -->
            <button id="downloadPDFNew" class="btn btn-outline-warning shadow-sm" style="display: none;">
                <span>Download PDF</span>
            </button>

            <!-- Print PDF button -->
            <button id="printPDFNew" class="btn btn-outline-info shadow-sm" style="display: none;">
                <span>Print PDF</span>
            </button>
        </div>

        <div class="container mt-4">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div id="pdfCanvasContainerNew" class="d-flex flex-column justify-content-center align-items-center"></div>
                </div>
            </div>
        </div>








    </div>
    <div class="tab-pane fade" id="tabs1-tab3" role="tabpanel">
        <!-- Content for Statistics Reports -->
    </div>
</div>





    </div>
</div>



<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.15.349/pdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.15.349/pdf.worker.min.js"></script>




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

        // Toast notification function
        function showToast(message, isError = false) {
            var toast = isError ? '#error-toast' : '#success-toast';
            $(toast).find('.toast-body').text(message);
            $(toast).toast('show');
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
                            showToast("No floors found for this hostel.", true);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log("Error retrieving floors:", xhr, status, error);
                        showToast('Error retrieving floors.', true);
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
                            showToast("No rooms found for this block.", true);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log("Error retrieving rooms:", xhr, status, error);
                        showToast('Error retrieving rooms.', true);
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
                            showToast("No rooms found for this floor.", true);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log("Error retrieving rooms:", xhr, status, error);
                        showToast('Error retrieving rooms.', true);
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
                            showToast("No gender options found for this room.", true);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log("Error retrieving gender options:", xhr, status, error);
                        showToast('Error retrieving gender options.', true);
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
                    showToast('Error loading the PDF.', true);
                    $('#overlay').fadeOut();
                });
            } else {
                showToast('Please select all filters before generating the report.', true);
            }
        });

        // Function to load PDF
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
                                pageDiv.appendChild(canvas);

                                container.appendChild(pageDiv);

                                var viewport = page.getViewport({ scale: 1.5 });
                                canvas.height = viewport.height;
                                canvas.width = viewport.width;

                                var renderContext = {
                                    canvasContext: canvas.getContext('2d'),
                                    viewport: viewport
                                };

                                return page.render(renderContext).promise;
                            })
                        );
                    }

                    return Promise.all(pagesPromises);
                }).then(() => {
                    console.log("All pages rendered.");
                    resolve();
                }).catch((error) => {
                    console.error("Error rendering PDF:", error);
                    reject(error);
                });
            });
        }
    });
</script>













































































<script>$(document).ready(function () {
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

    function updateNiceSelect(selector) {
        if ($.fn.niceSelect) {
            $(selector).niceSelect('update');
        }
    }

    // Function to display toast messages
    function showToast(toastId, message) {
        var $toast = $(toastId);
        $toast.find('.toast-body').text(message);
        $toast.toast({
            delay: 3000
        }); // Set the delay for the toast to hide automatically
        $toast.toast('show');
    }

    // When a block is selected
    $('#blockSelectNew').on('change', function () {
        var hostelId = $(this).val();
        console.log("Selected hostel ID: ", hostelId);

        $('#genderSelectNew').html('<option value="">Select Gender</option>').prop('disabled', true);
        $('#courseSelectNew').html('<option value="">Select Course</option>').prop('disabled', true);
        $('#checkinCheckoutSelectNew').html('<option value="">Select status</option><option value="checkin">Check-in</option><option value="checkout">Check-out</option>').prop('disabled', true);

        if (hostelId) {
            $('#checkinCheckoutSelectNew').prop('disabled', false);
            updateNiceSelect('#checkinCheckoutSelectNew');
        }
    });

    // When Check-in/Check-out option is selected
    $('#checkinCheckoutSelectNew').on('change', function () {
        var checkinCheckout = $(this).val();
        console.log("Selected Check-in/Check-out status: ", checkinCheckout);

        $('#genderSelectNew').html('<option value="">Select Gender</option>').prop('disabled', true);
        $('#courseSelectNew').html('<option value="">Select Course</option>').prop('disabled', true);
        updateNiceSelect('#genderSelectNew');

        if (checkinCheckout) {
            console.log("Fetching gender options based on selected check-in/check-out status.");
            $.ajax({
                url: '/get-gender-options/' + checkinCheckout,
                type: 'GET',
                success: function (data) {
                    console.log("Gender options received: ", data);
                    if (data && data.genders && data.genders.length > 0) {
                        var genderOptions = '<option value="">Select Gender</option>' +
                                            '<option value="all">All Gender</option>';
                        $.each(data.genders, function (key, gender) {
                            genderOptions += '<option value="' + gender + '">' + gender + '</option>';
                        });
                        $('#genderSelectNew').prop('disabled', false).html(genderOptions);
                        updateNiceSelect('#genderSelectNew');
                    } else {
                        showToast('#error-toast', 'No gender options found.');
                    }
                },
                error: function (xhr, status, error) {
                    showToast('#error-toast', 'Error retrieving gender options.');
                    console.error("Error retrieving gender options:", xhr, status, error);
                }
            });
        }
    });

    // When Gender is selected
    $('#genderSelectNew').on('change', function () {
        var gender = $(this).val();
        console.log("Selected gender: ", gender);

        $('#courseSelectNew').html('<option value="">Select Course</option>').prop('disabled', true);
        updateNiceSelect('#courseSelectNew');

        if (gender) {
            console.log("Fetching course options based on gender: ", gender);
            $.ajax({
                url: '/get-course-options/' + gender,
                type: 'GET',
                success: function (data) {
                    console.log("Course options received: ", data);
                    if (data && data.courses && data.courses.length > 0) {
                        var courseOptions = '<option value="">Select Course</option>' +
                                            '<option value="all">All Courses</option>';
                        $.each(data.courses, function (key, course) {
                            courseOptions += '<option value="' + course + '">' + course + '</option>';
                        });
                        $('#courseSelectNew').prop('disabled', false).html(courseOptions);
                        updateNiceSelect('#courseSelectNew');
                    } else {
                        showToast('#error-toast', 'No course options found.');
                    }
                },
                error: function (xhr, status, error) {
                    showToast('#error-toast', 'Error retrieving course options.');
                    console.error("Error retrieving course options:", xhr, status, error);
                }
            });
        }
    });

    // Handle report generation based on all filters
    $('#printReportNew').on('click', function () {
        var hostelId = $('#blockSelectNew').val();
        var gender = $('#genderSelectNew').val();
        var course = $('#courseSelectNew').val();
        var checkinCheckout = $('#checkinCheckoutSelectNew').val();

        if (hostelId && gender && course && checkinCheckout) {
            var url = '/generate-report-print-new?hostel_id=' + hostelId + '&gender=' + gender + '&course=' + course + '&checkin_checkout=' + checkinCheckout;

            $('#overlay').css('display', 'flex');

            loadPDF(url).then(() => {
                $('#overlay').fadeOut();
            }).catch((error) => {
                showToast('#error-toast', 'Error loading the PDF.');
                console.error("Error loading PDF:", error);
                $('#overlay').fadeOut();
            });
        } else {
            showToast('#error-toast', 'Please select all filters before generating the report.');
        }
    });

    // Load PDF and handle overlay
    function loadPDF(url) {
        return new Promise((resolve, reject) => {
            var container = document.getElementById('pdfCanvasContainerNew');
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
                    $('#exportExcelNew').show();
                    $('#downloadPDFNew').show();
                    $('#printPDFNew').show();
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

    var printButton = document.getElementById('printPDFNew');
    var downloadButton = document.getElementById('downloadPDFNew');
    var exportExcelButton = document.getElementById('exportExcelNew');

    if (printButton) {
        printButton.addEventListener('click', function () {
            setButtonText('printPDFNew', 'Generating...');

            var hostelId = $('#blockSelectNew').val();
            var gender = $('#genderSelectNew').val();
            var course = $('#courseSelectNew').val();
            var checkinCheckout = $('#checkinCheckoutSelectNew').val();

            if (hostelId && gender && course && checkinCheckout) {
                var url = '/generate-report-print-check?hostel_id=' + hostelId + '&gender=' + gender + '&course=' + course + '&checkin_checkout=' + checkinCheckout;

                var printWindow = window.open(url, '_blank');
                printWindow.onload = function () {
                    printWindow.focus();
                    printWindow.print();
                };

                restoreButtonText('printPDFNew', 'Print PDF');
            } else {
                showToast('#error-toast', 'Please select all filters before generating the report.');
                restoreButtonText('printPDFNew', 'Print PDF');
            }
        });
    }

    if (downloadButton) {
        downloadButton.addEventListener('click', function () {
            setButtonText('downloadPDFNew', 'Generating...');
            var url = '/generate-report-print-new?hostel_id=' + $('#blockSelectNew').val() + '&gender=' + $('#genderSelectNew').val() + '&course=' + $('#courseSelectNew').val() + '&checkin_checkout=' + $('#checkinCheckoutSelectNew').val();
            window.location.href = url;
            restoreButtonText('downloadPDFNew', 'Download PDF');
        });
    }

    if (exportExcelButton) {
        exportExcelButton.addEventListener('click', function () {
            setButtonText('exportExcelNew', 'Exporting...');
            var url = '/generate-report-excel-new?hostel_id=' + $('#blockSelectNew').val() + '&gender=' + $('#genderSelectNew').val() + '&course=' + $('#courseSelectNew').val() + '&checkin_checkout=' + $('#checkinCheckoutSelectNew').val();
            window.location.href = url;
            restoreButtonText('exportExcelNew', 'Export Excel');
        });
    }
});

</script>


