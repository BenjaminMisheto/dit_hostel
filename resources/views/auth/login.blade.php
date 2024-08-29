@extends('layouts.guest') @section('content')
<div class="content">

    <div class="container-fluid pb-5">

        <div class="row justify-content-md-center">
            <div class="card-wrapper col-12 col-md-4 mt-5">
                <div class="brand text-center mb-3">
                    <i class="gd-key" style="font-size: 60px;"></i>
                </div>
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title">Login</h4>



                 <x-validation-errors class="mb-4" />

                          @if (session('status'))
                           <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('status') }}
                       </div>
                     @endif
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                            <div class="form-group">


                                <div>
                                    <x-label for="email" value="{{ __('Email') }}" />
                                    <x-input id="email" class="form-control" type="text" name="email" :value="old('email')"  autofocus autocomplete="username" />
                            </div>

                            <div class="form-group mt-3">

                                <x-label for="password" value="{{ __('Password') }}" />
                                <x-input id="password" class="form-control" type="password" name="password"  autocomplete="current-password" />



                                <div class="text-right">
                                    @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="small">
                                        {{ __('Forgot your password?') }}
                                    </a>
                                @endif


                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-check position-relative mb-2">
                                    <x-checkbox id="remember_me" name="remember" />
                                  <label class="checkbox checkbox-xxs form-check-label ml-1" for="remember_me"
                                         data-icon="&#xe936">Remember Me</label>
                                </div>
                            </div>


                            <div class="form-group no-margin">
                                <button id="login_submit" type="submit" class="btn btn-primary btn-block" onclick="signIn();">Sign In</button>
                                <script>
                                    function signIn() {

                                        $('#overlay').css('display', 'flex');
                                      $('#ogin_submit').prop('disabled', true);

                                    }
                                </script>

                            </div>
                            <div class="text-center mt-3 small">
                                Don't have an account? <a href="{{ route('register')}}">Sign Up</a>
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
