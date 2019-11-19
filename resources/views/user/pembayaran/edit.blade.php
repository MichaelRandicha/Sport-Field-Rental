@extends('layouts.app')

@section('title', 'Pembayaran Pemesanan')

@section('content')
    @form(['method' => 'POST', 'route' =>route('PO.pembayaran.update', ['order' => $order]), 'fileupload' => 'yes'])
        @slot('header', 'Pembayaran Pemesanan')

        @slot('body')
            @method('PUT')
            @if(!$errors->isEmpty())
                @alert(['type' => 'danger', 'dismissable' => 'true'])
                <ul>
                    @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                    @endforeach
                </ul>
                @endalert
            @endif

            <div class="table-responsive">
                <table class="table borderless">
                    <tr>
                        <th>Nama</td>
                        <td>{{ Auth::user()->name }}</td>
                    </tr>
                    <tr>
                        <th>Lapangan</th>
                        <td><a target="_blank" href="{{ route('olahraga.show', ['lapangan' => $order->olahraga->lapangan, 'olahraga' => $order->olahraga]) }}">{{ ucwords($order->olahraga->lapangan->name) }} - {{ ucwords($order->olahraga->name) }}</a></td>
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
                        <td>{{ $time->format('H:i') }} - {{ $time1->format('H:i') }} {{ $order->olahraga->lapangan->zone }} ({{ $order->length() }} Jam)</td>
                    </tr>
                    <tr>
                        <th>Batas Waktu Pembayaran</th>
                        <td>
                            @php
                            $time = $order->created_at;
                            $time->hour += App\Order::$timeLimit;
                            @endphp
                            <span @unless(Carbon\Carbon::now() > $time) class="countdown" countdown data-text="%s Hours / %s:%s" data-date="{{ $time }}" @endunless>
                                @if(Carbon\Carbon::now() > $time)
                                    @if($order->payment_status == "Pending") Waiting for Confirmation @else {{ ucwords($order->payment_status) }} @endif
                                @else
                                <span data-hours>0</span>:<span data-minutes>0</span>:<span data-seconds>0</span>
                                @endif
                            </span>
                        </td>
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
                        <th colspan="2">Gambar Kartu Identitas</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="px-0 py-0 text-center">
                            <div class="avatar-upload my-0">
                                <div class="avatar-edit" style="top: 0">
                                    <input type="file" id="identity_card" name="identity_card_image" accept="image/x-png, image/jpeg" />
                                    <label for="identity_card"></label>
                                </div>
                                <a id="identity_card_img" href="{{ asset('assets/images/user/image-default.png') }}" data-fancybox="gallery" data-caption="Identity Card Image" >
                                <div class="avatar-preview rounded-0">
                                    <div id="identity_card_preview" class="rounded-0" style="background-image: url({{ asset('assets/images/user/image-default.png') }});">
                                        </div>
                                </div>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @unless($order->harga_per_jam == 0)
                    <tr>
                        <th colspan="2">Gambar Bukti Pembayaran</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="px-0 py-0 text-center">
                            <div class="avatar-upload my-0">
                                <div class="avatar-edit" style="top: 0">
                                    <input type="file" id="payment" name="payment_image" accept="image/x-png, image/jpeg" />
                                    <label for="payment"></label>
                                </div>
                                <a id="payment_img" href="{{ asset('assets/images/user/image-default.png') }}" data-fancybox="gallery" data-caption="Payment Image" >
                                <div class="avatar-preview rounded-0">
                                    <div id="payment_preview" class="rounded-0" style="background-image: url({{ asset('assets/images/user/image-default.png') }});">
                                        </div>
                                </div>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endunless
                </table>
            </div>
            <div class="submit-btn-area">
                <div class="col-md-12 p-0">
                    <div class="row">
                        <div class="col-md-6 p-1">
                            <button id="form_submit" type="submit">Simpan <i class="ti-arrow-right"></i></button>
                        </div>
                        <div class="col-md-6 p-1">
                            <a href="{{ route('PO.pembayaran.index') }}"><button id="form_submit" type="button">Kembali <i class="ti-back-left"></i></button></a>
                        </div>
                    </div>
                </div>
            </div>
        @endslot
    @endform
@endsection

@section('script')
    <script src="{{ asset('js/custom-file-input.js') }}"></script>
    <script src="{{ asset('js/countdown.js') }}"></script>
    <script type="text/javascript">
        $('.countdown').countdown({
            end: function(){
                location.reload();
            }
        });
        $("#identity_card").change(function() {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#identity_card_preview').css('background-image', 'url('+e.target.result +')');
                    $('#identity_card_img').attr('href', e.target.result);
                    $('#identity_card_preview').hide();
                    $('#identity_card_preview').fadeIn(650);
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
        $("#payment").change(function() {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#payment_preview').css('background-image', 'url('+e.target.result +')');
                    $('#payment_img').attr('href', e.target.result);
                    $('#payment_preview').hide();
                    $('#payment_preview').fadeIn(650);
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    </script>
@endsection