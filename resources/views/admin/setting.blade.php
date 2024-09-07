<div class="content">
    <div class="py-4 px-3 px-md-4">
        <div class="mb-3 mb-md-4 d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Settings</h3>
        </div>

        <!-- Settings Container -->
        <div class="bg-white p-4">
            <!-- Turn off algorithm -->
            <div class="form-group row align-items-center mb-3">
                <label class="col-sm-8 col-form-label">Turn On/Off Algorithm</label>
                <div class="col-sm-4 text-right">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="algorithmSwitch"
                            data-setting="algorithm" {{ $settings->algorithm ? 'checked' : '' }}>
                        <label class="custom-control-label" for="algorithmSwitch"></label>
                    </div>
                </div>
            </div>
            <hr>

            <!-- Show reserved rooms to students -->
            <div class="form-group row align-items-center mb-3">
                <label class="col-sm-8 col-form-label">Show Reserved Rooms to Students</label>
                <div class="col-sm-4 text-right">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="reservedRoomsSwitch"
                            data-setting="reserved_bed" {{ $settings->reserved_bed ? 'checked' : '' }}>
                        <label class="custom-control-label" for="reservedRoomsSwitch"></label>
                    </div>
                </div>
            </div>
            <hr>

            <!-- Show maintenance beds to students -->
            <div class="form-group row align-items-center mb-3">
                <label class="col-sm-8 col-form-label">Show Maintenance Beds to Students</label>
                <div class="col-sm-4 text-right">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="maintenanceBedsSwitch"
                            data-setting="maintenance_bed" {{ $settings->maintenance_bed ? 'checked' : '' }}>
                        <label class="custom-control-label" for="maintenanceBedsSwitch"></label>
                    </div>
                </div>
            </div>
            <hr>

            <!-- Number of Days Input -->
            <div class="form-group row align-items-center mb-3">
                <label class="col-sm-10 col-form-label">
                    Number of days students have to make a payment before their hostel application expires
                </label>

                <div class="col-sm-2 text-right">
                    <select id="daysSelect" class="form-control wide">
                        <option value="1" {{ $expirationDays == 1 ? 'selected' : '' }}>1 Day</option>
                        <option value="2" {{ $expirationDays == 2 ? 'selected' : '' }}>2 Days</option>
                        <option value="3" {{ $expirationDays == 3 ? 'selected' : '' }}>3 Days</option>
                        <option value="4" {{ $expirationDays == 4 ? 'selected' : '' }}>4 Days</option>
                        <option value="5" {{ $expirationDays == 5 ? 'selected' : '' }}>5 Days</option>
                        <option value="6" {{ $expirationDays == 6 ? 'selected' : '' }}>6 Day</option>
                        <option value="7" {{ $expirationDays == 7 ? 'selected' : '' }}>7 Days</option>
                        <option value="8" {{ $expirationDays == 8 ? 'selected' : '' }}>8 Days</option>
                        <option value="9" {{ $expirationDays == 9 ? 'selected' : '' }}>9 Days</option>
                        <option value="10" {{ $expirationDays == 10 ? 'selected' : '' }}>10 Days</option>
                    </select>
                </div>
            </div>
            <hr>

<!-- Open Date Input -->
<div class="form-group row align-items-center mb-3">
    <label for="openDate" class="col-sm-10 col-form-label">
        Open Date for Application
    </label>
    <div class="col-sm-2 text-right">
        <input type="date" id="openDate" class="form-control" value="{{ $openDate }}">
    </div>
</div>
<hr>
 <!-- Deadline Date Input -->
<div class="form-group row align-items-center mb-3">
    <label for="deadlineDate" class="col-sm-10 col-form-label">
        Deadline Date for Application Expiration
    </label>
    <div class="col-sm-2 text-right">
        <input type="date" id="deadlineDate" class="form-control" value="{{ $deadlineDate }}">
    </div>
</div>


        </div>
    </div>
