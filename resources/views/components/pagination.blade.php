@if ($paginator->hasPages())
	<ul class="pagination pg-color-border">
	    <!-- Previous Page Link -->
	    @if ($paginator->onFirstPage())
			<li class="page-item disabled"><a class="page-link" tabindex="-1">Previous</a></li>
		@else
			<li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" tabindex="-1">Previous</a></li>
		@endif

		@foreach ($elements as $element)
	        <!-- "Three Dots" Separator -->
	        @if (is_string($element))
				<li class="page-item disabled">{{ $element }}</li>
	        @endif

	        <!-- Array Of Links -->
	        @if (is_array($element))
	            @foreach ($element as $page => $url)
	                @if ($page == $paginator->currentPage())
		                <li class="page-item active"><a class="page-link" href="#">{{ $page }} <span class="sr-only">(current)</span></a></li>
	                @else
	                	<li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
	                @endif
	            @endforeach
	        @endif
	    @endforeach

		<!-- Next Page Link -->
	    @if ($paginator->hasMorePages())
	        <li class="page-item">
				<a class="page-link" href="{{ $paginator->nextPageUrl() }}">Next</a>
			</li>
	    @else
	    	<li class="page-item disabled"><a class="page-link">Next</a></li>
	    @endif
	</ul>
@endif