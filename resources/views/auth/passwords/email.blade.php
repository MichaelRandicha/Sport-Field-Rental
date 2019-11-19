@extends('layouts.app')

@section('title', 'Atur Ulang Password')

@section('content')
    @form(['method' => 'POST', 'route' =>route('password.email')])
        @slot('header', 'Atur Ulang Password')

        @slot('body')
            @if (session('status'))
                @alert(['type' => 'success', 'dismissable' => 'true'])
                {{ session('status') }}
                @endalert
            @endif
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
                <label for="email">Email address</label>
                <input type="email" name="email" value="{{ old('email') }}" autofocus required>
                <i class="ti-email"></i>
            </div>
            <div class="submit-btn-area">
                <button id="form_submit" type="submit">{{ __('Kirim Link Atur Ulang Password') }}</button>
            </div>
        @endslot
    @endform
@endsection
{{-- <div class="login-area">
    <div class="login-box ptb--50">
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="login-form-head">
                <h4>Reset Password</h4>
            </div>

            <div class="login-form-body">
                @if (session('status'))
                @alert(['type' => 'success'])
                {{ session('status') }}
                @endalert
                @endif
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
                <div class="submit-btn-area">
                    <button id="form_submit" type="submit">{{ __('Send Password Reset Link') }}</button>
                </div>
            </div>
        </form>
    </div>
</div> --}}

{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

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

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}