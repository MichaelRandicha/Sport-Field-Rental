@extends('layouts.app')

@section('title')
	{{ ucwords($lapangan->name).' - '.ucwords($olahraga->name) }}
	@if(is_numeric(last(Request::segments())))
	- Pemesanan
	@elseif(last(Request::segments()) == "review")
	- Ulasan
	@elseif(last(Request::segments()) == "pembayaran")
	- Pembayaran
	@endif
@endsection

@section('content')
<div class="card-area">	
	<div class="row">
		<div class="col-lg-8 offset-lg-2">
			<div class="card card-bordered">
				<div class="card-horizontal">
					<a href="{{ $olahraga->img }}" data-fancybox="gallery" data-caption="{{$lapangan->name}}<br>{{ $olahraga->name }}">
						<img class="card-img-top img-fluid" id="field-top-img" src="{{ $olahraga->img_resized }}" alt="image">
						<span class="bg-success text-white text-center title-lapangan" id="title-lapangan" title="Average Rating: {{ $olahraga->realRating }}">
							{{ $lapangan->name }}<br>
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
					</a>
					<div class="card-body pt-2 {{-- px-2 py-2 --}}">
						<div class="olahraga-type">
							<img src="{{ asset('storage/images/icon/'.$olahraga->jenis_olahraga.'.png') }}" title="{{ $olahraga->jenis_olahraga }}">
						</div>
						<div class="table-responsive">
							<table class="table borderless table-sm">
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
							@if($olahraga->image != null)
								<a href="#" class="btn btn-danger remove-record btn-sm btn-flat btn-flat" data-toggle="modal" data-url="{{ route('olahraga.image.remove', ['lapangan' => $lapangan, 'olahraga' => $olahraga]) }}" data-target="#olahraga-image-modal">Hapus Gambar</a>
							@endif
						@if($olahraga->harga_per_jam > 0)
						<a href="{{ route('olahraga.discount.manage', ['lapangan' => $lapangan, 'olahraga' => $olahraga]) }}" class="btn btn-primary btn-sm btn-flat my-1">Kelola Diskon</a>
						@endif
						{{-- <a href="{{ route('olahraga.edit', ['lapangan' => $lapangan, 'olahraga' => $olahraga]) }}" class="btn btn-warning btn-sm btn-flat my-1">Edit</a>
						<a href="#" class="btn btn-danger remove-record btn-sm btn-flat my-1" data-toggle="modal" data-url="{{ route('olahraga.destroy', ['lapangan' => $lapangan, 'olahraga' => $olahraga]) }}" data-target="#modal">Delete</a> --}}
						@endif
						<a href="{{ route('olahraga.show', ['lapangan' => $lapangan, 'olahraga' => $olahraga]) }}" class="btn btn-outline-info btn-sm btn-flat my-1 @if(is_numeric(last(Request::segments()))) active @endif">Pemesanan</a>
						@if(Auth::user()->isCS())
							<a href="{{ route('pembayaran.index', ['lapangan' => $lapangan, 'olahraga' => $olahraga]) }}" class="btn btn-outline-info btn-sm btn-flat my-1 @if(last(Request::segments()) == "pembayaran") active @endif">Daftar Pembayaran</a>
						@endif
						<a href="{{ route('olahraga.review.index', ['lapangan' => $lapangan, 'olahraga' => $olahraga]) }}" class="btn btn-outline-info btn-sm btn-flat my-1 @if(last(Request::segments()) == "review") active @endif">Ulasan</a>
						<a href="{{ route('lapangan.show', ['lapangan' => $lapangan]) }}" class="btn btn-info btn-sm btn-flat my-1">Kembali</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		@if(is_numeric(last(Request::segments())))
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
										@if(Carbon\Carbon::now()->setTimezone($lapangan->timezone)->toDateString() > $day->toDateString())
										<td class="bg-secondary"></td>
										@else
											@if($olahraga->isBooked($day, $time->hour))
												@if($olahraga->isOrderStart($day, $time->hour))
												@php
													$order = $olahraga->OrderAt($day, $time->hour);
												@endphp
												<td class="bg-light" rowspan="{{ $order->length() }}">
													@if(Auth::user()->isPO())
														@if($order->user->id == Auth::user()->id)
															@if($order->payment_status == 'pending')
																<a target="_blank" href="{{ route('PO.pembayaran.edit', ['pembayaran' => $order]) }}">Dipesan oleh anda, Tekan untuk melakukan pembayaran</a>
															@else
																<a target="_blank" href="{{ route('PO.pembayaran.show', ['order' => $order]) }}">Dipesan oleh anda</a>
															@endif
														@else
															Telah dipesan
														@endif
													@elseif($olahraga->OrderAt($day, $time->hour)->user->isPO())
														{{ $olahraga->OrderAt($day, $time->hour)->user->name }}
													@else
														{{ $olahraga->OrderAt($day, $time->hour)->name }} 
													@endif
												</td>
												@endif
											@elseif($day->toDateString() == $time->toDateString())
												@if($time->hour <= Carbon\Carbon::now()->setTimezone($lapangan->timezone)->hour + App\Order::$timeLimit && Auth::user()->isPO())
												<td class="bg-secondary"></td>
												@elseif($time->hour <= Carbon\Carbon::now()->setTimezone($lapangan->timezone)->hour)
												<td class="bg-secondary"></td>
												@elseif(!Auth::user()->isPL())
													<td rowspan=""><a href="{{ route('order.create', ['lapangan' => $lapangan, 'olahraga' => $olahraga, 'tanggal_pesan' => $day->toDateString(), 'jam_mulai' => $time->hour]) }}" class="btn btn-outline-primary btn-xs">Pesan</a></td>
												@else
													<td></td>
												@endif
											@else
												@if(Auth::user()->isPL())
													<td></td>
												@else
												<td rowspan=""><a href="{{ route('order.create', ['lapangan' => $lapangan, 'olahraga' => $olahraga, 'tanggal_pesan' => $day->toDateString(), 'jam_mulai' => $time->hour]) }}" class="btn btn-outline-primary btn-xs">Pesan</a></td>
												@endif
											@endif
										@endif
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
		@elseif(last(Request::segments()) == "review")
			@if($reviews->count() == 0)
				@if(Auth::user()->isPO() && Auth::user()->order()->where('lapangan_olahraga_id', $olahraga->id)->where('status', 'finished')->count() > Auth::user()->review()->where('lapangan_olahraga_id', $olahraga->id)->count())
					<div class="col-lg-8 offset-lg-2 mt-5">
						<div class="card card-bordered">
							<div class="card-body">
								<div class="media">
									<div class="media-body">
										<form method="POST" action="{{ route('olahraga.review.add', ['lapangan' => $lapangan, 'olahraga' => $olahraga]) }}">
											@csrf
											<span class="rating">
												<input type="radio" class="rating-input" id="star-5-0" name="rating" value="5">
												<label for="star-5-0" class="fa rating-star text-warning mb-0"></label>
												<input type="radio" class="rating-input" id="star-4-0" name="rating" value="4">
												<label for="star-4-0" class="fa rating-star text-warning mb-0"></label>
												<input type="radio" class="rating-input" id="star-3-0" name="rating" value="3">
												<label for="star-3-0" class="fa rating-star text-warning mb-0"></label>
												<input type="radio" class="rating-input" id="star-2-0" name="rating" value="2">
												<label for="star-2-0" class="fa rating-star text-warning mb-0"></label>
												<input type="radio" class="rating-input" id="star-1-0" name="rating" value="1">
												<label for="star-1-0" class="fa rating-star text-warning mb-0"></label>
											</span>
											<h5>Oleh <span class="text-primary">{{ Auth::user()->name }}</span> - <span class="text-secondary">{{ \Carbon\Carbon::now()->setTimezone(Auth::user()->timezone)->format('l, j F Y') }}</span></h5>
											<div class="form-group mt-1 mb-2">
												<textarea class="form-control" id="review-0" name="review" rows="2" placeholder="Masukkan ulasan mengenai lapangan olahraga ini"></textarea>
											</div>
											<button type="submit" class="btn btn-success btn-sm">Berikan Ulasan</button>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				@else
					<div class="col-lg-8 offset-lg-2 text-center mt-5">
					<div class="card card-bordered">
						<div class="card-body">
							Belum ada ulasan di Lapangan Olahraga ini
						</div>
					</div>
				</div>
				@endif
			@else
				@if(Auth::user()->isPO() && Auth::user()->order()->where('lapangan_olahraga_id', $olahraga->id)->where('status', 'finished')->count() > Auth::user()->review()->where('lapangan_olahraga_id', $olahraga->id)->count())
					<div class="col-lg-8 offset-lg-2 mt-5">
						<div class="card card-bordered">
							<div class="card-body">
								<div class="media">
									<div class="media-body">
										<form method="POST" action="{{ route('olahraga.review.add', ['lapangan' => $lapangan, 'olahraga' => $olahraga]) }}">
											@csrf
											<span class="rating">
												<input type="radio" class="rating-input" id="star-5-0" name="rating" value="5">
												<label for="star-5-0" class="fa rating-star text-warning mb-0"></label>
												<input type="radio" class="rating-input" id="star-4-0" name="rating" value="4">
												<label for="star-4-0" class="fa rating-star text-warning mb-0"></label>
												<input type="radio" class="rating-input" id="star-3-0" name="rating" value="3">
												<label for="star-3-0" class="fa rating-star text-warning mb-0"></label>
												<input type="radio" class="rating-input" id="star-2-0" name="rating" value="2">
												<label for="star-2-0" class="fa rating-star text-warning mb-0"></label>
												<input type="radio" class="rating-input" id="star-1-0" name="rating" value="1">
												<label for="star-1-0" class="fa rating-star text-warning mb-0"></label>
											</span>
											<h5>Oleh <span class="text-primary">{{ Auth::user()->name }}</span> - <span class="text-secondary">{{ \Carbon\Carbon::now()->setTimezone(Auth::user()->timezone)->format('l, j F Y') }}</span></h5>
											<div class="form-group mt-1 mb-2">
												<textarea class="form-control" id="review-0" name="review" rows="2" placeholder="Masukkan ulasan mengenai lapangan olahraga ini"></textarea>
											</div>
											<button type="submit" class="btn btn-success btn-sm">Tambah Ulasan</button>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				@endif
					<div class="col-lg-8 offset-lg-2 mt-5">
					@foreach($reviews as $review)
						<div class="card card-bordered mb-3">
							<div class="card-body">
								<div class="media">
									<div class="media-body">
										<span title="Rating: {{ $review->rating }}">
											@for($i = 0; $i < $review->rating; $i++)
												@if($review->rating - $i > 0.5)
													<i class="fa fa-star text-warning"></i>
												@else
													<i class="fa fa-star-half-o text-warning"></i>
												@endif
											@endfor
											@for($i = 0; $i < 5 - ceil($review->rating);$i++)
												<i class="fa fa-star-o text-warning"></i>
											@endfor
										</span>
										<h5>Oleh <span class="text-primary">{{ $review->user->name }}</span> @if($review->user->isCS())<span class="badge badge-primary">CS</span>@endif - <span class="text-secondary">{{ $review->created_at->format('l, j F Y') }}</span>
											@if(Auth::user()->id == $review->user->id)
											<a href="{{ route('olahraga.review.edit', ['lapangan' => $lapangan, 'olahraga' => $olahraga, 'review' => $review]) }}" class="btn btn-flat btn-warning btn-xs">Ubah</a>
											@endif
										</h5>{{ $review->review }}
									</div>
								</div>
								@if($review->tanggapan)
								<hr>
								<div class="media child-media">
									<div class="media-body">
										<h5>Oleh <span class="text-primary">{{ strtoupper($lapangan->name) }}</span> <span class="badge badge-primary">CS</span>
										@if(Auth::user()->isCS() && Auth::user()->serve()->where('lapangan_id', $lapangan->id)->count() > 0)
											<a href="{{ route('olahraga.review.edit', ['lapangan' => $lapangan, 'olahraga' => $olahraga, 'review' => $review]) }}" class="btn btn-flat btn-warning btn-xs">Ubah</a>
										@endif
										</h5>
										{{ $review->tanggapan }}
									</div>
								</div>
								@elseif(Auth::user()->isCS())
								<hr>
								<div class="media child-media">
									<div class="media-body">
										<form method="POST" action="{{ route('olahraga.review.reply', ['lapangan' => $lapangan, 'olahraga' => $olahraga, 'review' => $review]) }}">
											@csrf
											<h5>Oleh <span class="text-primary">{{ strtoupper($lapangan->name) }}</span> <span class="badge badge-primary">CS</span>
											</h5>
											<div class="form-group mt-1 mb-2">
												<textarea class="form-control" id="review-{{ $loop->iteration }}" name="review" rows="2" placeholder="Berikan Tanggapan terhadap ulasan"></textarea>
											</div>
											<button type="submit" class="btn btn-success btn-sm">Berikan Tanggapan</button>
										</form>
									</div>
								</div>
								@endif
							</div>
						</div>
					@endforeach
					{{ $reviews->links('components.pagination-center') }}
					</div>
			@endif
		@elseif(last(Request::segments()) == "pembayaran")
			<div class="col-lg-8 offset-lg-2 mt-5">
				<div class="card card-bordered">
					<div class="card-body text-center">
					@if($orders->count() == 0)
							Belum ada pemesanan online
					@else
						<h4 class="header-title">Daftar Pembayaran</h4>
						@if(session('status'))
			                @alert(['type' => 'success', 'dismissable' => 'true'])
			                	{{ session('status') }}
			                @endalert
			            @endif

						<div class="table-responsive">
							<table class="table text-center">
								<thead class="text-uppercase bg-primary">
									<tr class="text-white">
										<th scope="col">No</th>
										<th scope="col">Nama</th>
										<th scope="col">Jam Pemesanan</th>
										<th scope="col">Tanggal Pesan</th>
										<th scope="col">Status Pembayaran</th>
										<th scope="col">Aksi</th>
									</tr>
								</thead>
								<tbody class="v-middle">
									@foreach($orders as $order)
									<tr>
										<th scope="row">{{ $loop->index + $orders->firstItem() }}</th>
										<td>{{ $order->user->name }}</td>
										@php
											$time = new \Carbon\Carbon();
											$time->hour = $order->jam_pesan_start;
											$time->minute = 0;
											$time1 = new \Carbon\Carbon();
											$time1->hour = $order->jam_pesan_end;
											$time1->minute = 0;
										@endphp
										<td>{{ $time->format('H:i') }} - {{ $time1->format('H:i') }}  {{ $lapangan->zone }} ({{ $order->length() }} Jam)</td>
										<td>{{ $order->tanggal_pesan->format('d F Y') }}</td>
										<td>
											@if($order->payment_status == "Pending") Menunggu Konfirmasi pembayaran dari anda
											@elseif($order->payment_status == "pending") Menunggu Pembayaran
											@elseif($order->payment_status == "canceled") Pembayaran dibatalkan
											@elseif($order->payment_status == "accepted") Pembayaran telah diterima oleh Customer Service
											@elseif($order->payment_status == "denied") Pembayaran ditolak oleh Customer Service
											@endif
										</td>
										<td>
											<button class="btn btn-flat btn-xs my-1 btn-primary"><a class="text-white" href="{{ route('pembayaran.show', ['lapangan' => $lapangan, 'olahraga' => $olahraga, 'pembayaran' => $order]) }}">@if($order->payment_status == "Pending") Verifikasi @else Lihat @endif<i class="fa fa-edit"></i></a></button>
											{{-- <button class="btn btn-flat btn-xs my-1 btn-warning"><a class="text-white" href="{{ route('CS.edit', ['CS' => $cs]) }}">Edit <i class="fa fa-edit"></i></a></button> --}}
											{{-- <button class="btn btn-flat btn-xs my-1 btn-danger">
												<a class="text-white remove-record" href="#" class="remove-record" data-toggle="modal" data-url="{{ route('CS.destroy', ['CS' => $cs]) }}" data-target="#modal">Delete <i class="ti-trash"></i></a>
											</button> --}}
										</td>
									</tr>
									@endforeach
								</tbody>
							</table>
							{{ $orders->links('components.pagination-center') }}
						</div>
					@endif
					</div>
				</div>
			</div>
		@endif
	</div>
</div>
@endsection

@section('modal')
	<form action="" method="POST" class="remove-record-model">
		@csrf
		@method('delete')
		@modal(['id' => 'modal'])
			@slot('title', 'Delete Confirmation')

			@slot('body', 'Apakah anda yakin ingin menghapus lapangan olahraga ini?')

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
