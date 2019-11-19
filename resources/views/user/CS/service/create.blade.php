@extends('layouts.app')

@section('title', 'Tambah Lapangan Kepada Customer Service')

@section('content')
    @form(['method' => 'POST', 'route' =>route('service.store', ['CS' => $CS])])
        @slot('header', 'Tambah Lapangan Kepada Customer Service')

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
            <div class="form-group">
                <label style="color:#7e74ff">Lapangan</label>
                <select class="form-control form-control-lg" name="lapangan_id">
                	@foreach(Auth::user()->lapangan as $lapangan)
                        @if($CS->services()->where('lapangan_id', $lapangan->id)->count() == 0)
                            <option value="{{ $lapangan->id }}">{{ ucwords($lapangan->name) }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="submit-btn-area">
				<div class="col-md-12 p-0">
					<div class="row">
						<div class="col-md-6 p-1">
							<button id="form_submit" type="submit">Simpan <i class="ti-arrow-right"></i></button>
						</div>
						<div class="col-md-6 p-1">
							<a href="{{ route('CS.show', ['CS' => $CS]) }}"><button id="form_submit" type="button">Kembali <i class="ti-back-left"></i></button></a>
						</div>
					</div>
				</div>
			</div>
        @endslot
    @endform
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.14/moment-timezone-with-data-2012-2022.min.js"></script>
    <script>
        $(function () {
            $('#tz').val(moment.tz.guess())
        })
    </script>
@endsection