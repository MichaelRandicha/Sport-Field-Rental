@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
    @form(['method' => 'POST', 'route' =>route('login')])
        @slot('header', 'Masuk')

        @slot('body')
            @if(!$errors->isEmpty())
                @alert(['type' => 'danger', 'dismissable' => 'true'])
                <ul>
                    @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                    @endforeach
                </ul>
                @endalert
            @endif

            <div class="form-gp @if(old('email')) focused @endif">
                <label for="email">Email address*</label>
                <input type="email" name="email" value="{{ old('email') }}" @if(old('email') == null) autofocus @endif required>
                <i class="ti-email"></i>
            </div>
            <div class="form-gp">
                <label for="password">Password*</label>
                <input type="password" name="password" @if(old('email')) autofocus @endif required>
                <i class="ti-lock"></i>
            </div>
            <div class="row mb-4 rmber-area">
                <div class="col-6">
                    <div class="custom-control custom-checkbox mr-sm-2">
                        <input type="checkbox" class="custom-control-input" id="rememberme" name="remember" @if(old('remember'))  checked @endif>
                        <label class="custom-control-label" for="rememberme">Ingat Saya</label>
                    </div>
                </div>
                <div class="col-6 text-right">
                    <a href="{{ route('password.request') }}">Lupa Password?</a>
                </div>
            </div>
            <div class="submit-btn-area">
                <button id="form_submit" type="submit">Masuk <i class="ti-arrow-right"></i></button>
            </div>
            <div class="form-footer text-center mt-5">
                <p class="text-muted">Tidak punya akun? <a href="{{ route('register') }}">Daftar</a></p>
            </div>
        @endslot
    @endform
@endsection
{{-- <div class="login-area">
    <div class="login-box ptb--50">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="login-form-head">
                <h4>Sign In</h4>
                <p>Hello there, Sign in and start managing your Admin Template</p>
            </div>

            <div class="login-form-body">
                @if(!$errors->isEmpty())
                @alert(['type' => 'danger'])
                <ul>
                    @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                    @endforeach
                </ul>
                @endalert
                @endif

                <div class="form-gp @if(old('email')) focused @endif">
                    <label for="email">Email address</label>
                    <input type="email" name="email" value="{{ old('email') }}" autofocus required>
                    <i class="ti-email"></i>
                </div>
                <div class="form-gp">
                    <label for="password">Password</label>
                    <input type="password" name="password" required>
                    <i class="ti-lock"></i>
                </div>
                <div class="row mb-4 rmber-area">
                    <div class="col-6">
                        <div class="custom-control custom-checkbox mr-sm-2">
                            <input type="checkbox" class="custom-control-input" id="rememberme">
                            <label class="custom-control-label" for="rememberme">Remember Me</label>
                        </div>
                    </div>
                    <div class="col-6 text-right">
                        <a href="{{ route('password.request') }}">Forgot Password?</a>
                    </div>
                </div>
                <div class="submit-btn-area">
                    <button id="form_submit" type="submit">Submit <i class="ti-arrow-right"></i></button>
                </div>
                <div class="form-footer text-center mt-5">
                    <p class="text-muted">Don't have an account? <a href="{{ route('register') }}">Sign up</a></p>
                </div>
            </div>
        </form>
    </div>
</div> --}}