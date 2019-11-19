@extends('layouts.app')

@section('title', 'Verify Your Email Address')

@section('content')
@form(['method' => 'GET', 'route' => route('verification.resend')])
        @slot('header', 'Verify Your Email Address')

        @slot('body')
            @if (session('resent'))
                {{-- @alert(['type' => 'success']) --}}
                @alert(['type' => 'success', 'dismissable' => 'true'])
                A fresh verification link has been sent to your email address.
                @endalert
            @elseif(session('success'))
                @alert(['type' => 'success', 'dismissable' => 'true'])
                Your new email address is <br><strong>{{ Auth::user()->email }}</strong>
                @endalert
            @endif
            
            <p>
                {{ __('Before proceeding, please check your email for a verification link.') }}
                {{ __('If you did not receive the email') }}
            </p>

            <div class="submit-btn-area my-2">
                <button id="form_submit" type="submit">{{ __('click here to request another') }}</button>
            </div>
            <p>
                Or if you're using the wrong email when you sign up
            </p>
            <div class="submit-btn-area mt-2">
                <a href="{{ route('email.edit') }}"><button type="button">{{ __('click here to change your email') }}</button></a>
            </div>
        @endslot
    @endform
@endsection
{{-- <div class="card-area">
    <div class="col-md-4 offset-md-4 mt-5">
        <div class="card card-bordered">
            <div class="card-header text-center">
                <h4>Verify Your Email Address</h4>
            </div>

            <div class="card-body">
                @if (session('resent'))
                @alert(['type' => 'success'])
                A fresh verification link has been sent to your email address.
                @endalert
                @endif

                {{ __('Before proceeding, please check your email for a verification link.') }}
                {{ __('If you did not receive the email') }}, <a href="{{ route('verification.resend') }}">{{ __('click here to request another') }}</a>.                    
            </div>
        </div>
    </div>
</div> --}}

{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verify Your Email Address') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }}, <a href="{{ route('verification.resend') }}">{{ __('click here to request another') }}</a>.
                </div>
            </div>
        </div>
    </div>
</div> --}}
