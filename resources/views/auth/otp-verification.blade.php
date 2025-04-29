@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Verify OTP</h2>

    <form action="{{ route('otp.verify.submit') }}" method="POST">
        @csrf
        <input type="hidden" name="registration_number" value="{{ $registration_number }}">

        <div class="form-group">
            <label for="otp">Enter OTP sent to your email:</label>
            <input type="text" id="otp" name="otp" class="form-control" required>
            @error('otp')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Verify OTP</button>
    </form>
</div>
@endsection
