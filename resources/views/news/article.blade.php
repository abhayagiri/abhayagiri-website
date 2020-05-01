<article>
    @include('app.article-header')
    <section class="body">
        @if ($article->image_path)
            @include('app.article-picture')
        @endif
        {!! $article->body_html !!}
    </section>
    @include('app.article-date')
</article>
