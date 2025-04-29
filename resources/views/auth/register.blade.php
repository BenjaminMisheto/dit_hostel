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
                        <h4 class="card-title">Create new account</h4>
                        <x-validation-errors class="mb-4" />

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <!-- Registration Number Input -->
                            <div class="form-group">
                                <x-label for="registration_number" value="{{ __('Registration Number') }}" />
                                <x-input id="registration_number" class="form-control" type="text" name="registration_number" :value="old('registration_number')"  autocomplete="off" />
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <x-label for="password" value="{{ __('Password') }}" />
                                    <x-input id="password" class="form-control" type="password" name="password" autocomplete="new-password" />
                                </div>
                                <div class="form-group col-md-6">
                                    <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                                    <x-input id="password_confirmation" class="form-control" type="password" name="password_confirmation" autocomplete="new-password" />
                                </div>
                            </div>

                            <div class="form-group no-margin">
                                <button id="register_button" type="submit" class="btn btn-primary btn-block" onclick="signIn();">Sign Up</button>
                                <script>
                                    function signIn() {
                                        $('#overlay').css('display', 'flex');
                                    }
                                </script>
                            </div>
                            <div class="text-center mt-3 small">
                                Already have an account? <a href="{{route('login')}}">Sign In</a>
                            </div>
                            <div class="text-center mt-3 small">
                                <a href="{{ route('admin')}}">Admin</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
