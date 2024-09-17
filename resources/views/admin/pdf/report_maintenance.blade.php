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

        .text-success {
            color: green;
        }

        .text-danger {
            color: red;
        }

        .summary-container {
            margin-top: 40px;
        }

        .summary-container h2 {
            font-size: 18px;
            margin-bottom: 15px;
        }

        .summary-container table {
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-top: 0;
        }

        .summary-container th {
            background-color: #007bff;
            color: white;
        }

        .summary-container .total {
            font-weight: bold;
        }

        .summary-container .percentage {
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Maintenance Report for {{ $block ?? 'Not Available' }}</h1>

        <!-- AdminCheckouts Section -->
        <div id="admin-checkouts" class="table-container">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item Name</th>
                        <th>Condition</th>
                        <th>Block</th>
                        <th>Floor</th>
                        <th>Room</th>
                    </tr>
                </thead>
                <tbody>
                    @php $currentIndex = 1; @endphp
                    @foreach ($users as $item)
                        <tr>
                            <td>{{ $currentIndex++ }}</td>
                            <td>{{ $item->name }}</td>
                            <td class="{{ $item->condition === 'Good' ? 'text-success' : 'text-danger' }}">
                                {{ $item->condition }}
                            </td>
                            <td>{{ $item->block_name ?? 'Not Available' }}</td>
                            <td>{{ $item->floor_name ?? 'Not Available' }}</td>
                            <td>{{ $item->bed_name ?? 'Not Available' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Page Break -->
        {{-- <div class="page-break"></div> --}}

        <!-- Summary Section -->
        <div class="summary-container">
            <h2>Summary</h2>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Condition</th>
                        <th>Total Items</th>
                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalItems = $users->count();
                        $goodItems = $users->where('condition', 'Good')->count();
                        $badItems = $users->where('condition', 'Bad')->count();
                        $noneItems = $users->where('condition', 'None')->count();
                        $goodPercentage = $totalItems > 0 ? ($goodItems / $totalItems) * 100 : 0;
                        $badPercentage = $totalItems > 0 ? ($badItems / $totalItems) * 100 : 0;
                        $nonePercentage = $totalItems > 0 ? ($noneItems / $totalItems) * 100 : 0;
                    @endphp
                    <tr>
                        <td>Good</td>
                        <td class="total">{{ $goodItems }}</td>
                        <td class="percentage">{{ number_format($goodPercentage, 2) }}%</td>
                    </tr>
                    <tr>
                        <td>Bad</td>
                        <td class="total">{{ $badItems }}</td>
                        <td class="percentage">{{ number_format($badPercentage, 2) }}%</td>
                    </tr>
                    <tr>
                        <td>None</td>
                        <td class="total">{{ $noneItems }}</td>
                        <td class="percentage">{{ number_format($nonePercentage, 2) }}%</td>
                    </tr>
                    <tr>
                        <td>Total Items</td>
                        <td class="total">{{ $totalItems }}</td>
                        <td class="percentage"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="footer">
        <p>Report generated on {{ now()->format('Y-m-d H:i:s') }}</p>
        <p>© {{ now()->format('Y') }} {{ $block ?? 'Not Available' }}</p>
    </div>
</body>
</html>
