@include('app.article-picture', ['article' => $book, 'preset' => 'fit-icon'])
<h1 title="{{ $book->title }}" data-toggle="tooltip">
    @if ($link ?? false)
        <a href="{{ $book->path }}">
    @endif
    {{ $book->title }}
    @if ($link ?? false)
        </a>
    @endif
</h1>
<h2>
    {{ $book->author->title }}
    @if ($book->author2)
        &amp;
        {{ $book->author2->title }}
    @endif
</h2>
