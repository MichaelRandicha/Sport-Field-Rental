<div class="alert-items @isset($dismissable) alert-dismiss @endisset">
	<div class="alert alert-{{ $type }} @isset($dismissable) alert-dismissible fade show @endisset @isset($class) {{ $class }} @endisset" role="alert">
		{{ $slot }}
		@isset($dismissable)
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span class="fa fa-times"></span>
			</button>
		@endisset
	</div>
</div>