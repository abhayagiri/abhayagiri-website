<article>
    <header>
        @if (isset($article->author))
            <img src="{{ $article->author->image_url }}">
        @endif
        <h1>{{ $article->title }}</h1>
        @if (isset($article->author))
            <h2>{{ $article->author->title }}</h2>
        @endif
    </header>
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
    <aside class="date">
        <a href="{{ lp($article->path) }}">🔗</a>
        {{ __('common.posted') }}: @date($article->posted_at)
        @if (isset($showUpdated) && $showUpdated && $article->wasUpdatedAfterPosting())
            <br>
            {{ __('common.last_updated') }}: @date($article->updated_at)
        @endif
    </aside>
</article>
