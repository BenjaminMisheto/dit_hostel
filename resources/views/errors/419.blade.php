@extends('layouts.guest')
@section('content')
<div class="content d-flex align-items-center justify-content-center vh-100">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 text-center">
                <div class="card p-5">
                    <div class="card-body">
                        <h6 class="display-4">The Page Has Expired</h6>
                        <p class="lead">Please refresh the page or try again later.</p>
                        <a href="{{ route('expire') }}" class="btn btn-primary mt-4">Go to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
