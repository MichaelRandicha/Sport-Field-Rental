@extends('layouts.app')

@section('title', 'Change Password')

@section('content')
	@form(['method' => 'POST', 'route' =>route('user.password.update')])
		@slot('header', 'Change Password')

		@slot('body')
			@method('PUT')
			@if(!empty($success))
				@alert(['type' => 'success', 'dismissable' => 'true'])
				{{ $success }}
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
			<div class="form-gp">
				<label for="password">Old Password</label>
				<input type="password" name="password" required autofocus>
				<i class="ti-lock"></i>
			</div>
			<div class="form-gp">
				<label for="new_password">New Password</label>
				<input type="password" name="new_password" required>
				<i class="ti-lock"></i>
			</div>
			<div class="form-gp">
				<label for="new_password_confirmation">New Password Confirmation</label>
				<input type="password" name="new_password_confirmation" required>
				<i class="ti-lock"></i>
			</div>
			<div class="submit-btn-area">
				<div class="col-md-12 p-0">
					<div class="row">
						<div class="col-md-6 p-1">
							<button id="form_submit" type="submit">Atur Ulang Password <i class="ti-arrow-right"></i></button>
						</div>
						<div class="col-md-6 p-1">
							<a href="{{ route('user.profile') }}"><button id="form_submit" type="button">Kembali <i class="ti-back-left"></i></button></a>
						</div>
					</div>
				</div>
			</div>
		@endslot
	@endform
@endsection
{{-- <div class="form-box ptb--50">
	<form method="POST" action="{{ route('user.password.update') }}">
		@csrf
		<div class="form-head">
			<h4>Password Reset</h4>
		</div>

		<div class="form-body">
			@if(!empty($success))
				@alert(['type' => 'success'])
				{{ $success }}
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
			<div class="form-gp">
				<label for="password">Old Password</label>
				<input type="password" name="password" required autofocus>
				<i class="ti-lock"></i>
			</div>
			<div class="form-gp">
				<label for="new_password">New Password</label>
				<input type="password" name="new_password" required>
				<i class="ti-lock"></i>
			</div>
			<div class="form-gp">
				<label for="new_password_confirmation">New Password Confirmation</label>
				<input type="password" name="new_password_confirmation" required>
				<i class="ti-lock"></i>
			</div>
			<div class="submit-btn-area">
				<div class="col-md-12 p-0">
					<div class="row">
						<div class="col-md-6 p-0">
							<button id="form_submit" type="submit">Reset Password <i class="ti-arrow-right"></i></button>
						</div>
						<div class="col-md-6 p-0">
							<a href="{{ route('user.profile') }}"><button id="form_submit" type="button">Go Back <i class="ti-back-left"></i></button></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div> --}}

{{-- <div class="container">
	<div class="card-area align-item-center">
		<div class="col-lg-4 offset-lg-4 col-md-6 offset-md-3 mt-5">
			<div class="card">
				<div class="card-body">
					<h4 class="header-title">User Profile</h4>
					<form method="POST" action="{{ route('user.password.update') }}">
						@csrf

						@if(!empty($success))
						@alert(['type' => 'success'])
						{{ $success }}
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

						<div class="form-group">
							<label for="password" class="col-form-label">Old Password</label>
							<input class="form-control" type="password" name="password">
						</div>
						<div class="form-group">
							<label for="new_password" class="col-form-label">New Password</label>
							<input class="form-control" type="password" name="new_password">
						</div>
						<div class="form-group">
							<label for="new_password_confirmation" class="col-form-label">New Password Confirmation</label>
							<input class="form-control" type="password" name="new_password_confirmation">
						</div>
						<button class="btn btn-success">Reset Password</button>
						<a class="btn btn-primary" href="{{ route('user.profile') }}">Go Back</a>
					</form>
				</div>
			</div>
		</div>
	</div>
</div> --}}