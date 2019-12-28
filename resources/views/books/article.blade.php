<article class="book">

    <header>
        @include('books.title')
    </header>

    <section class="body">
        {!! tp($book, 'description_html') !!}
    </section>

    <aside>
        <p>
            <a href="{{ $book->path }}">🔗</a>
            {{ __('books.published') }}:
            {{ $book->posted_at->forUser()->isoFormat('MMMM YYYY') }}
        </p>
        @if ($book->pdf_url || $book->epub_url || $book->mobi_url)
            <div class="buttons">
                <div class="btn-group" role="group">
                    @if ($book->pdf_url)
                        <a class="btn btn-light" href="{{ $book->pdf_url }}">
                            <i class="fa fa-download"></i> PDF
                        </a>
                    @endif
                    @if ($book->epub_url)
                        <a class="btn btn-light" href="{{ $book->epub_url }}">
                            <i class="fa fa-download"></i> ePUB
                        </a>
                    @endif
                    @if ($book->mobi_url)
                        <a class="btn btn-light" href="{{ $book->mobi_url }}">
                            <i class="fa fa-download"></i> Mobi
                        </a>
                    @endif
                </div>
            </div>
        @endif
        @if ($book->request)
            <div class="buttons">
                <form method="POST" action="{{ lp(route('books.cart.add', null, false)) }}">
                    @csrf
                    <input type="hidden" name="id" value="{{ $book->id }}">
                    <button type="submit" class="btn btn-light">
                        <i class="fa fa-book"></i> {{ __('books.request_print_copy') }}
                    </button>
                </form>
            </div>
        @endif
    </aside>

</article>
