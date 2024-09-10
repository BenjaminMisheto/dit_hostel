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

        th, td {
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


<body><div class="container">
    @if ($checkinCheckout === 'checkin')
    <h1 class="mb-4">Check-In for {{ $block->name ?? 'Not Available' }}</h1>
    @else
    <h1 class="mb-4">Check-Out for {{ $block->name ?? 'Not Available' }}</h1>
    @endif


    <!-- Index Section -->
    <div class="index-section">
        <h2>Details</h2>
        <ul>
            <li><p><strong>Block Name:</strong> {{ $block->name ?? 'Not Available' }}</p></li>
            <li><p><strong>Block Manager:</strong> {{ $block->manager ?? 'Not Available' }}</p></li>
            <li><p><strong>Block Location:</strong> {{ $block->location ?? 'Not Available' }}</p></li>
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
                    <th>Course</th>
                    <th>Gender</th>
                    @if ($checkinCheckout === 'checkin')
                        <th>Given Items</th>
                    @else
                        <th>Returned Items</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @php $currentIndex = 1; @endphp
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $currentIndex++ }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->registration_number }}</td>
                        <td>{{ $user->course }}</td>
                        <td>{{ $user->gender }}</td>

                        <td>
                            @if ($checkinCheckout === 'checkin')
                                @if ($user->requirementItemConfirmation)
                                    @php
                                        $checkoutItems = json_decode($user->requirementItemConfirmation->checkout_items_names, true);
                                    @endphp
                                    @if ($checkoutItems)
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Item</th>
                                                    <th>Condition</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @foreach ($checkoutItems as $item)
                                                    <tr>
                                                        <td>{{ $item['name'] ?? 'Not Available' }}</td>
                                                        <td style="color:
                                                            @if($item['condition'] == 'Good')
                                                                green
                                                            @elseif($item['condition'] == 'Bad')
                                                                red
                                                            @else
                                                                red
                                                            @endif
                                                        ">
                                                            {{ $item['condition'] ?? 'Not Available' }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        Not Available
                                    @endif
                                @else
                                    Not Available
                                @endif
                            @else
                                @if ($user->adminCheckouts->isNotEmpty())
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Condition</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($user->adminCheckouts as $adminCheckout)
                                                <tr>
                                                    <td>{{ $adminCheckout->name }}</td>
                                                    <td style="color:
                                                        @if($adminCheckout->condition === 'Good')
                                                            green
                                                        @elseif($adminCheckout->condition === 'Bad')
                                                            red
                                                        @else
                                                            red
                                                        @endif
                                                    ">
                                                        {{ $adminCheckout->condition }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    Not Available
                                @endif
                            @endif
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
