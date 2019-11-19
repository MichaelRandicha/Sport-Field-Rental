@extends('layouts.app')

@section('title', ucwords($olahraga->name).' - Ubah Diskon')

@section('content')
    @form(['method' => 'POST', 'route' =>route('olahraga.discount.update', compact('lapangan', 'olahraga', 'discount'))])
        @slot('header', ucwords($olahraga->name).' - Ubah Diskon ')

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
            @method('put')
            
            <div class="form-gp focused">
                <label for="discount">Diskon (%)*</label>
                <input type="number" name="discount" max="100" min="0" value="{{ $discount->discount }}" required autofocus>
                <i class="ti-money"></i>
            </div>
            <div class="form-gp focused">
                <label for="location">Dari Tanggal*</label>
                <input type="date" name="dari_tanggal" min="{{ Carbon\Carbon::now()->toDateString() }}" value="{{ $discount->dari_tanggal }}" required>
            </div>
            <div class="form-gp focused">
                <label for="location">Sampai Tanggal*</label>
                <input type="date" name="sampai_tanggal" value="{{ $discount->sampai_tanggal }}" required>
            </div>
            <div class="form-gp focused">
                <label for="dari_jam">Dari Jam (Minimal : {{ $lapangan->jam_buka }}, Maksimal : {{ $lapangan->jam_tutup }})*</label>
                <input type="number" name="dari_jam" min="{{ $lapangan->jam_buka }}" max="{{ $lapangan->jam_tutup }}" value="{{ $discount->dari_jam }}" required>
                <i class="ti-alarm-clock"></i>
            </div>
            <div class="form-gp focused">
                <label for="sampai_jam">Sampai Jam (Minimal : {{ $lapangan->jam_buka }}, Maksimal : {{ $lapangan->jam_tutup }})*</label>
                <input type="number" name="sampai_jam" min="{{ $lapangan->jam_buka }}" max="{{ $lapangan->jam_tutup }}" value="{{ $discount->sampai_jam }}" required>
                <i class="ti-alarm-clock"></i>
            </div>
            <div class="submit-btn-area">
                <div class="col-md-12 p-0">
                    <div class="row">
                        <div class="col-md-6 p-1">
                            <button id="form_submit" type="submit">Simpan <i class="ti-arrow-right"></i></button>
                        </div>
                        <div class="col-md-6 p-1">
                            <a href="{{ route('olahraga.discount.manage', compact('lapangan', 'olahraga')) }}"><button id="form_submit" type="button">Kembali <i class="ti-back-left"></i></button></a>
                        </div>
                    </div>
                </div>
            </div>
        @endslot
    @endform
@endsection