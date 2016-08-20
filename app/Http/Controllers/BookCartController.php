<?php

namespace App\Http\Controllers;

use Closure;
use Config;
use Illuminate\Http\Request;
use Mail;

use App\Http\Controllers\Controller;

class BookCartController extends Controller
{
    public function addBook(Request $request, $id)
    {
        $this->withBooks($request, function(&$books) use ($id) {
            $quantity = array_get($books, $id, 0);
            $books[$id] = $quantity + 1;
        });
    }

    public function updateBook(Request $request, $id, $quantity)
    {
        $this->withBooks($request, function(&$books) use ($id, $quantity) {
            $books[$id] = (int) $quantity;
        });
    }

    public function deleteBook(Request $request, $id)
    {
        $this->withBooks($request, function(&$books) use ($id) {
            unset($books[$id]);
        });
    }

    public function sendRequest(Request $request)
    {
        $name = $request->input('fname') . ' ' . $request->input('lname');
        $email = $request->input('email');

        Mail::send(['text' => 'mail.book_request'], $request->all(),
                   function ($message) use ($name, $email) {
            $message->from(Config::get('abhayagiri.mail.book_request_from'),
                'Website Book Request');
            $message->replyTo($email, $name);
            $message->to(Config::get('abhayagiri.mail.book_request_to'));
            $message->subject("Book Request from $name <$email>");
        });

        $request->session()->set('books', []);

        return '1';
    }

    protected function withBooks(Request $request, Closure $closure)
    {
        $books = $request->session()->get('books', []);
        $closure($books);
        $request->session()->set('books', $books);
    }
}
