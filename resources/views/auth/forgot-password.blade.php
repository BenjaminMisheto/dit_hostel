@extends('layouts.guest')

@section('content')
<div class="content">
    <div class="container-fluid pb-5">
        <div class="row justify-content-md-center">
            <div class="card-wrapper col-12 col-md-4 mt-5">
                <div class="brand text-center mb-3">
                    <i class="gd-key" style="font-size: 60px;"></i>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Reset Password</h4>
                        <div class="mb-4 text-sm text-gray-600">
                            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                        </div>

                        <x-validation-errors class="mb-4" />

                        @if (session('status'))
                            <div class="mb-4 font-medium text-sm text-green-600">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}" id="resetForm">
                            @csrf

                            <div class="form-group">
                                <x-label for="email" value="{{ __('Email') }}" />
                                <x-input id="email" class="form-control" type="text" name="email"
                                    :value="old('email')" autofocus autocomplete="username" />
                            </div>

                            <div class="form-group no-margin">
                                <button id="reset_submit" type="submit" class="btn btn-primary btn-block">
                                    {{ __('Email Password Reset Link') }}
                                </button>
                            </div>

                            <div class="text-center mt-3 small">
                                <a href="{{ route('login') }}">Remember password? Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('resetForm');
        const submitButton = document.getElementById('reset_submit');

        form.addEventListener('submit', function(event) {
            // Optionally, prevent default form submission if needed
            // event.preventDefault();

            // Disable the submit button and show the overlay
            submitButton.disabled = true;
            document.getElementById('overlay').style.display = 'flex';
        });
    });
</script>

@endsection
