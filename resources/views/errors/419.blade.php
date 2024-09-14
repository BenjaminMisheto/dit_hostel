@extends('layouts.guest')
@section('content')

<div class="container full-height d-flex align-items-center justify-content-center" style="height: 70vh;">
    <div class="" style="width: 18rem;">
        <div class="card-body text-center">
            <i class="gd-alert text-danger" style="font-size: 3rem;"></i><br>
            <small class="card-title">Page Expire</small><br>
            <a href="{{ route('expire') }}" class="btn btn-outline-primary mt-4">Go to Home</a>
        </div>
    </div>
</div>

@endsection
