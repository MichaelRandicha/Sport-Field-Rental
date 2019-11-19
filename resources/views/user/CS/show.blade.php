@extends('layouts.app')

@section('title', 'Customer Service - '.$CS->name)

@section('content')
    <div class="row">
		<div class="col-lg-6 offset-lg-3 col-md-6 offset-md-3 mt-5">
			<div class="card card-bordered">
				<div class="card-body text-center">
					<h4 class="header-title">Kelola Customer Service</h4>
					@if($services->count() == 0)
						@alert(['type' => 'warning'])
							<h4 class="alert-heading text-left">Tambah Lapangan yang dikelola oleh Customer Service</h4>
							<p class="text-left">Customer Servicemu tidak mengelola lapangan saat ini, tekan <a class="alert-link" href="{{ route('service.create', ['CS' => $CS]) }}">Tambah Lapangan</a> untuk menambah lapangan yang dikelola oleh Customer Service.</p>
						@endalert
					@endif
					@if(session('status'))
		                @alert(['type' => 'success', 'dismissable' => 'true'])
		                	{{ session('status') }}
		                @endalert
		            @endif
					<a href="{{ route('service.create', ['CS' => $CS]) }}"><button type="button" class="btn btn-flat btn-success mb-3">Tambah Lapangan</button></a>
					<a href="{{ route('CS.index') }}"><button type="button" class="btn btn-flat btn-warning mb-3">Kembali</button></a>
					@if($services->count() > 0)
						<div class="single-table">
							<div class="table-responsive">
								<table class="table text-center">
									<thead class="text-uppercase bg-primary">
										<tr class="text-white">
											<th scope="col">No</th>
											<th scope="col">Lapangan</th>
											<th scope="col">Aksi</th>
										</tr>
									</thead>
									<tbody class="v-middle">
										@foreach($services as $service)
										<tr>
											<th scope="row">{{ $loop->index + $services->firstItem() }}</th>
											<td>{{ ucwords($service->lapangan->name) }}</td>
											<td>
												<button class="btn btn-flat btn-xs btn-danger">
													<a class="text-white remove-record" href="#" class="remove-record" data-toggle="modal" data-url="{{ route('service.destroy', ['CS' => $CS, 'service' => $service]) }}" data-target="#modal">Hapus <i class="ti-trash"></i></a>
												</button>
											</td>
										</tr>
										@endforeach
									</tbody>
								</table>
								{{ $services->links('components.pagination-center') }}
							</div>
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

			@slot('body', 'Apakah anda yakin ingin menghapus pengelolaan lapangan tersebut dari customer service ini?')

			@slot('button')
				<button type="button" class="btn btn-secondary remove-data-from-delete-form" data-dismiss="modal">Batal</button>
				<button type="submit" class="btn btn-primary">Ya</button>
			@endslot
		@endmodal
	</form>
@endsection

@section('script')
	<script src="{{ asset('js/custom.js') }}"></script>
@endsection