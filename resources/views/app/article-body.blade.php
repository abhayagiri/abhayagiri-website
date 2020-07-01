<section class="body">
    @if (isset($abridge) && ($abridge))
        @include('app.abridge', [
            'html' => $article->body_html,
            'length' => 240,
            'path' => lp($article->path),
        ])
    @else
        {!! $article->body_html !!}
    @endif
</section>
