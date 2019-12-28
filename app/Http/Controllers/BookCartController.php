<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookCartRequest;
use App\Mail\BookCartAdminMailer;
use App\Mail\BookCartUserMailer;
use App\Models\Book;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use Illuminate\View\View;

class BookCartController extends Controller
{
    /**
     * Add a book to the cart.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add(): RedirectResponse
    {
        $book = $this->getAndAuthorizeBook();
        $cart = $this->getSessionCart();
        if ($book->request) {
            $quantity = (int) array_get($cart, $book->id, 0);
            $cart[$book->id] = $quantity + 1;
        } else {
            unset($cart[$book->id]);
        }
        $this->setSessionCart($cart);
        return redirect($this->getShowCartPath());
    }

    /**
     * Remove a book from the cart.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(): RedirectResponse
    {
        $book = $this->getAndAuthorizeBook();
        $cart = $this->getSessionCart();
        unset($cart[$book->id]);
        $this->setSessionCart($cart);
        return redirect($this->getShowCartPath());
    }

    /**
     * Edit the book cart request.
     *
     * @return \Illuminate\View\View
     */
    public function editRequest(): View
    {
        // dd(Request::session());
        $this->authorize('viewAny', Book::class);
        return view('books.cart.request', [
            'cart' => $this->getCart(),
            'informationHtml' => $this->getInformationHtml(),
        ]);
    }

    /**
     * Send a book cart request via sending an email.
     *
     * @param  \App\Http\Requests\BookCartRequest  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendRequest(BookCartRequest $request): RedirectResponse
    {
        $cart = $this->getCart();
        $shipping = (object) $request->validated();

        $adminMailer = new BookCartAdminMailer($cart, $shipping);
        // Always send emails to admins as English
        Mail::to([$adminMailer->getTo()])->locale('en')->send($adminMailer);

        $userMailer = new BookCartUserMailer($cart, $shipping);
        Mail::to($userMailer->getTo())->send($userMailer);

        $request->session()->flash('message', __('books.send_request_success', [
            'email' => $userMailer->getTo()->email,
        ]));

        $request->session()->remove('books');

        return redirect($this->getBooksIndexPath());
    }

    /**
     * Show the book cart.
     *
     * @return \Illuminate\View\View
     */
    public function show(): View
    {
        $this->authorize('viewAny', Book::class);
        return view('books.cart.show', [
            'cart' => $this->getCart(),
            'informationHtml' => $this->getInformationHtml(),
        ]);
    }

    /**
     * Update the quantity of a book in the cart.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(): RedirectResponse
    {
        $book = $this->getAndAuthorizeBook();
        $cart = $this->getSessionCart();
        $quantity = (int) Request::input('quantity', 0);
        if (!$book->request || $quantity <= 0) {
            unset($cart[$book->id]);
        } else {
            $cart[$book->id] = $quantity;
        }
        $this->setSessionCart($cart);
        return redirect($this->getShowCartPath());
    }

    /**
     * Return and authorize the book identified by input id.
     *
     * @return \App\Models\Book
     */
    protected function getAndAuthorizeBook(): Book
    {
        $book = Book::public()->findOrFail(Request::input('id'));
        $this->authorize('view', $book);
        return $book;
    }

    /**
     * Return the path to the books index route.
     *
     * @return string
     */
    protected function getBooksIndexPath(): string
    {
        return lp(route('books.index', null, false));
    }

    /**
     * Return the book cart.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getCart(): Collection
    {
        return collect($this->getSessionCart())->map(function ($quantity, $id) {
            // n+1...
            $book = Book::public()->find($id);
            if ($book && $book->request) {
                $quantity = (int) $quantity;
                return (object) [
                    'book' => $book,
                    'quantity' => $quantity,
                ];
            } else {
                return null;
            }
        })->filter(function ($item) {
            return $item;
        })->sortBy(function ($item) {
            return $item->book->title;
        })->values();
    }

    /**
     * Return the session book cart.
     *
     * @return array
     */
    protected function getSessionCart(): array
    {
        $cart = Request::session()->get($this->getSessionCartKey(), []);
        if (gettype($cart) !== 'array') {
            $cart = [];
        }
        return $cart;
    }

    /**
     * Return the session cart key.
     *
     * @return string
     */
    protected function getSessionCartKey(): string
    {
        return Config::get('abhayagiri.book_cart.session_key');
    }

    /**
     * Return the path to the show cart route.
     *
     * @return string
     */
    protected function getShowCartPath(): string
    {
        return lp(route('books.cart.show', null, false));
    }

    /**
     * Return important information HTML for the book cart.
     *
     * @return string
     */
    protected function getInformationHtml(): string
    {
        $html = '';
        if (Lang::locale() === 'th') {
            $html = (string) Config::get('settings.books.request_form_th_html');
        }
        if ($html === '') {
            $html = (string) Config::get('settings.books.request_form_en_html');
        }
        return $html;
    }

    /**
     * Set (update) the session book cart.
     *
     * @param  array  $cart
     *
     * @return void
     */
    protected function setSessionCart(array $cart): void
    {
        Request::session()->put($this->getSessionCartKey(), $cart);
    }
}
