<div class="login-area">
    <div class="login-box ptb--50">
        <form method="{{ $method }}" action="{{ $route }}" @isset($fileupload) enctype="multipart/form-data" @endisset>
            @if(isset($method) && $method == "POST") @csrf @endif
            <div class="login-form-head">
                <h4>{{ $header }}</h4>
                @isset($header_p)<p>{{ $header_p }}</p>@endisset
            </div>

            <div class="login-form-body">
                {{ $body }}
            </div>
        </form>
    </div>
</div>