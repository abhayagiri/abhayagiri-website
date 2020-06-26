@php
    $abridge = isset($abridge) && $abridge;
    $class = isset($class) ? $class : \Str::singular($article->getTable());
@endphp

<article class="{{ $class }} {{ $abridge ? 'abridge' : 'full' }} reading">

    @include('app.article-header')
    @include('app.article-date')

    @if ($abridge)

        <section class="image">
            <a href="{{ $article->path }}">
                @include('app.article-picture', ['preset' => 'thumb'])
            </a>
        </section>
        <section class="body">
            <p>{!! \App\Util::abridge($article->body_html, 400, false) !!}</p>
        </section>
        <nav class="links">
            <a class="btn btn-light" role="button" href="{{ $article->path }}">
                {{ __('common.read_more') }} ⇢
            </a>
        </nav>

    @else

        <section class="image">
            @include('app.article-picture')
        </section>
        <section class="body">
            {!! $article->body_html !!}
        </section>

    @endif

</article>
