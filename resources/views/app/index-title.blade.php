@section('title')
    {{ $page->title }}
    @if ($items->currentPage() > 1)
        | {{ __('common.page_n', ['page' => $items->currentPage()]) }}
    @endif
@endsection
