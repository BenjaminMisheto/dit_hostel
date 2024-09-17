<div class="content">
    <div class="py-4 px-3 px-md-4">
        <div class="mb-3 mb-md-4 d-flex justify-content-between align-items-center">
            <h3 class="mb-0">History</h3>
        </div>

        @if ($adminCheckouts->isEmpty())
            <div class="container full-height d-flex align-items-center justify-content-center" style="height: 70vh;">
                <div style="width: 18rem;">
                    <div class="card-body text-center">
                        <i class="gd-alert text-danger" style="font-size: 3rem;"></i><br>
                        <small class="card-title">No history available for this student.</small>
                    </div>
                </div>
            </div>
        @else
            @foreach ($adminCheckouts as $semesterId => $checkouts)
                <p class="mt-4">{{ $checkouts->first()->semester->name }}</p>

                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Items</th>
                                <th scope="col">Condition</th>
                                <th scope="col">Payment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($checkouts as $checkout)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $checkout->name }}</td>
                                    <td class="
                                        @if($checkout->condition === 'None') text-warning
                                        @elseif($checkout->condition === 'Bad') text-danger
                                        @elseif($checkout->condition === 'Good') text-success
                                        @endif">
                                        {{ $checkout->condition }}
                                    </td>
                                    <td class="
                                        @if($checkout->condition === 'Bad' || $checkout->condition === 'None') text-danger
                                        @elseif($checkout->condition === 'Good') text-success
                                        @endif">
                                        @if($checkout->condition === 'Bad' || $checkout->condition === 'None')
                                            Payment Required
                                        @elseif($checkout->condition === 'Good')
                                            Good
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        @endif
    </div>
</div>
