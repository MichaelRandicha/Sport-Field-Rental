@extends('layouts.app')

@section('title', 'Pesan Lapangan Olahraga')

@section('content')
    {{-- @if(Auth::user()->isPO()) --}}
    @form(['method' => 'POST', 'route' => route('order.store', ['lapangan' => $lapangan, 'olahraga' => $olahraga])])
    {{-- @else
    @form(['method' => 'POST', 'route' => route('order.store', ['lapangan' => $lapangan, 'olahraga' => $olahraga]), 'fileupload' => 'yes'])
    @endif --}}
        @slot('header', 'Pesan Lapangan Olahraga')

        @slot('body')
            @if(!$errors->isEmpty())
                @alert(['type' => 'danger', 'dismissable' => 'true'])
                <ul>
                    @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                    @endforeach
                </ul>
                @endalert
            @endif
            @unless(Auth::user()->isPO())
            <div class="form-gp @if(old('name')) focused @endif">
                <label for="name">Nama Pemesan*</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus>
                <i class="ti-user"></i>
            </div>
            @endunless
            <input type="hidden" name="tanggal_pesan" value="{{ Request::get('tanggal_pesan') }}">
            <table class="table borderless">
                <tr>
                    <th class="pl-0">Tanggal Pemesanan</th>
                    <td class="pr-0">{{ Carbon\Carbon::make(Request::get('tanggal_pesan'))->format('j F Y') }}</td>
                </tr>
                <tr>
                    <th class="pl-0">Lokasi Pemesanan</th>
                    <td class="pr-0">{{ ucwords($lapangan->name) }} - {{ ucwords($olahraga->name) }}</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="form-group row mb-0">
                            <div class="col-lg-6 px-1">
                                <label style="color:#7e74ff">Jam Mulai</label>
                                <select class="form-control form-control-lg" name="jam_mulai" id="jam_mulai">
                                    @php
                                        $mulai = new Carbon\Carbon();
                                        $mulai->hour = Request::get('jam_mulai');
                                        $mulai->minute = 0;
                                        $mulai = $mulai->format('H:i');
                                    @endphp
                                    <option value="{{ Request::get('jam_mulai') }}" selected>{{ $mulai }}</option>
                                </select>
                            </div>
                            <div class="col-lg-6 px-1">
                                <label style="color:#7e74ff">Jam Selesai</label>
                                <select class="form-control form-control-lg" name="jam_selesai" id="jam_selesai">
                                    @foreach($jam_selesai as $jam)
                                        @php
                                            $selesai = new Carbon\Carbon();
                                            $selesai->hour = $jam;
                                            $selesai->minute = 0;
                                            $selesai = $selesai->format('H:i');
                                        @endphp
                                        <option value="{{ $jam }}">{{ $selesai }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th class="pl-0">Lama Pemesanan</th>
                    <td class="pr-0" id="lama_pemesanan">1 Jam</td>
                </tr>
                <tr>
                    <th class="pl-0">Biaya Pemesanan</th>
                    <td class="pr-0" id="biaya_pemesanan">Rp. {{ number_format($olahraga->harga_per_jam - (($olahraga->diskon != null ? $olahraga->diskon->discount : $olahraga->diskon) * $olahraga->harga_per_jam / 100), 0, '', '.') }}</td>
                </tr>
            {{-- @unless(Auth::user()->isPO())
                <tr>
                    <td colspan="2" class="px-0 py-0" style="color:#7e74ff">Identity Card's Image</td>
                </tr>
                <tr>
                    <td class="px-0 pb-0 text-center" colspan="2">
                        <input type="file" name="image" id="image" class="inputfile inputfile-2" accept="image/x-png, image/jpeg">
                        <label for="image" class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg> <span>Choose a file…</span></label>
                    </td>
                </tr>
            @endunless --}}
            </table>

                    
            <div class="submit-btn-area">
				<div class="col-md-12 p-0">
					<div class="row">
						<div class="col-md-6 p-1">
							<button id="form_submit" type="submit">Simpan <i class="ti-arrow-right"></i></button>
						</div>
						<div class="col-md-6 p-1">
							<a href="{{ route('olahraga.show', ['lapangan' => $lapangan, 'olahraga' => $olahraga]) }}"><button id="form_submit" type="button">Kembali <i class="ti-back-left"></i></button></a>
						</div>
					</div>
				</div>
			</div>
        @endslot
    @endform
@endsection

@section('script')
    <script type="text/javascript">
        $('#jam_selesai').change(function(){
            let jam_mulai = $('#jam_mulai');
            let lama_pemesanan = $('#lama_pemesanan');
            let biaya_pemesanan = $('#biaya_pemesanan');
            let harga_per_jam = {{ $olahraga->harga_per_jam - (($olahraga->diskon != null ? $olahraga->diskon->discount : $olahraga->diskon) * $olahraga->harga_per_jam / 100) }};
            // alert($olahraga->diskon->discount);

            lama_pemesanan.text($(this).val() - jam_mulai.val()+" Jam");
            let formatter = new Intl.NumberFormat('id-ID');
            biaya_pemesanan.text("Rp. "+formatter.format(harga_per_jam * ($(this).val() - jam_mulai.val())))
        });
    </script>
@endsection