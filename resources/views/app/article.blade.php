<article>
    <header>
        <h1>{{ $article->title }}</h1>
    </header>
    <section class="body">
        {!! $article->body_html !!}
    </section>
    <aside class="date">
        <a href="{{ lp($article->getPath()) }}">🔗</a>
        {{ __('common.posted') }}: @date($article->posted_at)
        @if ($article->wasUpdatedAfterPosting())
            <br>
            {{ __('common.last_updated') }}: @date($article->updated_at)
        @endif
    </aside>
</article>
