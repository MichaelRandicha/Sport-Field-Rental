@extends('layouts.app')

@section('title', 'Ubah Email')

@section('content')
    @form(['method' => 'POST', 'route' =>route('email.update')])
        @slot('header', 'Ubah Email')

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
            
            @method('PUT')
            <div class="form-gp focused">
                <label for="email">Email address*</label>
                <input type="email" name="email" value="{{ Auth::user()->email }}" autofocus required>
                <i class="ti-email"></i>
            </div>
{{--             <div class="submit-btn-area mt-2">
                <button id="form_submit" type="submit">Change Email Address</button>
			</div> --}}
            <div class="submit-btn-area">
                <div class="col-md-12 p-0">
                    <div class="row">
                        <div class="col-md-6 p-1">
                            <button id="form_submit" type="submit">Ubah Email Address</button>
                        </div>
                        <div class="col-md-6 p-1">
                            <a href="{{ route('verification.notice') }}"><button type="button">Kembali <i class="ti-back-left"></i></button></a>
                        </div>
                    </div>
                </div>
            </div>
        @endslot
    @endform
@endsection