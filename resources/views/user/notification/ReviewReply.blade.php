<a href="{{ route('olahraga.review.index', ['lapangan' => $notification->data['lapangan']['id'], 'olahraga' => $notification->data['olahraga']['id']]) }}" class="notify-item">
	<div class="notify-thumb"><i class="ti-user btn-info"></i></div>
	<div class="notify-text">
		<p>Customer Service dari Lapangan {{ $notification->data['lapangan']['name'] }} - {{ $notification->data['olahraga']['name'] }} Menanggapi Ulasanmu</p>
		<span>{{ $notification->created_at->diffForHumans() }}</span>
	</div>
</a>