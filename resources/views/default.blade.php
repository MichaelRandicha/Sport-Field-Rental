@extends('layouts.app')

@section('title', 'Selamat datang di '.env('APP_NAME'))

@section('full-content')
    <header class="bg-green text-center text-white fp">
        <div class="container">
            <img class="img-fluid mb-5 d-block mx-auto" src="{{ asset('assets/images/icon/sports.png') }}" style="width:256px;height:256px" alt="">
            <h1>{{ env('APP_NAME') }}</h2>
            <hr class="star-light">
            <h1>Memesan Lapangan Olahraga Menjadi Lebih Mudah</h1>
        </div>
    </header>
    <section class="bg-primary text-white fp">
        <div class="container">
            <h1 class="text-center text-uppercase">Mengenai Kami</h1>
            <hr class="star-light">
            <div class="row">
                <div class="col-lg-4 ml-auto mb">
                    <p class="lead text-white font-weight-normal mb-1">
                        CariLapangan adalah sebuah platform yang mempermudah pemilik lapangan untuk mempromosikan, dan mengatur lapangan olahraga yang dimilikinya, serta mempermudah pemesanan lapangan bagi Penggemar Olahraga.
                        {{-- Freelancer is a free bootstrap theme created by Start Bootstrap. The download includes the complete source files including HTML, CSS, and JavaScript as well as optional LESS stylesheets for easy customization. --}}
                    </p>
                </div>
                <div class="col-lg-4 mr-auto">
                    <p class="lead text-white font-weight-normal mb-1">
                        CariLapangan dibentuk untuk menghubungkan pemilik lapangan dengan penggemar olahraga dengan mempermudah pemesanan dan pengelolaan pemesanan lapangan.
                        {{-- Whether you're a student looking to showcase your work, a professional looking to attract clients, or a graphic artist looking to share your projects, this template is the perfect starting point! --}}
                    </p>
                </div>
              </div>
            </div>
    </section>
    {{-- <div class="row">
    	<div class="col-12">
    		<div class="card text-center">
    			<div class="card-body" class="">asd</div>
    		</div>
    		<div class="card text-center">
    			<div class="card-body">asd</div>
    		</div>
    		<div class="card text-center">
    			<div class="card-body">asd</div>
    		</div>
    	</div>
    </div> --}}
@endsection