</div>
<script>$(document).ready(function() {$('select').niceSelect();});</script>
<script>
    $(document).ready(function () {
        // Function to change button text to "Generating..."
        function setButtonText(buttonId, text) {
            $('#' + buttonId + ' span').text(text);
        }

        // Function to restore original button text
        function restoreButtonText(buttonId, originalText) {
            $('#' + buttonId + ' span').text(originalText);
        }

        // When a hostel is selected
        $('#hostelSelect').on('change', function () {
            var hostelId = $(this).val();
            console.log("Selected hostel ID: ", hostelId);

            // Clear and disable Floor and Room dropdowns initially
            $('#floorSelect').html('<option value="">Select a floor</option>').prop('disabled', true);
            $('#roomSelect').html('<option value="">Select a room</option>').prop('disabled', true);

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
                            $('#floorSelect').append('<option value="all">Select All Floors</option>'); // Add Select All Floors option
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
                            $('#roomSelect').append('<option value="all">Select All Rooms</option>'); // Add Select All Rooms option
                            $.each(data.rooms, function (key, room) {
                                $('#roomSelect').append('<option value="' + room.id + '">' + room.room_number + '</option>');
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
                            $('#roomSelect').append('<option value="all">Select All Rooms</option>'); // Add Select All Rooms option
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

        $('#printReport').on('click', function () {
            var hostelId = $('#hostelSelect').val();
            var floorId = $('#floorSelect').val();
            var roomId = $('#roomSelect').val();

            if (hostelId && floorId && roomId) {
                var url = '/generate-report?hostel_id=' + hostelId + '&floor_id=' + floorId + '&room_id=' + roomId;

                $('#overlay').css('display', 'flex');

                loadPDF(url).then(() => {
                    $('#overlay').fadeOut();
                }).catch((error) => {
                    console.error("Error loading PDF:", error);
                    alert('Error loading the PDF.');
                    $('#overlay').fadeOut();
                });
            } else {
                alert('Please select hostel, floor, and room before generating the report.');
            }
        });

        function loadPDF(url) {
            return new Promise((resolve, reject) => {
                var container = $('#pdfCanvasContainer');
                container.empty();

                var loadingTask = pdfjsLib.getDocument(url);
                loadingTask.promise.then(function (pdf) {
                    var totalPages = pdf.numPages;
                    var pagesPromises = [];

                    for (var pageNumber = 1; pageNumber <= totalPages; pageNumber++) {
                        pagesPromises.push(
                            pdf.getPage(pageNumber).then(function (page) {
                                var pageDiv = $('<div>').addClass('pdf-page');

                                var canvas = $('<canvas>').addClass('pdf-canvas')[0];
                                var context = canvas.getContext('2d');
                                var viewport = page.getViewport({ scale: 1.5 });

                                canvas.height = viewport.height;
                                canvas.width = viewport.width;
                                pageDiv.append(canvas);
                                container.append(pageDiv);

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

                        $('#exportExcel').show();
                        $('#downloadPDF').show();
                        $('#printPDF').show();

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

        $('#printPDF').on('click', function () {
            setButtonText('printPDF', 'Generating...');
            var container = $('#pdfCanvasContainer');
            var printWindow = window.open('', '_blank');
            printWindow.document.open();
            printWindow.document.write('<html><head><title>Print PDF</title></head><body>');
            printWindow.document.write(container.html());
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
            restoreButtonText('printPDF', 'Print PDF');
        });

        $('#downloadPDF').on('click', function () {
            setButtonText('downloadPDF', 'Generating...');
            var url = '/generate-report?hostel_id=' + $('#hostelSelect').val() + '&floor_id=' + $('#floorSelect').val() + '&room_id=' + $('#roomSelect').val();
            window.location.href = url;

            // Restore button text after a delay (since we can't directly detect file save)
            setTimeout(function () {
                restoreButtonText('downloadPDF', 'Download PDF');
            }, 5000); // Adjust the delay as needed
        });

        $('#exportExcel').on('click', function () {
            setButtonText('exportExcel', 'Generating...');
            var hostelId = $('#hostelSelect').val();
            var floorId = $('#floorSelect').val();
            var roomId = $('#roomSelect').val();
            var url = '/generate-excel-report?hostel_id=' + hostelId + '&floor_id=' + floorId + '&room_id=' + roomId;
            window.location.href = url;

            // Restore button text after a delay (since we can't directly detect file save)
            setTimeout(function () {
                restoreButtonText('exportExcel', 'Export as Excel');
            }, 5000); // Adjust the delay as needed
        });
    });
</script>
