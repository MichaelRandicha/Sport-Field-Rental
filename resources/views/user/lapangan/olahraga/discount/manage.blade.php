@extends('layouts.app')

@section('title', ucwords($olahraga->name).' - Kelola Diskon')

@section('content')
    <div class="row">
        <div class="col-lg-10 offset-lg-1 mt-5">
            <div class="card card-bordered">
                <div class="card-body text-center">
                    @if($discounts->count() == 0)
                        <div class="my-3 text-center">
                            @alert(['type' => 'warning', 'class' => 'mb-0'])
                                <h4 class="alert-heading">Buat lapangan baru</h4>
                                <p>Lapangan Olahraga ini tidak memiliki riwayat diskon, tekan <a class="alert-link" href="{{ route('olahraga.discount.add', compact('lapangan', 'olahraga')) }}">Tambahkan Diskon</a> untuk menambahkan diskon lapangan.</p>
                            @endalert
                        </div>
                        <a href="{{ route('olahraga.discount.add', compact('lapangan', 'olahraga')) }}"><button type="button" class="btn btn-flat btn-success mb-3">Tambahkan Diskon</button></a>
                        <a href="{{ route('olahraga.show', compact('lapangan', 'olahraga')) }}"><button type="button" class="btn btn-flat btn-info mb-3">Kembali</button></a>
                    @else
                        <h4 class="header-title">Daftar Riwayat Diskon</h4>
                        
                        @if(session('TLE'))
                            @alert(['type' => 'success', 'dismissable' => 'true'])
                                {{ session('TLE') }}
                            @endalert
                        @endif  
                        @if(session('status'))
                            @alert(['type' => 'success', 'dismissable' => 'true'])
                                {{ session('status') }}
                            @endalert
                        @endif
                        
                        <a href="{{ route('olahraga.discount.add', compact('lapangan', 'olahraga')) }}"><button type="button" class="btn btn-flat btn-success mb-3">Tambahkan Diskon</button></a>
                        <a href="{{ route('olahraga.show', compact('lapangan', 'olahraga')) }}"><button type="button" class="btn btn-flat btn-info mb-3">Kembali</button></a>
                        <div class="table-responsive">
                            <table class="table text-center">
                                <thead class="text-uppercase bg-primary">
                                    <tr class="text-white">
                                        <th scope="col" class="align-middle">No</th>
                                        <th scope="col" class="align-middle">Diskon</th>
                                        <th scope="col" class="align-middle">Dari Jam</th>
                                        <th scope="col" class="align-middle">Sampai Jam</th>
                                        <th scope="col" class="align-middle">Dari Tanggal</th>
                                        <th scope="col" class="align-middle">Sampai Tanggal</th>
                                        <th scope="col" class="align-middle">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="v-middle">
                                    @foreach($discounts as $discount)
                                    <tr>
                                        <th scope="row">{{ $loop->index + $discounts->firstItem() }}</th>
                                        <td>{{ $discount->discount }}%</td>
                                        <td>{{ $discount->dari_jam }}</td>
                                        <td>{{ $discount->sampai_jam }}</td>
                                        <td>{{ Carbon\Carbon::parse($discount->dari_tanggal)->format('d F Y') }}</td>
                                        <td>{{ Carbon\Carbon::parse($discount->sampai_tanggal)->format('d F Y') }}</td>
                                        <td>
                                            <a class="text-white btn btn-flat btn-xs my-1 btn-primary" href="{{ route('olahraga.discount.edit', compact('lapangan', 'olahraga', 'discount')) }}">Ubah<i class="fa fa-location-arrow"></i></a>
                                            <a class="text-white btn btn-flat btn-xs my-1 btn-danger remove-record" data-toggle="modal" data-url="{{ route('olahraga.discount.destroy', compact('lapangan', 'olahraga', 'discount')) }}" data-target="#modal">Hapus<i class="fa fa-times"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $discounts->links('components.pagination-center') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <form action="" method="POST" class="remove-record-model">
        @csrf
        @method('delete')
        @modal(['id' => 'modal'])
            @slot('title', 'Delete Confirmation')

            @slot('body', 'Apakah anda yakin ingin menghapus diskon ini?')

            @slot('button')
                <button type="button" class="btn btn-secondary remove-data-from-delete-form" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Ya</button>
            @endslot
        @endmodal
    </form>
@endsection

@section('script')
    <script src="{{ asset('js/custom.js') }}"></script>
@endsection