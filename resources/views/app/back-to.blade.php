{{-- $page is defined in ViewServiceProvider --}}
<p>
    <a href="{{ lp($page->path) }}">
        {{ __('common.back_to', ['section' => $page->title])}}
    </a>
</p>
