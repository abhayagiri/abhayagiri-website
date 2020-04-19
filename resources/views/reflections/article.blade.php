<article class="reflection author">
    <header>
        <h1>{{ $article->title }}</h1>
        <h2>{{ $article->author->title }}</h2>
    </header>
    @if (isset($abridge) && ($abridge))
        @include('app.article-picture', ['article' => $article->author, 'preset' => 'icon', 'class' => 'abridge'])
        <section class="abridge">
            <p class="text">
                {!! \App\Util::abridge($article->body_html, 300) !!}
            </p>
            <div class="read-more">
                <a class="btn btn-light" role="button" href="{{ lp($article->path) }}">
                    {{ __('common.read_more') }} ⇢
                </a>
            </div>
        </section>
    @else
        <section class="body">
            @include('app.article-picture', ['article' => $article->author, 'preset' => 'icon'])
            {!! $article->body_html !!}
        </section>
    @endif
    @include('app.article-date')
</article>
