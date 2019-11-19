@extends('layouts.app')

@section('title', ucwords($lapangan->name).' - '.ucwords($olahraga->name))

@section('content')
<div class="card-area">	
	<div class="row">
		<div class="col-lg-8 offset-lg-2">
			<div class="card card-bordered">
				<div class="card-horizontal">
					<a href="{{ $olahraga->img }}" data-fancybox="gallery" data-caption="{{$lapangan->name}}<br>{{ $olahraga->name }}">
						<img class="card-img-top img-fluid" id="field-top-img" src="{{ $olahraga->img_resized }}" alt="image">
						<span class="bg-success text-white text-center title-lapangan" title="Rating: {{ $olahraga->realRating }}">{{$lapangan->name}}<br>{{ $olahraga->name }}@if($olahraga->discount > 0) <span class="badge badge-dark">{{ $olahraga->discount }}% Off</span> @endif<br>
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
							<span style="font-weight: normal;">({{ $olahraga->reviewCount }})</span>
						</span>
					</a>
					<div class="card-body pt-2 {{-- px-2 py-2 --}}">
						<div class="olahraga-type">
							<img src="{{ asset('storage/images/icon/'.$olahraga->jenis_olahraga.'.png') }}" title="{{ $olahraga->jenis_olahraga }}">
						</div>
						<div class="table-responsive">
							<table class="table borderless table-sm">
								<tr>
									<td class="lapangan-td">Harga</td>
									<td>@if($olahraga->harga == 0) Free @else {{ $olahraga->harga }} @endif</td>
								</tr>
								<tr>
									<td class="lapangan-td">Fasilitas</td>
									<td>{{ $olahraga->fasilitas }}</td>
								</tr>
								<tr>
									<td class="field-top-td">Hari Buka</td>
									<td>{{ $lapangan->hariBuka }}</td>
								</tr>
							</table>
						</div>
						@if(Auth::user()->isPL())
							@if($olahraga->image != null)
								<a href="#" class="btn btn-danger remove-record btn-sm btn-flat btn-flat" data-toggle="modal" data-url="{{ route('olahraga.image.remove', ['lapangan' => $lapangan, 'olahraga' => $olahraga]) }}" data-target="#olahraga-image-modal">Remove Image</a>
							@endif
						<a href="{{ route('olahraga.discount.edit', ['lapangan' => $lapangan, 'olahraga' => $olahraga]) }}" class="btn btn-primary btn-sm btn-flat my-1">Manage Discount</a>
						{{-- <a href="{{ route('olahraga.edit', ['lapangan' => $lapangan, 'olahraga' => $olahraga]) }}" class="btn btn-warning btn-sm btn-flat my-1">Edit</a>
						<a href="#" class="btn btn-danger remove-record btn-sm btn-flat my-1" data-toggle="modal" data-url="{{ route('olahraga.destroy', ['lapangan' => $lapangan, 'olahraga' => $olahraga]) }}" data-target="#modal">Delete</a> --}}
						@endif
						<a href="{{ route('lapangan.show', ['lapangan' => $lapangan]) }}" class="btn btn-info btn-sm btn-flat my-1">Go Back</a>
						@if(Auth::user()->isPL())
						<br>
						@endif
						<a href="{{ route('olahraga.show', ['lapangan' => $lapangan, 'olahraga' => $olahraga]) }}" class="btn btn-outline-info btn-sm btn-flat my-1 @if(is_numeric(last(Request::segments()))) active @endif">Pemesanan</a>
						@if(Auth::user()->isCS())
							{{-- <a href="{{ route('olahraga.show', ['lapangan' => $lapangan, 'olahraga' => $olahraga]) }}" class="btn btn-outline-info btn-sm btn-flat my-1 @if(last(Request::segments()) == "pembayaran") active @endif">Daftar Pembayaran</a> --}}
						@endif
						{{-- <a href="{{ route('olahraga.review', ['lapangan' => $lapangan]) }}" class="btn btn-outline-info btn-sm btn-flat my-1">Go Back</a> --}}
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 mt-5">
			<div class="card">
				<div class="table-responsive">
					<table class="table text-center table-bordered mb-0">
						<thead class="text-uppercase bg-primary">
							<tr class="text-white">
								<th scope="col"></th>
								@foreach($week as $day)
									@if($lapangan->dayOfWeek($day->dayOfWeek))
										<th scope="col">{{ $day->format('j F Y') }}</th>
									@endif
								@endforeach
							</tr>
						</thead>
						<tbody class="v-middle">
							@for($i = $lapangan->jam_buka; $i < $lapangan->jam_tutup; $i++)
							<tr>
								@php
									$time = new \Carbon\Carbon();
									$time->hour = $i;
									$time->minute = 0;
									$time1 = new \Carbon\Carbon();
									$time1->hour = $i + 1;
									$time1->minute = 0;
								@endphp
								<th scope="row">{{ $time->format('H:i') }} - {{ $time1->format('H:i') }}</th>
								@foreach($week as $day)
									@if($lapangan->dayOfWeek($day->dayOfWeek))
										{{-- @if($olahraga) --}}
										<td rowspan=""></td>
									@endif
								@endforeach
							</tr>
							@endfor
						</tbody>
					</table>
				</div>
			</div>
			@if($week->hasPages())
				<div class="pt-2">
					{{ $week->links('components.pagination-center') }}
				</div>
			@endif
		</div>
	</div>
</div>
@endsection

@section('modal')
	<form action="" method="POST" class="remove-record-model">
		@csrf
		@method('delete')
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