@extends('layouts.app')

@section('title', 'Daftar Pembayaran')

@section('content')
	<div class="row">
		<div class="col-lg-10 offset-lg-1 mt-5">
			<div class="card card-bordered">
				<div class="card-body text-center">
					@if($orders->count() == 0)
						Tidak ada riwayat pemesanan di akun anda
					@else
						<h4 class="header-title">Daftar Pembayaran</h4>
						<h4 class="header-title" id="date">Tanggal Sekarang : </h4>
						<h4 class="header-title" id="time">Waktu Sekarang : </h4>
						
						@if(session('TLE'))
			                @alert(['type' => 'success', 'dismissable' => 'true'])
			                	{{ session('TLE') }}
			                @endalert
			            @endif	
						@if(session('status'))
			                @alert(['type' => 'success', 'dismissable' => 'true'])
			                	{{ session('status') }}
			                @endalert
			            @endif
						
						<div class="table-responsive">
							<table class="table text-center">
								<thead class="text-uppercase bg-primary">
									<tr class="text-white">
										<th scope="col" class="align-middle">No</th>
										<th scope="col" class="align-middle">Lapangan</th>
										<th scope="col" class="align-middle">Jam Pemesanan</th>
										<th scope="col" class="align-middle">Tanggal Pesan</th>
										<th scope="col" class="align-middle">Status Pembayaran</th>
										<th scope="col" class="align-middle">Batas Waktu Pembayaran</th>
										<th scope="col" class="align-middle">Status Pemesanan</th>
										<th scope="col" class="align-middle">Aksi</th>
									</tr>
								</thead>
								<tbody class="v-middle">
									@foreach($orders as $order)
									<tr>
										<th scope="row">{{ $loop->index + $orders->firstItem() }}</th>
										<td><a target="_blank" href="{{ route('olahraga.show', ['lapangan' => $order->olahraga->lapangan, 'olahraga' => $order->olahraga]) }}">{{ ucwords($order->olahraga->lapangan->name) }} - {{ ucwords($order->olahraga->name) }}</a></td>
										@php
											$time = new \Carbon\Carbon();
											$time->hour = $order->jam_pesan_start;
											$time->minute = 0;
											$time1 = new \Carbon\Carbon();
											$time1->hour = $order->jam_pesan_end;
											$time1->minute = 0;
										@endphp
										<td>{{ $time->format('H:i') }} - {{ $time1->format('H:i') }} {{ $order->olahraga->lapangan->zone }} ({{ $order->length() }} Jam)</td>
										<td>{{ $order->tanggal_pesan->format('d F Y') }}</td>
										<td>
											@if($order->payment_status == "Pending") Menunggu Konfirmasi dari Customer Service 
											@elseif($order->payment_status == "pending") Menunggu Pembayaran
											@elseif($order->payment_status == "canceled") Pembayaran dibatalkan
											@elseif($order->payment_status == "accepted") Pembayaran telah diterima oleh Customer Service
											@elseif($order->payment_status == "denied") Pembayaran ditolak oleh Customer Service
											@endif
										</td>
										@if($order->payment_status == "pending")
										@php
											$time = $order->created_at;
											$time->hour += App\Order::$timeLimit;
										@endphp
										<td @unless(Carbon\Carbon::now() > $time) class="countdown" countdown data-text="%s Hours / %s:%s" data-date="{{ $time }}" @endunless>
											@if(Carbon\Carbon::now() > $time)
												{{ ucwords($order->payment_status) }}
											@else
										    <span data-hours>0</span>:<span data-minutes>0</span>:<span data-seconds>0</span>
											@endif
										</td>
										@else
										<td>-</td>
										@endif
										<td>
											@if($order->status == "ongoing") Pemesanan sedang dimulai
											@elseif($order->status == "pending") Menunggu Waktu Pemesanan Dimulai ({{ $order->tanggal_pesan->format('d F Y') }} {{ $time->format('H:i') }} {{ $order->olahraga->lapangan->zone }})
											@elseif($order->status == "finished") Pemesanan telah selesai
											@elseif($order->status == "canceled") Pemesanan dibatalkan
											@endif
										</td>
										<td>
											<a class="text-white btn btn-flat btn-xs my-1 btn-primary" href="@if($order->payment_status == "pending") {{ route('PO.pembayaran.edit', ['pembayaran' => $order]) }} @else {{ route('PO.pembayaran.show', ['pembayaran' => $order]) }} @endif">@if($order->payment_status == "pending") Bayar @else Lihat @endif<i class="fa fa-location-arrow"></i></a>
											@if($order->payment_status == "pending")
												<a class="text-white btn btn-flat btn-xs my-1 btn-warning remove-record" data-toggle="modal" data-url="{{ route('PO.pembayaran.cancel', ['pembayaran' => $order]) }}" data-target="#modal">Batal<i class="fa fa-times"></i></a>
											@endif
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
	</div>
@endsection

@section('modal')
	<form action="" method="POST" class="remove-record-model">
		@csrf
		@method('delete')
		@modal(['id' => 'modal'])
			@slot('title', 'Cancelation Confirmation')

			@slot('body', 'Apakah anda yakin ingin membatalkan pemesanan?')

			@slot('button')
				<button type="button" class="btn btn-secondary remove-data-from-delete-form" data-dismiss="modal">Batal</button>
				<button type="submit" class="btn btn-primary">Ya</button>
			@endslot
		@endmodal
	</form>
@endsection

@section('script')
	<script src="{{ asset('js/custom.js') }}"></script>
	<script src="{{ asset('js/countdown.js') }}"></script>
	<script type="text/javascript">
		$('.countdown').countdown({
			end: function(){
				location.reload();
			}
		});
	</script>
	<script src="{{ asset('js/moment-with-locales.min.js') }}"></script>
	<script src="{{ asset('js/moment-timezone-with-data.min.js') }}"></script>
	<script type="text/javascript">
		var zone = '{{ Auth::user()->zone }}'
		var tz = '{{ Auth::user()->timezone }}'
		var time = moment().tz(tz).format('HH:mm:ss')
		var date = document.getElementById('date')
		var clock = document.getElementById('time')
		clock.innerText = 'Waktu Sekarang : ' + time + ' ' + zone
		date.innerText = 'Tanggal Sekarang : ' + moment().format('D MMMM Y')
		setInterval(function (){
			var clockTime = moment().tz(tz).format('HH:mm:ss')
			clock.innerText = 'Waktu Sekarang : ' + clockTime + ' ' + zone;
			date.innerText = 'Tanggal Sekarang : ' + moment().format('D MMMM Y')
		}, 1000);
	</script>
@endsection
