<style>
    /* Hide columns progressively based on screen size */
    @media (max-width: 1200px) {
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
    }
</style>

<div class="content">
    <div class="py-4 px-3 px-md-4">
        <div class="mb-3 mb-md-4 d-flex justify-content-between">
            <div class="h3 mb-0">Eligible Students</div>
        </div>

        @if($paginatedStudents->isEmpty())
            <p>No eligible students found.</p>
        @else
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Number</th>
                            {{-- <th class="col-payment-status">Payment</th> --}}
                            <th class="col-sponsor">Sponsorship</th>
                            <th class="col-phone">Phone</th>
                            <th class="col-gender">Gender</th>
                            <th class="col-course">Course</th>
                        </tr>
                    </thead>
                    <tbody>
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
                                {{-- <td class="col-payment-status">{{ ucfirst($student->payment_status) }}</td> --}}
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
