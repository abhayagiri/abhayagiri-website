@php
    $abridge = isset($abridge) ? $abridge : false
@endphp

<article class="book {{ $abridge ? 'abridge' : 'full reading' }}">

    @if ($abridge)
        <section class="floating-image">
            <a href="{{ $book->path }}">
                @include('app.article-picture', ['article' => $book, 'preset' => 'icon'])
            </a>
        </section>
    @endif

    <header>
        @include('books.title')
    </header>

    <aside class="date">
        <div class="posted-at">
            <a href="{{ $book->path }}"><i class="la la-link"></i></a>
            {{ __('books.published') }}:
            {{ $book->posted_at->forUser()->isoFormat('MMMM YYYY') }}
        </div>
    </aside>

    @if ($abridge)
        <section class="image">
            <a href="{{ $book->path }}">
                @include('app.article-picture', ['article' => $book, 'preset' => 'icon'])
            </a>
        </section>
        <section class="body">
            <p>{!! \App\Util::abridge(tp($book, 'description_html'), 400, false) !!}</p>
        </section>
    @else
        <section class="image">
            @include('app.article-picture', ['article' => $book])
        </section>
        <section class="body">
            {!! tp($book, 'description_html') !!}
        </section>
    @endif

    <nav class="links">

        @if ($book->pdf_url || $book->epub_url || $book->mobi_url)
            <div class="btn-group" role="group">
                @if ($book->pdf_url)
                    <a class="btn btn-light" href="{{ $book->pdf_url }}">
                        <i class="la la-download"></i> PDF
                    </a>
                @endif
                @if ($book->epub_url)
                    <a class="btn btn-light" href="{{ $book->epub_url }}">
                        <i class="la la-download"></i> ePUB
                    </a>
                @endif
                @if ($book->mobi_url)
                    <a class="btn btn-light" href="{{ $book->mobi_url }}">
                        <i class="la la-download"></i> Mobi
                    </a>
                @endif
            </div>
        @endif

        @if ($book->request)
            <form method="POST" action="{{ lp(route('books.cart.add', null, false)) }}">
                @csrf
                <input type="hidden" name="id" value="{{ $book->id }}">
                <button type="submit" class="btn btn-light" data-cy="request-book">
                    {{ __('books.request_print_copy') }}
                </button>
            </form>
        @endif

        @if ($abridge)
            <a class="btn btn-light" href="{{ $book->path }}">
                {{ __('common.read_more') }}
            </a>
        @endif

    </nav>

</article>
