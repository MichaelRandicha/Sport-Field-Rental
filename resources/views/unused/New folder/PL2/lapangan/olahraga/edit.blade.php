@extends('layouts.app')

@section('title', 'Edit Lapangan')

@section('content')
    @form(['method' => 'POST', 'route' =>route('olahraga.update', ['lapangan' => $lapangan, 'olahraga' => $olahraga]), 'fileupload' => 'yes'])
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
            <div class="form-gp @isset($olahraga->name) focused @endif">
                <label for="name">Name*</label>
                <input type="text" name="name" value="{{ $olahraga->name }}" required autofocus>
                <i class="ti-user"></i>
            </div>
            <label for="basic-url" style="color:#7e74ff">Fasilitas*</label>
            <div class="input-group mb-3">
                <textarea class="form-control" aria-label="With textarea" name="fasilitas" rows="4">{{ $olahraga->fasilitas }}</textarea>
            </div>
            <label for="basic-url" style="color:#7e74ff">Harga*</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">Rp.</span>
                </div>
                <input type="number" id="harga" name="harga" class="form-control form-control-sm" min="0" placeholder="Harga per jam" aria-label="harga" aria-describedby="basic-addon1" value="{{ $olahraga->harga_per_jam }}">
                <div class="input-group-append" required>
                    <span class="input-group-text" id="basic-addon1">/ Jam</span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-form-label" style="color:#7e74ff">Jenis Olahraga*</label>
                <select class="form-control form-control-lg" name="jenis_olahraga" required>
                    <option @if($olahraga->jenis_olahraga == 'Basket') selected @endif>Basket</option>
                    <option @if($olahraga->jenis_olahraga == 'Bulu Tangkis') selected @endif>Bulu Tangkis</option>
                    <option @if($olahraga->jenis_olahraga == 'Futsal') selected @endif>Futsal</option>
                    <option @if($olahraga->jenis_olahraga == 'Sepak Bola') selected @endif>Sepak Bola</option>
                    <option @if($olahraga->jenis_olahraga == 'Tenis Meja') selected @endif>Tenis Meja</option>
                    <option @if($olahraga->jenis_olahraga == 'Tenis') selected @endif>Tenis</option>
                    <option @if($olahraga->jenis_olahraga == 'Voli') selected @endif>Voli</option>
                </select>
            </div>
            <div class="table-responsive">
                <table class="table borderless">
                    <tr>
                        <td class="px-0 py-0" style="color:#7e74ff">Change Lapangan Olahraga's Picture</td>
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
                            <a href="{{ route('lapangan.show', ['lapangan' => $lapangan]) }}"><button id="form_submit" type="button">Go Back <i class="ti-back-left"></i></button></a>
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