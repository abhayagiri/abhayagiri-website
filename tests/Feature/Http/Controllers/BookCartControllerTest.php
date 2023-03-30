<?php

namespace Tests\Feature\Http\Controllers;

use App\Mail\BookCartAdminMailer;
use App\Mail\BookCartUserMailer;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use NoCaptcha;
use Tests\TestCase;

class BookCartControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('abhayagiri.book_cart.session_key', 'books');
    }

    public function testAdd()
    {
        $response = $this->withSession([])
                         ->post(route('books.cart.add'), ['id' => 123]);
        $response->assertNotFound();
        $response->assertSessionMissing('books');

        factory(Book::class)->create(['id' => 123]);

        $response = $this->post(route('books.cart.add'), ['id' => 123]);
        $response->assertRedirect(route('books.cart.show'));
        $response->assertSessionHas('books', [123 => 1]);

        $response = $this->withSession(['books' => [123 => 10]])
                         ->post(route('books.cart.add'), ['id' => 123]);
        $response->assertRedirect(route('books.cart.show'));
        $response->assertSessionHas('books', [123 => 11]);
    }

    public function testDestroy()
    {
        $response = $this->withSession([])
                         ->delete(route('books.cart.destroy'), ['id' => 123]);
        $response->assertNotFound();
        $response->assertSessionMissing('books');

        factory(Book::class)->create(['id' => 123]);

        $response = $this->delete(route('books.cart.destroy'), ['id' => 123]);
        $response->assertRedirect(route('books.cart.show'));
        $response->assertSessionHas('books', []);

        $response = $this->withSession(['books' => [1 => 1, 123 => 10]])
                         ->delete(route('books.cart.destroy'), ['id' => 123]);
        $response->assertRedirect(route('books.cart.show'));
        $response->assertSessionHas('books', [1 => 1]);
    }

    public function testEditSubmission()
    {
        $response = $this->get(route('books.cart.edit'));
        $response
            ->assertOk()
            ->assertSee('Shipping');

        $response = $this->get(route('th.books.cart.edit'));
        $response
            ->assertOk()
            ->assertSee('การส่งสินค้า');

        $book1 = factory(Book::class)->create(['id' => 1001, 'title' => 'First']);
        $book2 = factory(Book::class)->create(['id' => 1002, 'title' => 'Second']);

        $response = $this->withSession(['books' => [
            1001 => 10,
            1002 => 5,
        ]])->get(route('books.cart.edit'));
        $response->assertOk()
                 ->assertSee('First')
                 ->assertSee('Second')
                 ->assertSee('10')
                 ->assertSee('5');
    }

    public function testSendSubmission()
    {
        Config::set('abhayagiri.mail.book_request_to.address', 'admin@example.com');
        Config::set('abhayagiri.mail.book_request_to.name', 'Mr. Admin');

        Mail::fake();

        NoCaptcha::shouldReceive('verifyResponse')
            ->once()
            ->andReturn(true);

        $book1 = factory(Book::class)->create(['id' => 1001, 'title' => 'First']);
        $book2 = factory(Book::class)->create(['id' => 1002, 'title' => 'Second']);

        $response = $this->withSession(['books' => [
            1001 => 10,
            1002 => 5,
        ]])->post(route('books.cart.submit'), [
            'g-recaptcha-response' => '1',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'address' => '123 Main St.',
            'city' => 'New York',
            'state' => 'New York',
            'zip' => '10101',
            'country' => 'United States',
            'comments' => 'Hello!',
        ]);

        Mail::assertSent(BookCartAdminMailer::class, function ($mail) {
            return 'admin@example.com' === $mail->to[0]['address'] &&
                   'First' === $mail->cart[0]->book->title &&
                   'John' === $mail->shipping->first_name;
        });

        Mail::assertSent(BookCartUserMailer::class, function ($mail) {
            return 'john@example.com' === $mail->to[0]['address'] &&
                   'Second' === $mail->cart[1]->book->title &&
                   'Doe' === $mail->shipping->last_name;
        });
    }

    public function testShow()
    {
        factory(Book::class)->create(['id' => 123, 'title' => 'Hello!']);
        $response = $this->withSession(['books' => [123 => 95159]])
                         ->get(route('books.cart.show'));
        $response->assertOk()
                 ->assertSee('Hello!')
                 ->assertSee('95159');
    }

    public function testUpdate()
    {
        $response = $this->withSession([])
                         ->put(route('books.cart.update'), [
                             'id' => 123,
                             'quantity' => 5,
                         ]);
        $response->assertNotFound();
        $response->assertSessionMissing('books');

        factory(Book::class)->create(['id' => 99]);
        factory(Book::class)->create(['id' => 123]);

        $response = $this->withSession([])
                         ->put(route('books.cart.update'), [
                             'id' => 123,
                             'quantity' => 5,
                         ]);
        $response->assertRedirect(route('books.cart.show'));
        $response->assertSessionHas('books', [123 => 5]);

        $response = $this->withSession(['books' => [99 => 2]])
                         ->put(route('books.cart.update'), [
                             'id' => 123,
                             'quantity' => 25,
                         ]);
        $response->assertRedirect(route('books.cart.show'));
        $response->assertSessionHas('books', [
            99 => 2,
            123 => 25,
        ]);

        $response = $this->put(route('books.cart.update'), [
            'id' => 123,
            'quantity' => 0,
        ]);
        $response->assertSessionHas('books', [99 => 2]);
        $response = $this->put(route('books.cart.update'), [
            'id' => 99,
            'quantity' => -10,
        ]);
        $response->assertSessionHas('books', []);
    }
}
