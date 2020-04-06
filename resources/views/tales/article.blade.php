<article class="tale">
    <header>
        <h1>{{ $article->title }}</h1>
        <h2>{{ $article->author->title }}</h2>
    </header>
    <section class="body">
        @if (isset($abridge) && ($abridge))
            <img class="abridge-image" src="{{ $article->image_url }}" alt="{{ $article->title }}">
            @include('app.abridge', [
                'html' => $article->body_html,
                'length' => 240,
                'path' => lp($article->path),
                'lines' => 2,
            ])
        @else
            <p>
                <img src="{{ $article->image_url }}" alt="{{ $article->title }}">
            </p>
            {!! $article->body_html !!}
        @endif
    </section>
    @include('app.article-date')
</article>
