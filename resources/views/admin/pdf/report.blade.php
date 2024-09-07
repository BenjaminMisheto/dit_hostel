<!DOCTYPE html>
<html>
<head>
    <title>Report</title>
    <style>
        /* Custom styles for PDF */
        body {
            padding: 20px;
            font-family: Arial, sans-serif;
            color: #333;
            font-size: 12px; /* Adjust base font size */
            margin: 0;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            padding-bottom: 50px; /* Ensure space for the footer */
        }

        h1 {
            text-align: center;
            margin-bottom: 20px; /* Adjust margin */
            font-size: 22px; /* Reduce font size */
        }

        .index-section {
            margin-bottom: 20px; /* Adjust margin */
        }

        .index-section h2 {
            font-size: 16px; /* Reduce font size */
        }

        .index-section ul {
            list-style: none;
            padding: 0;
        }

        .index-section ul li {
            margin-bottom: 8px; /* Adjust margin */
        }

        .index-section ul li p {
            margin: 0;
            font-size: 15px; /* Adjust font size */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px; /* Adjust font size */
        }

        th, td {
            text-align: left;
            padding: 8px; /* Adjust padding */
            border: 1px solid #ddd;
        }

        thead {
            background-color: #f8f9fa;
        }

        tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
        }

        tbody tr:hover {
            background-color: #e9ecef;
        }

        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        .table-container {
            margin: 20px 0;
        }

        .page-break {
            page-break-after: always;
        }

        @page {
            margin: 20px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            border-top: 1px solid #ddd;
            padding: 10px 0;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Report for {{ $block->name ?? 'Not Available' }}</h1>

        <!-- Index Section -->
        <div class="index-section">
            <h2>Details</h2>
            <ul>
                <li><p><strong>Block Name:</strong> {{ $block->name ?? 'Not Available' }}</p></li>
                <li><p><strong>Block Manager:</strong> {{ $block->manager ?? 'Not Available' }}</p></li>
                <li><p><strong>Block Location:</strong> {{ $block->location ?? 'Not Available' }}</p></li>
                <li><p><strong>Block Price:</strong> {{ number_format($block->price ?? 0, 0, '.', ',') }}</p></li>
                <li><p><strong>Date:</strong> {{ $date }}</p></li>
            </ul>
        </div>

        <!-- Report Section -->
        <div id="report" class="table-container">
            <h2>Application</h2>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Reg No</th>
                        {{-- <th>Block</th> --}}
                        <th>Floor</th>
                        <th>Room</th>
                        <th>Bed</th>
                        <th>Course</th>
                        <th>Gender</th>
                        <th>Payment</th>
                    </tr>
                </thead>
                <tbody>
                    @php $currentIndex = 1; @endphp
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $currentIndex++ }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->registration_number }}</td>
                            {{-- <td>{{ $user->block->name ?? 'Not Available' }}</td> --}}
                            <td>{{ $user->floor->floor_number ?? 'Not Available' }}</td>
                            <td>{{ $user->room->room_number ?? 'Not Available' }}</td>
                            <td>{{ $user->bed->bed_number ?? 'Not Available' }}</td>
                            <td>{{ $user->course }}</td>
                            <td>{{ $user->gender }}</td>
                            <td>
                                {{ !empty($user->payment_status) ? 'Paid' : 'Not Paid' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="footer">
        <p>Report generated on {{ now()->format('Y-m-d H:i:s') }}</p>
        <p>© {{ now()->format('Y') }} {{ $block->name ?? 'Not Available' }}</p>
    </div>
</body>
</html>
