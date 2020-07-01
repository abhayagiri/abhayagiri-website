{{-- $page is defined in ViewServiceProvider --}}
<nav class="article-links">

    <a class="article-after btn btn-light {{ $associated->after ? '' : 'disabled'}}"
       role="button" href="{{ $associated->after->path ?? '#' }}">
        ⇠ {{ __('common.following') }}
    </a>

    <a class="back-to-articles btn btn-light" role="button"
       href="{{ lp($page->path) }}?page={{ $associated->page }}">
        {{ __('common.back_to', ['section' => $page->title])}}
    </a>

    <a class="article-before btn btn-light {{ $associated->before ? '' : 'disabled'}}"
       role="button" href="{{ $associated->before->path ?? '#' }}">
        {{ __('common.previous') }} ⇢
    </a>

</nav>
