@extends('layouts.app')

@section('title', 'Edit Lapangan')

@section('content')
    @form(['method' => 'POST', 'route' =>route('lapangan.update', ['lapangan' => $lapangan]), 'fileupload' => 'yes'])
        @slot('header', 'Edit Lapangan')

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
            @method('PUT')
            <div class="form-gp @isset($lapangan->name) focused @endisset">
                <label for="name">Lapangan Name*</label>
                <input type="text" name="name" value="{{ $lapangan->name }}" required autofocus>
                <i class="ti-user"></i>
            </div>
            <div class="form-gp @isset($lapangan->location) focused @endisset">
                <label for="location">Location*</label>
                <input type="text" name="location" value="{{ $lapangan->location }}" required>
                <i class="ti-location-pin"></i>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-gp @isset($lapangan->jam_buka)) focused @endisset">
                        <label for="jam_buka">Jam Buka (0-24)*</label>
                        <input type="number" name="jam_buka" min="0" max="24"value="{{ $lapangan->jam_buka }}" required>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-gp @isset($lapangan->jam_tutup) focused @endisset">
                        <label for="jam_tutup">Jam Tutup (0-24)*</label>
                        <input type="number" name="jam_tutup" min="0" max="24" value="{{ $lapangan->jam_tutup }}" required>
                    </div>
                </div>
            </div>
            <div class="form-gp @isset($lapangan->no_telepon) focused @endisset">
                <label for="no_telepon">Nomor Telepon</label>
                <input type="text" pattern="^(^\+62\s?|^0)(\d{3,4}-?){2}\d{3,4}$" name="no_telepon" value="{{ $lapangan->no_telepon ?? '' }}">
                <i class="fa fa-phone"></i>
            </div>
            <div class="table-responsive">
                <table class="table borderless">
                    <tr>
                        <td class="pt-0 px-0" rowspan="4"><label class="col-form-label" style="color:#7e74ff">Hari Buka</label></td>
                        <td class="py-0 px-0">
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" @if($lapangan->hari(1)) checked @endif class="custom-control-input" id="senin" name="hari_buka[]" value="1">
                                <label class="custom-control-label" for="senin"> Senin </label>
                            </div>
                        </td>
                        <td class="py-0 px-0">
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" @if($lapangan->hari(5)) checked @endif class="custom-control-input" id="jumat" name="hari_buka[]" value="5">
                                <label class="custom-control-label" for="jumat">Jumat</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-0 px-0">
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" @if($lapangan->hari(2)) checked @endif class="custom-control-input" id="selasa" name="hari_buka[]" value="2">
                                <label class="custom-control-label" for="selasa">Selasa</label>
                            </div>
                        </td>
                        <td class="py-0 px-0">
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" @if($lapangan->hari(6)) checked @endif class="custom-control-input" id="sabtu" name="hari_buka[]" value="6">
                                <label class="custom-control-label" for="sabtu">Sabtu</label>
                            </div>  
                        </td>
                    </tr>
                    <tr>
                        <td class="py-0 px-0">
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" @if($lapangan->hari(3)) checked @endif class="custom-control-input" id="rabu" name="hari_buka[]" value="3">
                                <label class="custom-control-label" for="rabu">Rabu</label>
                            </div>
                        </td>
                        <td class="py-0 px-0">
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" @if($lapangan->hari(7)) checked @endif class="custom-control-input" id="minggu" name="hari_buka[]" value="7">
                                <label class="custom-control-label" for="minggu">Minggu</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-0 px-0">
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" @if($lapangan->hari(4)) checked @endif class="custom-control-input" id="kamis" name="hari_buka[]" value="4">
                                <label class="custom-control-label" for="kamis">Kamis</label>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="table-responsive">
                <table class="table borderless">
                    <tr>
                        <td class="px-0 py-0" style="color:#7e74ff">Change Lapangan's Picture</td>
                    </tr>
                    <tr>
                        <td class="px-0 pb-0 text-center">
                            <input type="file" name="image" id="image" class="inputfile inputfile-2" accept="image/x-png, image/jpeg">
                            <label for="image" class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg> <span>Choose a file…</span></label>
                        </td>
                    </tr>
                </table>
            </div>
            {{-- <div class="input-group mb-3">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="inputGroupFile01">
                    <label class="custom-file-label" for="inputGroupFile01">Upload Lapangan's Picture</label>
                </div>
            </div> --}}
            <div class="submit-btn-area">
                <div class="col-md-12 p-0">
                    <div class="row">
                        <div class="col-md-6 p-1">
                            <button id="form_submit" type="submit">Submit <i class="ti-arrow-right"></i></button>
                        </div>
                        <div class="col-md-6 p-1">
                            <a href="{{ route('lapangan.index') }}"><button id="form_submit" type="button">Go Back <i class="ti-back-left"></i></button></a>
                        </div>
                    </div>
                </div>
            </div>
        @endslot
    @endform
@endsection

@section('script')
    <script src="{{ asset('js/custom-file-input.js') }}"></script>
@endsection