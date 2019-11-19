@extends('layouts.app')

@section('title')
	@if($order->payment_status == "Pending")
	Verifikasi
	@else
	Lihat
	@endif
	Pembayaran
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
											@endif
											/ Jam
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
							<a href="{{ route('olahraga.show', ['lapangan' => $lapangan, 'olahraga' => $olahraga]) }}" class="btn btn-outline-info btn-sm btn-flat my-1">Pemesanan</a>
							<a href="{{ route('pembayaran.index', ['lapangan' => $lapangan, 'olahraga' => $olahraga]) }}" class="btn btn-outline-info btn-sm btn-flat my-1 active">Daftar Pembayaran</a>
							<a href="{{ route('olahraga.review.index', ['lapangan' => $lapangan, 'olahraga' => $olahraga]) }}" class="btn btn-outline-info btn-sm btn-flat my-1">Ulasan</a>
							<a href="{{ route('lapangan.show', ['lapangan' => $lapangan]) }}" class="btn btn-info btn-sm btn-flat my-1">Kembali</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	    <div class="row">
			<div class="col-lg-6 offset-lg-3 col-md-6 offset-md-3 mt-5">
				<div class="card card-bordered">
					<div class="card-body">
					<div class="header-title text-center">
						@if($order->payment_status == "Pending")
						Verifikasi
						@else
						Lihat
						@endif 
						Pemesanan 
					</div>
					<table class="table borderless table-sm">
						<tr>
							<th>Nama Pemesan</th>
							<td>{{ $order->user->name }}</td>
						</tr>
		                <tr>
		                    <th>Tanggal Pemesanan</th>
		                    <td>{{ $order->tanggal_pesan->format('j F Y') }}</td>
		                </tr>
		                <tr>
		                	@php
								$time = new \Carbon\Carbon();
								$time->hour = $order->jam_pesan_start;
								$time->minute = 0;
								$time1 = new \Carbon\Carbon();
								$time1->hour = $order->jam_pesan_end;
								$time1->minute = 0;
							@endphp
		                    <th>Jam Pemesanan</th>
		                    <td>{{ $time->format('H:i') }} - {{ $time1->format('H:i') }} {{ $lapangan->zone }} ({{ $order->length() }} Jam)</td>
		                </tr>
		                <tr>
		                    <th>Biaya Pemesanan</th>
		                    <td>@if($order->harga_per_jam == 0) Free @else Rp. {{ number_format(($order->harga_per_jam - (($order->discount != null ? $order->discount->discount : 0) * $order->harga_per_jam / 100)) * ($order->jam_pesan_end - $order->jam_pesan_start), 0, '', '.') }}  @if($order->discount != null) <br>(Diskon {{ $order->discount->discount }}%, Harga Awal Rp. {{ number_format($order->harga_per_jam * ($order->jam_pesan_end - $order->jam_pesan_start), 0, '', '.') }} ) @endif @endif</td>
		                </tr>
		                <tr>
	                        <th>Rekening Yang Dituju ({{ $order->rekening->jenis_rekening }})</th>
	                        <td>{{ $order->rekening->rekening }} (atas nama {{ $order->rekening->rekening_atas_nama }})</td>
	                    </tr>
		                <tr>
		                	<th>Status Pembayaran</th>
		                	<td>
		                		@if($order->payment_status == "Pending") Menunggu Konfirmasi pembayaran dari anda
		                		@elseif($order->payment_status == "pending") Menunggu Pembayaran
		                		@elseif($order->payment_status == "canceled") Pembayaran dibatalkan
		                		@elseif($order->payment_status == "accepted") Pembayaran telah diterima oleh Customer Service
		                		@elseif($order->payment_status == "denied") Pembayaran ditolak oleh Customer Service
		                		@endif
		                	</td>
		                </tr>
		                @if($order->payment_status == 'accepted')
						<tr>
		                    <th>Status Pemesanan</th>
		                    <td>
		                    	@if($order->status == "ongoing") Pemesanan sedang dimulai
		                    	@elseif($order->status == "pending") Menunggu Waktu Pemesanan Dimulai ({{ $order->tanggal_pesan->format('d F Y') }} {{ $time->format('H:i') }} {{ $order->olahraga->lapangan->zone }})
		                    	@elseif($order->status == "finished") Pemesanan telah selesai
		                    	@elseif($order->status == "canceled") Pemesanan dibatalkan
		                    	@endif
		                    </td>
		                </tr>
		                @endif
		                @if($order->identity_card_img != null)
							<tr>
								<th>Gambar Kartu Identitas</th>
								<td>
		                            <div class="avatar-upload m-0">
		                                <a id="identity_card_img" href="{{ $order->ICPath }}" data-fancybox="gallery" data-caption="Identity Card Image" >
		                                <div class="avatar-preview rounded-0">
		                                    <div id="identity_card_preview" class="rounded-0" style="background-image: url({{ $order->ICPath }});">
		                                        </div>
		                                </div>
		                                </a>
		                            </div>
		                        </td>
							</tr>
		                @endif
		                @if($order->payment_img != null)
							<tr>
								<th>Gambar Bukti Pembayaran</th>
								<td>
		                            <div class="avatar-upload m-0">
		                                <a id="payment_img" href="{{ $order->PPath }}" data-fancybox="gallery" data-caption="Payment Image" >
		                                <div class="avatar-preview rounded-0">
		                                    <div id="identity_card_preview" class="rounded-0" style="background-image: url({{ $order->PPath }});">
		                                        </div>
		                                </div>
		                                </a>
		                            </div>
		                        </td>
							</tr>
		                @endif
		            </table>
		            <div class="text-center">
		            	@if($order->payment_status == "Pending")
		            	<div class="row">
		            		<div class="col-lg-6 mb-2">
		            			<a class="btn btn-success btn-flat w-100 remove-record" href="#" data-toggle="modal" data-url="{{ route('pembayaran.accept', ['lapangan' => $lapangan, 'olahraga' => $olahraga, 'order' => $order]) }}" data-target="#accept-modal">Terima Pembayaran</a>
		            		</div>
		            		<div class="col-lg-6 mb-2">
		            			<a class="btn btn-danger btn-flat w-100 remove-record" href="#" data-toggle="modal" data-url="{{ route('pembayaran.deny', ['lapangan' => $lapangan, 'olahraga' => $olahraga, 'order' => $order]) }}" data-target="#deny-modal">Tolak Pembayaran</a>
		            		</div>
		            	</div>
		            	@endif
		            	<a class="btn btn-primary btn-flat w-100" href="{{ route('pembayaran.index', ['lapangan' => $lapangan, 'olahraga' => $olahraga]) }}">Kembali</a>
		            </div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@if($order->payment_status == "Pending")
	@section('modal')
		<form action="" method="POST" class="remove-record-model">
			@csrf
			@method('PUT')
			@modal(['id' => 'accept-modal'])
				@slot('title', 'Accept Confirmation')

				@slot('body', "Apakah anda yakin ingin menerima pembayaran ini?")

				@slot('button')
					<button type="button" class="btn btn-secondary remove-data-from-delete-form" data-dismiss="modal">Batal</button>
					<button type="submit" class="btn btn-primary">Ya</button>
				@endslot
			@endmodal
			@modal(['id' => 'deny-modal'])
				@slot('title', 'Deny Confirmation')

				@slot('body', 'Apakah anda yakin ingin menolak pembayaran ini?')

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
@endif
