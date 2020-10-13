<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class BookController extends Controller
{
    /**
     * Return an image response for the book.
     *
     * @param  \App\Models\Book  $book
     * @param  string  $preset
     * @param  string  $format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function image(Book $book, string $preset, string $format): Response
    {
        $this->authorize('view', $book);
        return app('imageCache')->getModelImageResponse($book, $preset, $format);
    }

    /**
     * Display a listing of books.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\View
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Book::class);
        $bookCartkey = Config::get('abhayagiri.book_cart.session_key');
        $hasBooksInCart = !!$request->session()->get($bookCartkey, []);

        $filters = [
            'author_id' => $request->input('author'),
            'language_id' => $request->input('language'),
            'request' => $request->input('request'),
        ];

        $books = Book::public()
            ->commonOrder()
            ->filtered($filters)
            ->paginate(10);

        return view('books.index')
            ->withBooks($books)
            ->withHasBooksInCart($hasBooksInCart)
            ->withLanguages(Language::orderedByBooksCount()->get())
            ->withAuthors(Author::orderedByBooksCount()->get());
    }

    /**
     * Display the specified book.
     *
     * @param \App\Models\Book $book
     *
     * @return \Illuminate\View\View
     */
    public function show(Book $book): View
    {
        $this->authorize('view', $book);
        return view('books.show')
            ->withBook($book)
            ->withAssociated($book->getAssociated(10));
    }
}
