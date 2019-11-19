@extends('layouts.app')

@section('title', 'Customer Service List')

@section('content')
	<div class="row">
		<div class="col-lg-6 offset-lg-3 col-md-6 offset-md-3 mt-5">
			<div class="card card-bordered">
				<div class="card-body text-center">
					<h4 class="header-title">Daftar Customer Service</h4>
					@if($CS->count() == 0)
						@alert(['type' => 'warning'])
							<h4 class="alert-heading text-left">Buat Customer Service Baru</h4>
							<p class="text-left">Kamu tidak memiliki customer service saat ini, tekan <a class="alert-link" href="{{ route('CS.create') }}">Buat Customer Service</a>untuk membuat Customer Service Baru.</p>
						@endalert
					@endif
					@if(session('status'))
		                @alert(['type' => 'success', 'dismissable' => 'true'])
		                	{{ session('status') }}
		                @endalert
		            @endif
					<a href="{{ route('CS.create') }}"><button type="button" class="btn btn-flat btn-success mb-3">Buat Customer Service</button></a>
					@if($CS->count() > 0)
						<div class="table-responsive">
							<table class="table text-center">
								<thead class="text-uppercase bg-primary">
									<tr class="text-white">
										<th scope="col">No</th>
										<th scope="col">Nama</th>
										<th scope="col">Email Address</th>
										<th scope="col">Aksi</th>
									</tr>
								</thead>
								<tbody class="v-middle">
									@foreach($CS as $cs)
									<tr>
										<th scope="row">{{ $loop->index + $CS->firstItem() }}</th>
										<td>{{ $cs->name }}</td>
										<td>{{ $cs->email }}</td>
										<td>
											<button class="btn btn-flat btn-xs my-1 btn-primary"><a class="text-white" href="{{ route('CS.show', ['CS' => $cs]) }}">Lihat <i class="fa fa-location-arrow"></i></a></button>
											<button class="btn btn-flat btn-xs my-1 btn-warning"><a class="text-white" href="{{ route('CS.edit', ['CS' => $cs]) }}">Ubah <i class="fa fa-edit"></i></a></button>
											<button class="btn btn-flat btn-xs my-1 btn-danger">
												<a class="text-white remove-record" href="#" class="remove-record" data-toggle="modal" data-url="{{ route('CS.destroy', ['CS' => $cs]) }}" data-target="#modal">Hapus <i class="ti-trash"></i></a>
											</button>
										</td>
									</tr>
									@endforeach
								</tbody>
							</table>
							{{ $CS->links('components.pagination-center') }}
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

			@slot('body', 'Apakah anda yakin ingin menghapus customer service tersebut?')

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
