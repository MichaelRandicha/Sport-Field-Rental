@extends('layouts.app')

@section('title', 'Create Customer Service')

@section('content')
    @form(['method' => 'POST', 'route' =>route('CS.store')])
        @slot('header', 'Create Customer Service')

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
{{--             <div class="form-group">
                <label class="col-form-label" style="color:#7e74ff">Lapangan</label>
                <select class="custom-select">
                	<option selected="selected" disabled hidden>-</option>
                	<option value="1">One</option>
                </select>
            </div> --}}
            <div class="submit-btn-area">
				<div class="col-md-12 p-0">
					<div class="row">
						<div class="col-md-6 p-1">
							<button id="form_submit" type="submit">Submit <i class="ti-arrow-right"></i></button>
						</div>
						<div class="col-md-6 p-1">
							<a href="{{ route('CS.index') }}"><button id="form_submit" type="button">Go Back <i class="ti-back-left"></i></button></a>
						</div>
					</div>
				</div>
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