@php
    use Carbon\Carbon;

    $startingIndex = ($users->currentPage() - 1) * $users->perPage();
@endphp

@foreach($users as $index => $user)
    <tr class="user-row">
        <td><input type="checkbox" class="user-checkbox" data-user-id="{{ $user->id }}"></td>
        <td>{{ $startingIndex + $index + 1 }}</td>  {{-- Adjusted numbering --}}
        <td><img class="avatar rounded-circle" src="{{ $user->profile_photo_path ?? asset('default-avatar.png') }}" alt="Image"></td>
        <td>{{ $user->name ?? 'N/A' }}</td>
        <td>{{ $user->registration_number ?? 'N/A' }}</td>
        <td>{{ $user->course ?? 'N/A' }}</td>
        <td>{{ optional($user->bed->floor)->floor_number ?? 'N/A' }}</td>
        <td>{{ optional($user->bed->room)->room_number ?? 'N/A' }}</td>
        <td>{{ optional($user->bed)->bed_number ?? 'N/A' }}</td>

        @php
            $now = Carbon::now();
            $expirationDate = $user->expiration_date ? Carbon::parse($user->expiration_date) : null;
            $isExpired = $expirationDate && $now->greaterThan($expirationDate);
            $remainingTime = (!$isExpired && $expirationDate) ? $now->diff($expirationDate) : null;
            $paymentClass = $user->payment_status ? 'text-success' : ($isExpired ? 'text-danger' : 'text-warning');

            // Constructing remaining time dynamically
            $timeParts = [];
            if ($remainingTime) {
                if ($remainingTime->d > 0) $timeParts[] = "{$remainingTime->d}d";
                if ($remainingTime->h > 0) $timeParts[] = "{$remainingTime->h}h";
                if ($remainingTime->i > 0) $timeParts[] = "{$remainingTime->i}m";
            }
            $formattedTime = implode(' ', $timeParts) ?: 'N/A';
        @endphp

        {{-- Payment Status Column --}}
        <td class="{{ $paymentClass }}">
            {{ $user->payment_status ? 'Paid' : 'Not Paid' }}
        </td>

        {{-- Remaining Time Column --}}
        <td>
            @if ($user->payment_status)
                -
            @elseif ($isExpired)
                Expired
            @else
                {{ $formattedTime }}
            @endif
        </td>

        <td>
            <button class="btn btn-sm shadow-sm"
                onclick="floorAction('bed', {{ optional($user->bed)->id ?? 'null' }})">
                <i class="gd-arrow-top-right"></i>
            </button>
        </td>

        <td>
            <button class="btn btn-sm btn-toggle {{ $user->status === 'approved' ? 'btn-lightgreen' : 'btn-lightred' }}"
                    data-user-id="{{ $user->id }}"
                    data-status="{{ $user->status ?? 'N/A' }}"
                    onclick="toggleStatus(this)">
                {{ $user->status === 'approved' ? 'Yes' : 'No' }}
            </button>
        </td>
    </tr>
@endforeach
