@extends('layouts.app')

@section('title', ucwords($lapangan->name))

@section('content')
<div class="card-area">	
	<div class="row">
		<div class="col-lg-8 offset-lg-2">
			<div class="card card-bordered">
				<div class="card-horizontal">
					<a href="{{ $lapangan->img }}" data-fancybox="{{ $lapangan->name }}" data-caption="{{ $lapangan->name }}">
						<img class="card-img-top img-fluid" id="field-top-img" src="{{ $lapangan->img_resized }}" alt="image">
						<span class="bg-primary text-white text-center title-lapangan" id="title-lapangan" title="Average Rating: {{ $lapangan->realRating }}">{{ $lapangan->name }}<br>
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
								@if($lapangan->no_telepon)
								<tr>
									<td class="field-top-td">Nomor Telepon</td>
									<td>{{ $lapangan->no_telepon }}</td>
								</tr>
								@endif
							</table>
						</div>
						@if(Auth::user()->isPL())
							@if($lapangan->image != null)
								<a href="#" class="btn btn-danger remove-record btn-sm btn-flat btn-flat" data-toggle="modal" data-url="{{ route('lapangan.image.remove', ['lapangan' => $lapangan]) }}" data-target="#lapangan-image-modal">Hapus Gambar</a>
							@endif
						<a href="{{ route('olahraga.create', ['lapangan' => $lapangan]) }}" class="btn btn-primary btn-sm btn-flat my-1">Buat Lapangan Olahraga</a>
						{{-- <a href="{{ route('lapangan.edit', ['lapangan' => $lapangan]) }}" class="btn btn-warning btn-sm btn-flat my-1">Edit</a>
						<a href="#" class="btn btn-danger remove-record btn-sm btn-flat my-1" data-toggle="modal" data-url="{{ route('lapangan.destroy', ['lapangan' => $lapangan]) }}" data-target="#modal">Delete</a> --}}
						@elseif(Auth::user()->isCS())
							<a href="{{ route('lapangan.show', ['lapangan' => $lapangan]) }}" class="btn btn-outline-info btn-sm btn-flat my-1 @if(is_numeric(last(Request::segments()))) active @endif">Daftar Lapangan</a>
							<a href="{{ route('pemesanan.index', ['lapangan' => $lapangan]) }}" class="btn btn-outline-info btn-sm btn-flat my-1 @if(last(Request::segments()) == "pemesanan") active @endif">Daftar Pemesanan</a>
						@endif
						<a href="{{ route('lapangan.index') }}" class="btn btn-info btn-sm btn-flat my-1">Kembali</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	@if(is_numeric(last(Request::segments())))
	<div class="row">
		@foreach($olahragas as $olahraga)
			<div class="col-lg-3 col-md-6 mt-5">
				<div class="card card-bordered">
					<a href="{{ $olahraga->img }}" data-fancybox="{{ $olahraga->name }}" data-caption="{{ $olahraga->name }}" >
						<img class="card-img-top img-fluid" style="height:250px;" src="{{ $olahraga->img_resized }}" alt="image">
						<span class="bg-success text-white text-center title-lapangan" title="Rating: {{ $olahraga->realRating }}">
							{{ $olahraga->name }}
							@if($olahraga->diskon != null) 
							<br>
                            <span class="badge badge-dark">
                                Diskon {{ $olahraga->diskon->discount }}% Sampai 
                                @php
                                    $diskon = Carbon\Carbon::parse($olahraga->diskon->sampai_tanggal);
                                    $diskon->hour = $olahraga->diskon->sampai_jam;
                                    $start = Carbon\Carbon::parse($olahraga->diskon->dari_tanggal);
                                    $start->hour = $olahraga->diskon->dari_jam;
                                @endphp
                                    {{ Carbon\Carbon::parse($olahraga->diskon->sampai_tanggal)->format('d F') }} 
                                <br>Jam {{ $start->format('H:i') }} - {{ $diskon->format('H:i') }}
                            </span> 
                            @endif
							<br>
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
						@if(Auth::user()->isPL() && $olahraga->image != null)
						<a href="#" class="btn btn-danger remove-record btn-xs btn-flat btn-flat" data-toggle="modal" data-url="{{ route('olahraga.image.remove', ['lapangan' => $lapangan, 'olahraga' => $olahraga]) }}" data-target="#olahraga-image-modal">Hapus Gambar</a>
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
									<td>
										@if($olahraga->harga_per_jam == 0)
											Free
										@else
											Rp. 
											@if($olahraga->diskon != null)
											<del>{{ number_format($olahraga->harga_per_jam, 0, '', '.') }}</del>
											{{ number_format(($olahraga->harga_per_jam - ($olahraga->diskon->discount * $olahraga->harga_per_jam / 100)), 0, '', '.') }} 
											@else 
											{{ number_format(($olahraga->harga_per_jam - ($olahraga->diskon * $olahraga->harga_per_jam / 100)), 0, '', '.') }}
											@endif
											/ Jam
										@endif
									</td>
								</tr>
								<tr>
									<td class="lapangan-td">Fasilitas</td>
									<td>{{ $olahraga->fasilitas }}</td>
								</tr>
							</table>
						</div>
						<a href="{{ route('olahraga.show', ['lapangan' => $lapangan, 'olahraga' => $olahraga]) }}" class="btn btn-flat @if(Auth::user()->isPL()) btn-xs @endif btn-primary my-1">Lihat Lapangan</a>
						@if(Auth::user()->isPL())
						<a href="{{ route('olahraga.edit', ['lapangan' => $lapangan, 'olahraga' => $olahraga]) }}" class="btn btn-flat btn-warning btn-xs my-1">Ubah</a>
						<a href="#" class="btn btn-flat btn-danger btn-xs remove-record my-1" data-toggle="modal" data-url="{{ route('olahraga.destroy', ['lapangan' => $lapangan, 'olahraga' => $olahraga]) }}" data-target="#modal">Hapus</a>
						@endif
					</div>
				</div>
			</div>
		@endforeach
	</div>
	<div class="pt-2">
		{{ $olahragas->links('components.pagination-center') }}
	</div>
	@elseif(last(Request::segments()) == "pemesanan")
		@if($orders->count() == 0)
			<div class="row">
				<div class="col-md-8 offset-md-2 mt-5">
					<div class="card card-bordered">
						<div class="card-body text-center">
						<h4 class="header-title">Pemesanan pada Hari ini</h4>
							Belum ada pemesanan pada hari ini
						</div>
					</div>
				</div>
			</div>	
		@else
			<div class="row">
				<div class="col-md-8 offset-md-2 mt-5">
					<div class="card card-bordered">
						<div class="card-body text-center">
						<h4 class="header-title">Pemesanan pada Hari ini</h4>
						<h4 class="header-title" id="clock"></h4>
							<div class="table-responsive">
								<table class="table text-center">
									<thead class="text-uppercase bg-primary">
										<tr class="text-white">
											<th scope="col">No</th>
											<th scope="col">Name</th>
											<th scope="col">Lapangan Olahraga</th>
											<th scope="col">Tanggal Pesan</th>
											<th scope="col">Status Pemesanan</th>
											<th scope="col">Informasi Pembayaran</th>
										</tr>
									</thead>
									<tbody class="v-middle">
										@foreach($orders as $order)
											<tr>
												<th>{{ $loop->index + $orders->firstItem() }}</th>
												<td>@if($order->user->isCS()) {{ $order->name }} @else {{ $order->user->name }} @endif</td>
												<td><a href="{{ route('olahraga.show', ['lapangan' => $lapangan, 'olahraga' => $order->olahraga]) }}">{{ ucwords($order->olahraga->name) }}</a></td>
												@php
													$time = new \Carbon\Carbon();
													$time->hour = $order->jam_pesan_start;
													$time->minute = 0;
													$time1 = new \Carbon\Carbon();
													$time1->hour = $order->jam_pesan_end;
													$time1->minute = 0;
												@endphp
												<td>
													{{ $time->format('H:i') }} - {{ $time1->format('H:i') }} {{ $lapangan->zone }} ({{ $order->length() }} Jam)
												</td>
												<td>
													@if($order->status == "ongoing") Pemesanan sedang dimulai
													@elseif($order->status == "pending") Menunggu Waktu Pemesanan Dimulai
													@elseif($order->status == "finished") Pemesanan telah selesai
													@elseif($order->status == "canceled") Pemesanan dibatalkan
													@endif
												</td>
												<td>
													@if($order->user->isCS())
														Pemesanan Offline
													@else
														<button class="btn btn-flat btn-xs my-1 btn-primary">
															<a class="text-white" target="_blank" href="{{ route('pembayaran.show', ['lapangan' => $lapangan, 'olahraga' => $order->olahraga, 'order' => $order]) }}">Lihat<i class="fa fa-edit"></i></a>
														</button> 
													@endif
												</td>
												{{-- <td>
													<button class="btn btn-flat btn-xs my-1 btn-primary">
														<a class="text-white" href="{{ route('pemesanan.show', ['lapangan' => $lapangan, 'order' => $order]) }}">Show<i class="fa fa-edit"></i></a>
													</button>
												</td> --}}
											</tr>
										@endforeach
									</tbody>
								</table>
								<div class="pt-2">
									{{ $orders->links('components.pagination-center') }}
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		@endif
	@endif
