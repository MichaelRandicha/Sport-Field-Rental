@extends('layouts.app')

@section('title', 'Customer Service List')

@section('content')
	<div class="row">
		<div class="col-lg-6 offset-lg-3 col-md-6 offset-md-3 mt-5">
			<div class="card">
				<div class="card-body text-center">
					<h4 class="header-title">Customer Service List</h4>
					@if($CS->count() == 0)
						@alert(['type' => 'warning'])
							<h4 class="alert-heading text-left">Create a new Customer Service</h4>
							<p class="text-left">You don't have any Customer Service at the moment, click <a class="alert-link" href="{{ route('CS.create') }}">Create Customer Service</a> or Hover the Manage Customer Service Menu and click <a class="alert-link" href="{{ route('CS.create') }}">Create New</a> to create a new Customer Service.</p>
						@endalert
					@endif
					@if(session('status'))
		                @alert(['type' => 'success', 'dismissable' => 'true'])
		                	{{ session('status') }}
		                @endalert
		            @endif
					<a href="{{ route('CS.create') }}"><button type="button" class="btn btn-flat btn-success mb-3">Create Customer Service</button></a>
					@if($CS->count() > 0)
						<div class="table-responsive">
							<table class="table text-center">
								<thead class="text-uppercase bg-primary">
									<tr class="text-white">
										<th scope="col">No</th>
										<th scope="col">Full Name</th>
										<th scope="col">Email Address</th>
										<th scope="col">Action</th>
									</tr>
								</thead>
								<tbody class="v-middle">
									@foreach($CS as $cs)
									<tr>
										<th scope="row">{{ $loop->index + $CS->firstItem() }}</th>
										<td>{{ $cs->name }}</td>
										<td>{{ $cs->email }}</td>
										<td class="text-left">
											<button class="btn btn-flat btn-xs my-1 btn-primary"><a class="text-white" href="{{ route('CS.show', ['CS' => $cs]) }}">Show <i class="fa fa-edit"></i></a></button>
											<button class="btn btn-flat btn-xs my-1 btn-warning"><a class="text-white" href="{{ route('CS.edit', ['CS' => $cs]) }}">Edit <i class="fa fa-edit"></i></a></button>
											<button class="btn btn-flat btn-xs my-1 btn-danger">
												<a class="text-white remove-record" href="#" class="remove-record" data-toggle="modal" data-url="{{ route('CS.destroy', ['CS' => $cs]) }}" data-target="#modal">Delete <i class="ti-trash"></i></a>
											</button>
										</td>
									</tr>
									@endforeach
								</tbody>
							</table>
							{{ $CS->links() }}
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
@endsection

@section('modal')
	<form action="" method="POST" class="remove-record-model">
		@csrf
		@method('delete')
		@modal(['id' => 'modal'])
			@slot('title', 'Delete Confirmation')

			@slot('body', 'Are you sure you want to delete this Customer Service?')

			@slot('button')
				<button type="button" class="btn btn-secondary remove-data-from-delete-form" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary">Yes</button>
			@endslot
		@endmodal
	</form>
@endsection

@section('script')
	<script src="{{ asset('js/custom.js') }}"></script>
@endsection
