@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
    @form(['method' => 'POST', 'route' =>route('register')])
        @slot('header', 'Daftar')

        @slot('body')
            @if(!$errors->isEmpty())
                {{-- @alert(['type' => 'danger']) --}}
                @alert(['type' => 'danger', 'dismissable' => 'true'])
                <ul>
                    @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                    @endforeach
                </ul>
                @endalert
            @endif
            <input type="hidden" name="tz" id="tz">
            <div class="form-gp @if(old('name')) focused @endif">
                <label for="name">Full Name*</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus>
                <i class="ti-user"></i>
            </div>
            <div class="form-gp @if(old('email')) focused @endif">
                <label for="email">Email address*</label>
                <input type="email" name="email" value="{{ old('email') }}" required>
                <i class="ti-email"></i>
            </div>
            <div class="form-gp">
                <label for="password">Password*</label>
                <input type="password" name="password" required>
                <i class="ti-lock"></i>
            </div>
            <div class="form-gp">
                <label for="password_confirmation">Confirm Password*</label>
                <input type="password" name="password_confirmation" required>
                <i class="ti-lock"></i>
            </div>
            <div class="mb-25">
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" checked id="PO" name="user_type" value="1" class="custom-control-input" @if(old('user_type') == 1) checked @endif>
                    <label class="custom-control-label" for="PO">Penggemar Olahraga</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="PL" name="user_type" value="2" class="custom-control-input" @if(old('user_type') == 2) checked @endif>
                    <label class="custom-control-label" for="PL">Pemilik Lapangan</label>
                </div>
            </div>
            <div class="submit-btn-area">
                <button id="form_submit" type="submit">Daftar <i class="ti-arrow-right"></i></button>
            </div>
            <div class="form-footer text-center mt-5">
                <p class="text-muted">Sudah memiliki akun? <a href="{{ route('login') }}">Masuk</a></p>
            </div>
        @endslot
    @endform
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.14/moment-timezone-with-data-2012-2022.min.js"></script>
    <script>
        $(function () {
            $('#tz').val(moment.tz.guess())
        })
    </script>
@endsection

{{-- <div class="login-area">
    <div class="login-box ptb--50">
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="login-form-head">
                <h4>Sign up</h4>
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
                <div class="form-gp @if(old('name')) focused @endif">
                    <label for="name">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus>
                    <i class="ti-user"></i>
                </div>
                <div class="form-gp @if(old('email')) focused @endif">
                    <label for="email">Email address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required>
                    <i class="ti-email"></i>
                </div>
                <div class="form-gp">
                    <label for="password">Password</label>
                    <input type="password" name="password" required>
                    <i class="ti-lock"></i>
                </div>
                <div class="form-gp">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" name="password_confirmation" required>
                    <i class="ti-lock"></i>
                </div>
                <div class="mb-25">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" checked id="PO" name="user_type" value="PO" class="custom-control-input" @if(old('user_type') == 'PO') checked @endif>
                        <label class="custom-control-label" for="PO">Penggemar Olahraga</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="PL" name="user_type" value="PL" class="custom-control-input" @if(old('user_type') == 'PL') checked @endif>
                        <label class="custom-control-label" for="PL">Pemilik Lapangan</label>
                    </div>
                </div>
                <div class="submit-btn-area">
                    <button id="form_submit" type="submit">Submit <i class="ti-arrow-right"></i></button>
                </div>
                <div class="form-footer text-center mt-5">
                    <p class="text-muted">Don't have an account? <a href="{{ route('login') }}">Sign in</a></p>
                </div>
            </div>
        </form>
    </div>
</div> --}}



{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
 --}}