@php
    $time = new Carbon\Carbon();
    $time->hour = $notification->data['order']['jam_pesan_start'];
    $time->minute = 0;
    $time1 = new Carbon\Carbon();
    $time1->hour = $notification->data['order']['jam_pesan_end'];
    $time1->minute = 0;
@endphp
<a href="{{ route('PO.pembayaran.show', ['order' => $notification->data['order']['id']]) }}" class="notify-item">
    <div class="notify-thumb"><i class="ti-user btn-danger"></i></div>
    <div class="notify-text">
        <p>Pembayaranmu untuk pemesanan di Lapangan {{ $notification->data['lapangan']['name'] }} - {{ $notification->data['olahraga']['name'] }} ({{ $time->format('H:i') }} - {{ $time1->format('H:i') }} {{ App\Lapangan::find($notification->data['lapangan']['id'])->zone }}) ditolak</p>
        <span>{{ $notification->created_at->diffForHumans() }}</span>
    </div>
</a>