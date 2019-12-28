<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\View\View;

class BookController extends Controller
{
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
        $books = Book::public()->postOrdered()->paginate(10);
        $bookCartkey = Config::get('abhayagiri.book_cart.session_key');
        $hasBooksInCart = !!$request->session()->get($bookCartkey, []);
        return view('books.index', [
            'books' => $books,
            'hasBooksInCart' => $hasBooksInCart,
        ]);
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
        return view('books.show', ['book' => $book]);
    }
}
