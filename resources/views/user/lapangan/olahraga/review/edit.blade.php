@extends('layouts.app')

@section('title', 'Edit Ulasan')

@section('content')
    <div class="card-area">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="card card-bordered">
                    <div class="card-horizontal">
                        <a href="{{ $olahraga->img }}" data-fancybox="gallery" data-caption="{{$lapangan->name}}<br>{{ $olahraga->name }}">
                            <img class="card-img-top img-fluid" id="field-top-img" src="{{ $olahraga->img_resized }}" alt="image">
                            <span class="bg-success text-white text-center title-lapangan" title="Average Rating: {{ $olahraga->realRating }}">
                                {{ $lapangan->name }}
                                <br>
                                {{ $olahraga->name }}
                                @if($olahraga->diskon != null) 
                                <br>
                                <span class="badge badge-dark">
                                    Diskon {{ $olahraga->diskon->discount }}% Sampai 
                                    @php
                                        $diskon = Carbon\Carbon::parse($olahraga->diskon->sampai_tanggal);
                                        $diskon->hour = $olahraga->diskon->sampai_jam;
                                        $start = Carbon\Carbon::parse($olahraga->diskon->dari_tanggal);
                                        $start->hour = $olahraga->diskon->dari_jam;
                                    @endphp
                                        {{ Carbon\Carbon::parse($olahraga->diskon->sampai_tanggal)->format('d F') }} 
                                    <br>Jam {{ $start->format('H:i') }} - {{ $diskon->format('H:i') }}
                                </span> 
                                @endif
                                <br>
                                @for($i = 0; $i < $olahraga->realRating; $i++)
                                    @if($olahraga->realRating - $i > 0.5)
                                        <i class="fa fa-star"></i>
                                    @else
                                        <i class="fa fa-star-half-o"></i>
                                    @endif
                                @endfor
                                @for($i = 0; $i < 5 - ceil($olahraga->realRating);$i++)
                                    <i class="fa fa-star-o"></i>
                                @endfor
                                <span style="font-weight: normal;">({{ $olahraga->reviewCount }})</span>
                            </span>
                        </a>
                        <div class="card-body pt-2 {{-- px-2 py-2 --}}">
                            <div class="olahraga-type">
                                <img src="{{ asset('storage/images/icon/'.$olahraga->jenis_olahraga.'.png') }}" title="{{ $olahraga->jenis_olahraga }}">
                            </div>
                            <div class="table-responsive">
                                <table class="table borderless table-sm">
                                    <tr>
                                        <td class="lapangan-td">Harga</td>
                                        <td>@if($olahraga->harga_per_jam == 0) Free @else {{ $olahraga->harga }} @endif</td>
                                    </tr>
                                    <tr>
                                        <td class="lapangan-td">Fasilitas</td>
                                        <td>{{ $olahraga->fasilitas }}</td>
                                    </tr>
                                    <tr>
                                        <td class="field-top-td">Hari Buka</td>
                                        <td>{{ $lapangan->hariBuka }}</td>
                                    </tr>
                                    <tr>
                                        <td class="field-top-td">Jam Buka</td>
                                        <td>
                                            @if($lapangan->jam_buka == 0 && $lapangan->jam_tutup == 24)
                                                24 Jam
                                            @else
                                                {{ $lapangan->buka }} - {{ $lapangan->tutup }} {{ $lapangan->zone }}
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <a href="{{ route('olahraga.show', ['lapangan' => $lapangan, 'olahraga' => $olahraga]) }}" class="btn btn-outline-info btn-sm btn-flat my-1">Pemesanan</a>
                            <a href="{{ route('pembayaran.index', ['lapangan' => $lapangan, 'olahraga' => $olahraga]) }}" class="btn btn-outline-info btn-sm btn-flat my-1">Daftar Pembayaran</a>
                            <a href="{{ route('olahraga.review.index', ['lapangan' => $lapangan, 'olahraga' => $olahraga]) }}" class="btn btn-outline-info btn-sm btn-flat my-1 active">Ulasan</a>
                            <a href="{{ route('lapangan.show', ['lapangan' => $lapangan]) }}" class="btn btn-info btn-sm btn-flat my-1">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            @if(Auth::user()->isPO())
                <div class="col-lg-8 offset-lg-2 mt-5">
                    <div class="card card-bordered">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body">
                                    <form method="POST" action="{{ route('olahraga.review.update', compact('lapangan', 'olahraga', 'review')) }}">
                                        @csrf
                                        @method('PUT')
                                        <span class="rating">
                                            <input type="radio" class="rating-input" id="star-5-0" name="rating" value="5" @if($review->rating == 5) checked @endif>
                                            <label for="star-5-0" class="fa rating-star text-warning mb-0"></label>
                                            <input type="radio" class="rating-input" id="star-4-0" name="rating" value="4" @if($review->rating == 4) checked @endif>
                                            <label for="star-4-0" class="fa rating-star text-warning mb-0"></label>
                                            <input type="radio" class="rating-input" id="star-3-0" name="rating" value="3" @if($review->rating == 3) checked @endif>
                                            <label for="star-3-0" class="fa rating-star text-warning mb-0"></label>
                                            <input type="radio" class="rating-input" id="star-2-0" name="rating" value="2" @if($review->rating == 2) checked @endif>
                                            <label for="star-2-0" class="fa rating-star text-warning mb-0"></label>
                                            <input type="radio" class="rating-input" id="star-1-0" name="rating" value="1" @if($review->rating == 1) checked @endif>
                                            <label for="star-1-0" class="fa rating-star text-warning mb-0"></label>
                                        </span>
                                        <h5>Oleh <span class="text-primary">{{ $review->user->name }}</span> - <span class="text-secondary">{{ $review->created_at->format('l, j F Y') }}</span></h5>
                                        <div class="form-group mt-1 mb-2">
                                            <textarea class="form-control" id="review-0" name="review" rows="2" placeholder="Masukkan ulasan mengenai lapangan olahraga ini">{{ $review->review }}</textarea>
                                        </div>
                                        <button type="submit" class="btn btn-warning btn-sm">Ubah Ulasan</button>
                                        <a href="{{ route('olahraga.review.index', compact('lapangan', 'olahraga')) }}" class="btn btn-primary btn-sm">Kembali</a>
                                    </form>
                                </div>
                            </div>
                            <hr>
                            @if($review->tanggapan)
                            <div class="media child-media">
                                <div class="media-body">
                                    <h5>Oleh <span class="text-primary">{{ strtoupper($lapangan->name) }}</span> <span class="badge badge-primary">CS</span>
                                    </h5>
                                    {{ $review->tanggapan }}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="col-lg-8 offset-lg-2 mt-5">
                        <div class="card card-bordered">
                            <div class="card-body">
                                <div class="media">
                                    <div class="media-body">
                                        <span title="Rating: {{ $review->rating }}">
                                            @for($i = 0; $i < $review->rating; $i++)
                                                @if($review->rating - $i > 0.5)
                                                    <i class="fa fa-star text-warning"></i>
                                                @else
                                                    <i class="fa fa-star-half-o text-warning"></i>
                                                @endif
                                            @endfor
                                            @for($i = 0; $i < 5 - ceil($review->rating);$i++)
                                                <i class="fa fa-star-o text-warning"></i>
                                            @endfor
                                        </span>
                                        <h5>Oleh <span class="text-primary">{{ $review->user->name }}</span> @if($review->user->isCS())<span class="badge badge-primary">CS</span>@endif - <span class="text-secondary">{{ $review->created_at->format('l, j F Y') }}</span>
                                        </h5>{{ $review->review }}
                                    </div>
                                </div>
                                <hr>
                                <div class="media child-media">
                                    <div class="media-body">
                                        <form method="POST" action="{{ route('olahraga.review.update', ['lapangan' => $lapangan, 'olahraga' => $olahraga, 'review' => $review]) }}">
                                            @csrf
                                            @method('PUT')
                                            <h5>Oleh <span class="text-primary">{{ $lapangan->name }}</span> <span class="badge badge-primary">CS</span></h5>
                                            <div class="form-group mt-1 mb-2">
                                                <textarea class="form-control" id="review-1" name="review" rows="2" placeholder="Berikan tanggapan terhadap ulasan">{{ $review->tanggapan }}</textarea>
                                            </div>
                                            <button type="submit" class="btn btn-warning btn-sm">Ubah Tanggapan</button>
                                            <a href="{{ route('olahraga.review.index', compact('lapangan', 'olahraga')) }}" class="btn btn-primary btn-sm">Kembali</a>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            @endif
        </div>
    </div>
@endsection

@section('script')
    
@endsection