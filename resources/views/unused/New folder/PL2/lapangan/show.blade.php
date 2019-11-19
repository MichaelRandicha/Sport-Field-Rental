@extends('layouts.app')

@section('title', ucwords($lapangan->name))

@section('content')
<div class="card-area">	
	<div class="row">
		<div class="col-lg-8 offset-lg-2">
			<div class="card card-bordered">
				<div class="card-horizontal">
					<a href="{{ $lapangan->img }}" data-fancybox="gallery" data-caption="{{ $lapangan->name }}">
						<img class="card-img-top img-fluid" id="field-top-img" src="{{ $lapangan->img_resized }}" alt="image">
						<span class="bg-primary text-white text-center title-lapangan" title="Rating: {{ $lapangan->realRating }}">{{ $lapangan->name }}<br>
							@for($i = 0; $i < $lapangan->realRating; $i++)
								@if($lapangan->realRating - $i > 0.5)
									<i class="fa fa-star"></i>
								@else
									<i class="fa fa-star-half-o"></i>
								@endif
							@endfor
							@for($i = 0; $i < 5 - ceil($lapangan->realRating);$i++)
								<i class="fa fa-star-o"></i>
							@endfor
							<span style="font-weight: normal;">({{ $lapangan->reviewCount }})</span>
						</span>
					</a>
					<div class="card-body pt-2 {{-- px-2 py-2 --}}">
						<div class="olahraga-type">
							@foreach($lapangan->jenisOlahraga() as $jenis_olahraga)
								<img src="{{ asset('storage/images/icon/'.$jenis_olahraga.'.png') }}" title="{{ $jenis_olahraga }}">
							@endforeach
						</div>
						<div class="table-responsive">
							<table class="table borderless table-sm">
								<tr>
									<td class="field-top-td">Lokasi</td>
									<td>{{ $lapangan->location }}</td>
								</tr>
								<tr>
									<td class="field-top-td">Hari Buka</td>
									<td>{{ $lapangan->hariBuka }}</td>
								</tr>
								<tr>
									<td class="field-top-td">Jam Buka</td>
									<td>
										@if($lapangan->jam_buka == 0 && $lapangan->jam_tutup == 24)
											24 Jam
										@else
											{{ $lapangan->buka }} - {{ $lapangan->tutup }} {{ $lapangan->zone }}
										@endif
									</td>
								</tr>
							</table>
						</div>
						@if(Auth::user()->isPL())
							@if($lapangan->image != null)
								<a href="#" class="btn btn-danger remove-record btn-sm btn-flat btn-flat" data-toggle="modal" data-url="{{ route('lapangan.image.remove', ['lapangan' => $lapangan]) }}" data-target="#lapangan-image-modal">Remove Image</a>
							@endif
						<a href="{{ route('olahraga.create', ['lapangan' => $lapangan]) }}" class="btn btn-primary btn-sm btn-flat my-1">Add Lapangan Olahraga</a>
						{{-- <a href="{{ route('lapangan.edit', ['lapangan' => $lapangan]) }}" class="btn btn-warning btn-sm btn-flat my-1">Edit</a>
						<a href="#" class="btn btn-danger remove-record btn-sm btn-flat my-1" data-toggle="modal" data-url="{{ route('lapangan.destroy', ['lapangan' => $lapangan]) }}" data-target="#modal">Delete</a> --}}
						@elseif(Auth::user()->isCS())
							<a href="{{ route('lapangan.show', ['lapangan' => $lapangan]) }}" class="btn btn-outline-info btn-sm btn-flat my-1 @if(is_numeric(last(Request::segments()))) active @endif">Daftar Lapangan</a>
							<a href="{{ route('lapangan.show', ['lapangan' => $lapangan]) }}" class="btn btn-outline-info btn-sm btn-flat my-1">Daftar Pemesanan</a>
						@endif
						<a href="{{ route('lapangan.index') }}" class="btn btn-info btn-sm btn-flat my-1">Go Back</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		@foreach($olahragas as $olahraga)
			<div class="col-lg-3 col-md-6 mt-5">
				<div class="card card-bordered">
					<a href="{{ $olahraga->img }}" data-fancybox="gallery" data-caption="{{ $olahraga->name }}" >
						<img class="card-img-top img-fluid" style="height:250px;" src="{{ $olahraga->img_resized }}" alt="image">
						<span class="bg-success text-white text-center title-lapangan" title="Rating: {{ $olahraga->realRating }}">{{ $olahraga->name }}@if($olahraga->discount > 0) <span class="badge badge-dark">{{ $olahraga->discount }}% Off</span> @endif<br>
							@for($i = 0; $i < $olahraga->realRating; $i++)
								@if($olahraga->realRating - $i > 0.5)
									<i class="fa fa-star"></i>
								@else
									<i class="fa fa-star-half-o"></i>
								@endif
							@endfor
							@for($i = 0; $i < 5 - ceil($olahraga->realRating);$i++)
								<i class="fa fa-star-o"></i>
							@endfor
							<span style="font-weight: normal;">({{ $olahraga->review() ? $olahraga->review()->count() : 0 }})</span>
						</span>
						@if(Auth::user()->isPL() && $olahraga->image != null)
						<a href="#" class="btn btn-danger remove-record btn-xs btn-flat btn-flat" data-toggle="modal" data-url="{{ route('olahraga.image.remove', ['lapangan' => $lapangan, 'olahraga' => $olahraga]) }}" data-target="#olahraga-image-modal">Remove Image</a>
						@endif
					</a>
					<div class="card-body pt-2 {{-- px-2 py-2 --}}">
						<div class="table-responsive">
							<div class="olahraga-type">
								<img src="{{ asset('storage/images/icon/'.$olahraga->jenis_olahraga.'.png') }}" title="{{ $olahraga->jenis_olahraga }}">
							</div>
							<table class="table borderless">
								<tr>
									<td class="lapangan-td">Harga</td>
									<td>@if($olahraga->harga == 0) Free @else {{ $olahraga->harga }} @endif</td>
								</tr>
								<tr>
									<td class="lapangan-td">Fasilitas</td>
									<td>{{ $olahraga->fasilitas }}</td>
								</tr>
							</table>
						</div>
						<a href="{{ route('olahraga.show', ['lapangan' => $lapangan, 'olahraga' => $olahraga]) }}" class="btn btn-flat @if(Auth::user()->isPL()) btn-xs @endif btn-primary my-1">View Lapangan</a>
						@if(Auth::user()->isPL())
						<a href="{{ route('olahraga.edit', ['lapangan' => $lapangan, 'olahraga' => $olahraga]) }}" class="btn btn-flat btn-warning btn-xs my-1">Edit</a>
						<a href="#" class="btn btn-flat btn-danger btn-xs remove-record my-1" data-toggle="modal" data-url="{{ route('olahraga.destroy', ['lapangan' => $lapangan, 'olahraga' => $olahraga]) }}" data-target="#modal">Delete</a>
						@endif
					</div>
				</div>
			</div>
		@endforeach
	</div>
	<div class="pt-2">
		{{ $olahragas->links('components.pagination-center') }}
	</div>
</div>
@endsection

@section('modal')
	<form action="" method="POST" class="remove-record-model">
		@csrf
		@method('delete')
		@modal(['id' => 'lapangan-image-modal'])
			@slot('title', 'Delete Confirmation')

			@slot('body', "Are you sure you want to remove Lapangan's Image?")

			@slot('button')
				<button type="button" class="btn btn-secondary remove-data-from-delete-form" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary">Yes</button>
			@endslot
		@endmodal
		@modal(['id' => 'olahraga-image-modal'])
			@slot('title', 'Delete Confirmation')

			@slot('body', "Are you sure you want to remove this Lapangan Olahraga's Image?")

			@slot('button')
				<button type="button" class="btn btn-secondary remove-data-from-delete-form" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary">Yes</button>
			@endslot
		@endmodal
		@modal(['id' => 'modal'])
			@slot('title', 'Delete Confirmation')

			@slot('body', 'Are you sure you want to delete this Lapangan Olahraga?')

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