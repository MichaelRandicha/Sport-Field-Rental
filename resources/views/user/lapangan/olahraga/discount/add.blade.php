@extends('layouts.app')

@section('title', ucwords($olahraga->name).' - Tambah Diskon')

@section('content')
    @form(['method' => 'POST', 'route' =>route('olahraga.discount.store', ['lapangan' => $lapangan, 'olahraga' => $olahraga])])
        @slot('header', ucwords($olahraga->name).' - Tambah Diskon ')

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
            
            <div class="form-gp @if(old('discount')) focused @endif">
                <label for="discount">Diskon (%)*</label>
                <input type="number" name="discount" max="100" min="0" value="{{ old('discount') }}" required autofocus>
                <i class="ti-money"></i>
            </div>
            <div class="form-gp focused">
                <label for="location">Dari Tanggal*</label>
                <input type="date" name="dari_tanggal" min="{{ Carbon\Carbon::now()->toDateString() }}" value="{{ old('dari_tanggal') ?? Carbon\Carbon::now()->toDateString() }}" required>
            </div>
            <div class="form-gp focused">
                <label for="location">Sampai Tanggal*</label>
                <input type="date" name="sampai_tanggal" min="{{ Carbon\Carbon::now()->toDateString() }}" value="{{ old('sampai_tanggal') ?? Carbon\Carbon::now()->toDateString() }}" required>
            </div>
            <div class="form-gp @if(old('dari_jam')) focused @endif">
                <label for="dari_jam">Dari Jam (Minimal : {{ $lapangan->jam_buka }}, Maksimal : {{ $lapangan->jam_tutup }})*</label>
                <input type="number" name="dari_jam" min="{{ $lapangan->jam_buka }}" max="{{ $lapangan->jam_tutup }}" value="{{ old('dari_jam') }}" required>
                <i class="ti-alarm-clock"></i>
            </div>
            <div class="form-gp @if(old('sampai_jam')) focused @endif">
                <label for="sampai_jam">Sampai Jam (Minimal : {{ $lapangan->jam_buka }}, Maksimal : {{ $lapangan->jam_tutup }})*</label>
                <input type="number" name="sampai_jam" min="{{ $lapangan->jam_buka }}" max="{{ $lapangan->jam_tutup }}" value="{{ old('sampai_jam') }}" required>
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