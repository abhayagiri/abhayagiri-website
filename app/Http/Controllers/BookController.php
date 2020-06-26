<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
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
        return view('books.index')
            ->withBooks(Book::public()->commonOrder()->paginate(10))
            ->withHasBooksInCart($hasBooksInCart);
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
