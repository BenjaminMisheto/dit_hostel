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
            font-size: 12px;
            margin: 0;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            padding-bottom: 50px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 22px;
        }

        .index-section {
            margin-bottom: 20px;
        }

        .index-section h2 {
            font-size: 16px;
        }

        .index-section ul {
            list-style: none;
            padding: 0;
        }

        .index-section ul li {
            margin-bottom: 8px;
        }

        .index-section ul li p {
            margin: 0;
            font-size: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
        }

        th,
        td {
            text-align: left;
            padding: 8px;
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

        .no-data {
            text-align: center;
            font-size: 16px;
            color: #ff0000;
            margin-top: 20px;
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
        <h1 class="mb-4">
            {{ $checkinCheckout === 'checkin' ? 'Check-In' : 'Check-Out' }} for {{ $block ?? 'Not Available' }}
        </h1>

        <!-- Report Section -->
        <div id="report" class="table-container">
            <h2>{{ $semester->name ?? 'Not Available' }}</h2>

            @if ($users->isEmpty())
                <div class="no-data">
                    No Data Found
                </div>
            @else
                @if ($checkinCheckout === 'checkin')

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Reg No</th>
                                <th>Course</th>
                                <th>Gender</th>
                                <th>{{ $checkinCheckout === 'checkin' ? 'Given Items' : 'Returned Items' }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $currentIndex = 1; @endphp

                            @foreach ($users as $user)
                                @php
                                    $checkoutItems = json_decode($user->checkout_items_names, true);
                                @endphp
                                <tr>
                                    <td>{{ $currentIndex++ }}</td>
                                    <td>{{ $user->user->name }}</td>
                                    <td>{{ $user->user->registration_number }}</td>
                                    <td>{{ $user->course_name }}</td>
                                    <td>{{ $user->user->gender }}</td>
                                    <td>
                                        @if ($checkoutItems)
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Items</th>
                                                        <th>Condition</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($checkoutItems as $item)
                                                        <tr>
                                                            <td>{{ $item['name'] ?? 'Not Available' }}</td>
                                                            <td
                                                                style="color:
                                                    @if ($item['condition'] === 'Good') green
                                                    @elseif($item['condition'] === 'Bad')
                                                        red
                                                    @else
                                                        red @endif
                                                ">
                                                                {{ $item['condition'] ?? 'Not Available' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            Not Available
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                @else
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Reg No</th>
                                <th>Course</th>
                                <th>Gender</th>
                                <th>{{ $checkinCheckout === 'checkin' ? 'Given Items' : 'Returned Items' }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $currentIndex = 1; @endphp

                            @foreach ($users as $userId => $checkouts)
                                @php
                                    $user = $checkouts->first()->user; // Get the user object
                                    $groupedCheckouts = $checkouts->groupBy('name');
                                @endphp

                                <tr>
                                    <td>{{ $currentIndex++ }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->registration_number }}</td>
                                    <td>{{ $checkouts->first()->course_name }}</td>
                                    <td>{{ $user->gender }}</td>
                                    <td>
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Items</th>
                                                    <th>Condition</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($groupedCheckouts as $itemName => $checkoutsGroup)
                                                    @foreach ($checkoutsGroup as $adminCheckout)
                                                        <tr>
                                                            <td>{{ $itemName }}</td>
                                                            <td
                                                                style="color:
                                                    @if ($adminCheckout->condition === 'Good') green
                                                    @elseif($adminCheckout->condition === 'Bad')
                                                        red
                                                    @else
                                                        red @endif
                                                ">
                                                                {{ $adminCheckout->condition }}</td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>

                @endif

            @endif
        </div>
    </div>

    <div class="footer">
        <p>Report generated on {{ now()->format('Y-m-d H:i:s') }}</p>
        <p>© {{ now()->format('Y') }} {{ $block->name ?? 'Not Available' }}</p>
    </div>
</body>

</html>
