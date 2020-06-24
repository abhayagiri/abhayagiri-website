{{-- $page is defined in ViewServiceProvider --}}
<nav class="article-links">

    <a class="article-after btn btn-light {{ $articleAfter ? '' : 'btn-disabled'}}" role="button" href="{{ $articleAfter->path ?? '#' }}">
        ⇠ {{ __('common.after') }}
    </a>

    <a class="back-to-articles btn btn-light" role="button" href="{{ lp($page->path) }}">
        {{ __('common.back_to', ['section' => $page->title])}}
    </a>

    @if ($articleBefore)
        <a class="article-before btn btn-light" role="button" href="{{ $articleBefore->path }}">
            {{ __('common.before') }} ⇢
        </a>
    @endif

</nav>
