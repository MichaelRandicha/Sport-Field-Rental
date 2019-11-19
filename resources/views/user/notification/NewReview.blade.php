<a href="{{ route('olahraga.review.index', ['lapangan' => $notification->data['lapangan']['id'], 'olahraga' => $notification->data['olahraga']['id']]) }}" class="notify-item">
	<div class="notify-thumb"><i class="ti-user btn-primary"></i></div>
	<div class="notify-text">
		<p>Ulasan baru di Lapangan {{ $notification->data['lapangan']['name'] }} - {{ $notification->data['olahraga']['name'] }}</p>
		<span>{{ $notification->created_at->diffForHumans() }}</span>
	</div>
</a>