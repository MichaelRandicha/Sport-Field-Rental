@extends('layouts.app')

@section('title', 'Lihat Pembayaran')

@section('content')
    <div class="row">
		<div class="col-lg-6 offset-lg-3 col-md-6 offset-md-3 mt-5">
			<div class="card card-bordered">
				<div class="card-body">
				<div class="header-title text-center">
					View Order 
				</div>
				<table class="table borderless table-sm">
	                <tr>
	                    <th>Tanggal Pemesanan</th>
	                    <td>{{ $order->tanggal_pesan->format('j F Y') }}</td>
	                </tr>
	                <tr>
	                    <th>Lokasi Lapangan</th>
	                    <td><a target="_blank" href="{{ route('olahraga.show', ['lapangan' => $order->olahraga->lapangan, 'olahraga' => $order->olahraga]) }}">{{ ucwords($order->olahraga->lapangan->name) }} - {{ ucwords($order->olahraga->name) }}</a></td>
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
	                    <td>{{ $time->format('H:i') }} - {{ $time1->format('H:i') }} {{ $order->olahraga->lapangan->zone }} ({{ $order->length() }} Jam)</td>
	                </tr>
	                <tr>
	                    <th>Biaya Pemesanan</th>
	                    <td>@if($order->harga_per_jam == 0) Free @else Rp. {{ number_format(($order->harga_per_jam - (($order->discount != null ? $order->discount->discount : 0) * $order->harga_per_jam / 100)) * ($order->jam_pesan_end - $order->jam_pesan_start), 0, '', '.') }} @if($order->discount != null) <br>(Diskon {{ $order->discount->discount }}%, Harga Awal Rp. {{ number_format($order->harga_per_jam * ($order->jam_pesan_end - $order->jam_pesan_start), 0, '', '.') }} ) @endif @endif</td>
	                </tr>
                    <tr>
                        <th>Rekening Yang Dituju ({{ $order->rekening->jenis_rekening }})</th>
                        <td>{{ $order->rekening->rekening }} (atas nama {{ $order->rekening->rekening_atas_nama }})</td>
                    </tr>
	                <tr>
	                    <th>Status Pembayaran</th>
	                    <td>
							@if($order->payment_status == "Pending") Menunggu Konfirmasi dari Customer Service 
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
	            	<a class="btn btn-primary btn-flat w-100" href="{{ route('PO.pembayaran.index') }}">Kembali</a>
	            </div>
				</div>
			</div>
		</div>
	</div>
@endsection
