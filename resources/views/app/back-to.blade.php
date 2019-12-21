{{-- $page is defined in ViewServiceProvider --}}
<p>
    <a class="btn btn-light" role="button" href="{{ lp($page->path) }}">
        ⇠ {{ __('common.back_to', ['section' => $page->title])}}
    </a>
</p>
