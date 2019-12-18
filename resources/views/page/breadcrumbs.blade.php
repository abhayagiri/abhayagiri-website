<div id="breadcrumb-container">
    <div class="container">
        <ol class="breadcrumb">
            @foreach ($breadcrumbs->all() as $i => $breadcrumb)
                <li class="breadcrumb-item{{ $i == 1 ? ' breadcrumb-navpage' : '' }}">
                    @if ($breadcrumb->link)
                        <a href="{{ $breadcrumb->path }}">
                    @endif
                    {{ $breadcrumb->title }}
                    @if ($breadcrumb->link)
                        </a>
                    @endif
                </li>
            @endforeach
        </ol>
    </div>
</div>
