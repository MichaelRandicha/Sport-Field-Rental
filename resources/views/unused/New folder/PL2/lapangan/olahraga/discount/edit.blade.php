@extends('layouts.app')

@section('title', ucwords($olahraga->name).' - Manage Discount ')

@section('content')
    @form(['method' => 'POST', 'route' =>route('olahraga.discount.update', ['lapangan' => $lapangan, 'olahraga' => $olahraga])])
        @slot('header', ucwords($olahraga->name).' - Manage Discount ')

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
            
            <div class="form-gp @isset($olahraga->discount) focused @endif">
                <label for="discount">Discount (%)*</label>
                <input type="number" name="discount" max="100" min="0" value="{{ $olahraga->discount }}" required autofocus>
            </div>

            <div class="submit-btn-area">
                <div class="col-md-12 p-0">
                    <div class="row">
                        <div class="col-md-6 p-1">
                            <button id="form_submit" type="submit">Submit <i class="ti-arrow-right"></i></button>
                        </div>
                        <div class="col-md-6 p-1">
                            <a href="{{ route('olahraga.show', ['lapangan' => $lapangan, 'olahraga' => $olahraga]) }}"><button id="form_submit" type="button">Go Back <i class="ti-back-left"></i></button></a>
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