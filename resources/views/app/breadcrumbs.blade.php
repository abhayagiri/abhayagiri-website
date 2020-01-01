{{-- $breadcrumbs is defined in ViewServiceProvider --}}
@php
    $breadcrumbs->addPageBreadcrumbs();
@endphp
<nav id="breadcrumbs" aria-label="breadcrumb">
    <div class="container">
        <ol class="breadcrumb">
            @foreach ($breadcrumbs as $i => $breadcrumb)
                <li class="breadcrumb-item{{ $i == 1 ? ' breadcrumb-navpage' : '' }}{{ $breadcrumb->last ? ' active" aria-current="page' : ''}}">
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
</nav>
