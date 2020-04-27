<header>
    @if (isset($article->author))
        <img src="{{ $article->author->image_url }}">
    @endif
    <h1>{{ $article->title }}</h1>
    @if (isset($article->author))
        <h2>{{ $article->author->title }}</h2>
    @endif
</header>