</div>
@endsection

@section('modal')
	<form action="" method="POST" class="remove-record-model">
		@csrf
		@method('delete')
		@modal(['id' => 'lapangan-image-modal'])
			@slot('title', 'Removal Confirmation')

			@slot('body', "Apakah anda yakin ingin menghapus gambar lapangan ini?")

			@slot('button')
				<button type="button" class="btn btn-secondary remove-data-from-delete-form" data-dismiss="modal">Batal</button>
				<button type="submit" class="btn btn-primary">Ya</button>
			@endslot
		@endmodal
		@modal(['id' => 'olahraga-image-modal'])
			@slot('title', 'Removal Confirmation')

			@slot('body', "Apakah anda yakin inign menghapus gambar lapangan olahraga ini?")

			@slot('button')
				<button type="button" class="btn btn-secondary remove-data-from-delete-form" data-dismiss="modal">Batal</button>
				<button type="submit" class="btn btn-primary">Ya</button>
			@endslot
		@endmodal
		@modal(['id' => 'modal'])
			@slot('title', 'Delete Confirmation')

			@slot('body', 'Apakah anda yakin inign menghapus lapangan olahraga ini?')

			@slot('button')
				<button type="button" class="btn btn-secondary remove-data-from-delete-form" data-dismiss="modal">Batal</button>
				<button type="submit" class="btn btn-primary">Ya</button>
			@endslot
		@endmodal
	</form>
@endsection

@section('script')
	<script src="{{ asset('js/custom.js') }}"></script>
	@if(last(Request::segments()) == "pemesanan")
	<script src="{{ asset('js/moment-with-locales.min.js') }}"></script>
	<script src="{{ asset('js/moment-timezone-with-data.min.js') }}"></script>
	<script type="text/javascript">
		var tz = '{{ $lapangan->timezone }}'
		var zone = '{{ $lapangan->zone }}'
		var time = moment().tz(tz).format('HH:mm:ss')
		clock.innerText = 'Jam Sekarang : ' + time + ' ' + zone;
		setInterval(function (){
			var clockTime = moment().tz(tz).format('HH:mm:ss')
			var clock = document.getElementById('clock')
			clock.innerText = 'Jam Sekarang : ' + clockTime + ' ' + zone;
		}, 1000);
	</script>
	@endif
@endsection