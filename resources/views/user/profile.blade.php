@extends('layouts.app')

@section('title', 'Profile Saya')

@section('content')
	@form(['method' => 'POST', 'route' =>route('user.profile.update'), 'fileupload' => 'yes'])
		@slot('header', 'Profile Saya')
		@slot('header_p', 'Jenis Akun : '.$user->tipeUser())

		@slot('body')
			@method('PUT')
			@if (session('status'))
				@alert(['type' => 'success', 'dismissable' => 'true'])
				{{ session('status') }}
				@endalert
			@endif

			@if(!empty($success))
				@alert(['type' => 'success', 'dismissable' => 'true'])
				{!! nl2br(e($success)) !!}
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
			{{-- <div class="avatar-upload mt-0">
		        <div class="avatar-edit">
		            <input type="file" id="imageUpload" name="profile_image" accept="image/x-png, image/jpeg" />
		            <label for="imageUpload"></label>
		        </div>
		        <div class="avatar-preview">
		            <div id="imagePreview" style="background-image: url({{ asset('assets/images/user/default.png') }});">
		            </div>
		        </div>
		    </div> --}}

            <input type="hidden" name="tz" id="tz">
			<div class="form-gp focused">
				<label for="name">Full Name</label>
				<input type="name" name="name" value="{{ $user->name }}" required>
				<i class="ti-info"></i>
			</div>
			<div class="form-gp focused">
				<label for="email">Email address
					@if($user->email_verified_at !== null)
					<a href="#" role="button" data-toggle="popover" data-trigger="focus" data-placement="top"title="" data-content="Emailmu telah diverifikasi" data-original-title="Verified Email">
						<i class="fa fa-check-circle" style="color:green;position:inherit"></i>
					</a>
					@endif
				</label>
				<input type="email" name="email" value="{{ $user->email }}" required>
				<i class="ti-email"></i>
			</div>
			<div class="form-gp">
				<label for="password">Password</label>
				<input type="password" name="password" required>
				<i class="ti-lock"></i>
			</div>
			<div class="submit-btn-area">
				<div class="col-md-12 p-0">
					<div class="row">
						<div class="col-md-6 p-1">
							<button id="form_submit" type="submit">Simpan <i class="ti-arrow-right"></i></button>
						</div>
						<div class="col-md-6 p-1">
							<a href="{{ route('user.password.reset') }}"><button id="form_submit" type="button">Atur Ulang Password <i class="ti-arrow-right"></i></button></a>
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
    <script type="text/javascript">
		$("#imageUpload").change(function() {
		    if (this.files && this.files[0]) {
		        var reader = new FileReader();
		        reader.onload = function(e) {
		            $('#imagePreview').css('background-image', 'url('+e.target.result +')');
		            $('#imagePreview').hide();
		            $('#imagePreview').fadeIn(650);
		        }
		        reader.readAsDataURL(this.files[0]);
		    }
		});
    </script>
@endsection

{{-- <div class="form-box ptb--50">
	<form method="POST" action="{{ route('user.profile.update') }}">
		@csrf
		<div class="form-head">
			<h4>User Profile</h4>
			<p>Account Type : {{ $user->tipeUser() }}</p>
		</div>

		<div class="form-body">
			@if (session('status'))
				@alert(['type' => 'success'])
				{{ session('status') }}
				@endalert
			@endif

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
			<div class="form-gp focused">
				<label for="email">Full Name</label>
				<input type="email" name="email" value="{{ $user->name }}" required>
				<i class="ti-info"></i>
			</div>
			<div class="form-gp focused">
				<label for="email">Email address
					@if($user->email_verified_at !== null)
					<a href="#" role="button" data-toggle="popover" data-trigger="focus" data-placement="top"title="" data-content="This means that your email is already verified" data-original-title="Verified Email">
						<i class="fa fa-check-circle" style="color:green;position:inherit"></i>
					</a>
					@endif
				</label>
				<input type="email" name="email" value="{{ $user->email }}" required>
				<i class="ti-email"></i>
			</div>
			<div class="form-gp">
				<label for="password">Password</label>
				<input type="password" name="password" required>
				<i class="ti-lock"></i>
			</div>
			<div class="submit-btn-area">
				<div class="col-md-12 p-0">
					<div class="row">
						<div class="col-md-6 p-0">
							<button id="form_submit" type="submit">Save <i class="ti-arrow-right"></i></button>
						</div>
						<div class="col-md-6 p-0">
							<a href="{{ route('user.password.reset') }}"><button id="form_submit" type="button">Reset Password <i class="ti-arrow-right"></i></button></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div> --}}

{{-- <div class="card-area align-item-center">
	<div class="col-lg-4 offset-lg-4 col-md-6 offset-md-3 mt-5">
		<div class="card">
			<div class="card-body">
				<h4 class="header-title">User Profile</h4>
				<form method="POST" action="{{ route('user.password.update') }}">
					@csrf

					@if (session('status'))
					@alert(['type' => 'success'])
					{{ session('status') }}
					@endalert
					@endif

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
						<label for="user_type" class="col-form-label">Account Type</label>
						<input class="form-control" type="text" name="user_type" value="{{ $user->tipeUser() }}" disabled>
					</div>
					<div class="form-group">
						<label for="name" class="col-form-label">Full Name</label>
						<input class="form-control" type="text" name="name" value="{{ $user->name }}">
					</div>
					<div class="form-group">
						<label for="email" class="col-form-label">Email Address
							<a href="#" role="button" data-toggle="popover" data-trigger="focus" data-placement="top"title="" data-content="This means that your email is already verified" data-original-title="Verified Email">
								<i class="fa fa-check-circle" style="color:green;"></i>
							</a>
						</label>
						<input class="form-control" type="text" name="email" value="{{ $user->email }}">
					</div>
					<div class="form-group">
						<label for="password" class="col-form-label">Password</label>
						<input class="form-control" type="password" name="password">
					</div>
					<button class="btn btn-success">Save</button>
					<a class="btn btn-primary" href="{{ route('user.password.reset') }}">Reset Password</a>
				</form>
			</div>
		</div>
	</div>
</div> --}}