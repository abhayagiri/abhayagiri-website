<header>
    <h1>{{ $article->title }}</h1>
    @if (isset($article->author))
        <h2>{{ $article->author->title }}</h2>
    @endif
</header>
