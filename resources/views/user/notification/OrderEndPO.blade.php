@php
    $time = new Carbon\Carbon();
    $time->hour = $notification->data['order']['jam_pesan_start'];
    $time->minute = 0;
    $time1 = new Carbon\Carbon();
    $time1->hour = $notification->data['order']['jam_pesan_end'];
    $time1->minute = 0;
@endphp
<a href="{{ route('olahraga.review.index', ['lapangan' => $notification->data['lapangan']['id'], 'olahraga' => $notification->data['olahraga']['id']]) }}" class="notify-item">
    <div class="notify-thumb"><i class="ti-basketball btn-danger"></i></div>
    <div class="notify-text">
        <p>Pemesananmu di Lapangan {{ $notification->data['lapangan']['name'] }} - {{ $notification->data['olahraga']['name'] }} pada {{ $time->format('H:i') }} - {{ $time1->format('H:i') }} {{ App\Lapangan::find($notification->data['lapangan']['id'])->zone }} telah selesai. Tolong tambahkan review {{ $notification->data['lapangan']['name'] }} - {{ $notification->data['olahraga']['name'] }} dengan menekan notifikasi ini</p>
        <span>{{ $notification->created_at->diffForHumans() }}</span>
    </div>
</a>