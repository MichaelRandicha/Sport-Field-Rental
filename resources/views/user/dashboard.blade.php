@extends('layouts.app')

@section('title', 'Daftar Lapangan')

@section('content')
<div class="card-area">
	@if($lapangans->count() == 0)
		@if(Request::has('search'))
			<div class="mt-3 text-center">
				@alert(['type' => 'warning', 'class' => 'mb-0'])
					<h4 class="alert-heading">Lapangan tidak ditemukan</h4>
					<p>Pencarian untuk "<strong>{{ Request::get('search') }}</strong>" tidak ditemukan. Coba kata kunci lainnya</p>
				@endalert
			</div>
		@elseif(Request::has('filter'))
			<div class="mt-3 text-center">
				@alert(['type' => 'warning', 'class' => 'mb-0'])
					<h4 class="alert-heading">Lapangan tidak ditemukan</h4>
					<p>Pencarian untuk Jenis Olahraga <strong>
						@foreach(Request::get('filter') as $filter)
							@if($loop->last && $loop->first)
								{{ $filter }}
							@elseif($loop->last)
								and {{ $filter }}
							@else
								{{ $filter }}, 
							@endif
						@endforeach
					</strong> tidak ditemukan. Coba dengan jenis olahraga lainnya</p>
				@endalert
			</div>
		@else
			@if(Auth::user()->isPL())
			<div class="mt-3">
				@alert(['type' => 'warning', 'class' => 'mb-0'])
					<h4 class="alert-heading">Buat lapangan baru</h4>
					<p>Kamu tidak memiliki lapangan saat ini, tekan <a class="alert-link" href="{{ route('lapangan.create') }}">Buat Lapangan</a> untuk membuat Lapangan baru.</p>
				@endalert
			</div>
			@elseif(Auth::user()->isCS())
			<div class="mt-3">
				@alert(['type' => 'warning', 'class' => 'mb-0'])
					<h4 class="alert-heading">0 Lapangan dikelola</h4>
					<p>Kamu tidak memiliki lapangan yang kamu kelola saat ini.</p>
				@endalert
			</div>
			@else
			<div class="mt-3">
				@alert(['type' => 'warning', 'class' => 'mb-0'])
					<h4 class="alert-heading">Tidak ada lapangan saat ini</h4>
					<p>Saat ini, belum ada lapangan yang tersedia.</p>
				@endalert
			</div>
			@endif
		@endif
	@endif
	@if(Auth::user()->isPL())
	<a href="{{ route('lapangan.create') }}"><button type="button" class="btn btn-flat btn-success mt-3">Buat Lapangan</button></a>
	@endif
	<div class="row">
		@foreach($lapangans as $lapangan)
			<div class="col-lg-3 col-md-6 mt-5">
				<div class="card card-bordered">
					<a href="{{ $lapangan->img }}" data-fancybox="{{ $lapangan->name }}" data-caption="{{ ucwords($lapangan->name) }}" >
						<img class="card-img-top img-fluid" style="height:250px;" src="{{ $lapangan->img_resized }}" alt="image">
						
						<span class="bg-primary text-white text-center title-lapangan" title="Average Rating: {{ $lapangan->realRating }}">{{ $lapangan->name }}<br>
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
						@if(Auth::user()->isPL() && $lapangan->image != null)
						<a href="#" class="btn btn-danger remove-record btn-xs btn-flat btn-flat" data-toggle="modal" data-url="{{ route('lapangan.image.remove', ['lapangan' => $lapangan]) }}" data-target="#image-modal">Hapus Gambar</a>
						@endif
					</a>
					<div class="card-body lapangan-card pt-2 {{-- px-2 py-2 --}}">
						<div class="olahraga-type">
							@foreach($lapangan->jenisOlahraga() as $jenis_olahraga)
								<img src="{{ asset('storage/images/icon/'.$jenis_olahraga.'.png') }}" title="{{ $jenis_olahraga }}">
							@endforeach
						</div>
						<div class="table-responsive">
							<table class="table borderless">
								<tr>
									<td class="lapangan-td">Lokasi</td>
									<td>{{ $lapangan->location }}</td>
								</tr>
								<tr>
									<td class="lapangan-td">Hari Buka</td>
									<td>{{ $lapangan->hariBuka }}</td>
								</tr>
								<tr>
									<td class="lapangan-td">Jam Buka</td>
									<td>
										@if($lapangan->jam_buka == 0 && $lapangan->jam_tutup == 24)
											24 Jam
										@else
											{{ $lapangan->buka }} - {{ $lapangan->tutup }} {{ $lapangan->zone }}
										@endif
									</td>
								</tr>
								@isset($lapangan->no_telepon)
								<tr>
									<td class="lapangan-td">Nomor Telepon</td>
									<td>{{ $lapangan->no_telepon }}</td>
								</tr>
								@endisset
							</table>
						</div>
						<a href="{{ route('lapangan.show', ['lapangan' => $lapangan]) }}" class="btn btn-primary @if(Auth::user()->isPL()) btn-xs @endif btn-flat my-1">Lihat Lapangan</a>
						@if(Auth::user()->isPL())
						<a href="{{ route('lapangan.edit', ['lapangan' => $lapangan]) }}" class="btn btn-warning btn-xs btn-flat my-1">Ubah</a>
						<a href="#" class="btn btn-danger remove-record btn-xs btn-flat my-1" data-toggle="modal" data-url="{{ route('lapangan.destroy', ['lapangan' => $lapangan]) }}" data-target="#modal">Hapus</a>
						@endif
					</div>
				</div>
			</div>
		@endforeach
	</div>
	@if($lapangans->hasPages())
		<div class="pt-2">
			{{ $lapangans->links('components.pagination-center') }}
		</div>
	@endif
</div>
@endsection

@section('modal')
	<form action="" method="POST" class="remove-record-model">
		@csrf
		@method('delete')
		@modal(['id' => 'image-modal'])
			@slot('title', 'Removal Confirmation')

			@slot('body', "Apakah anda yakin ingin menghapus gambar lapangan ini?")

			@slot('button')
				<button type="button" class="btn btn-secondary remove-data-from-delete-form" data-dismiss="modal">Batal</button>
				<button type="submit" class="btn btn-primary">Ya</button>
			@endslot
		@endmodal
		@modal(['id' => 'modal'])
			@slot('title', 'Delete Confirmation')

			@slot('body', 'Apakah anda yakin ingin menghapus lapangan ini?')

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