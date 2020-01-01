<?php

namespace Tests\Unit;

use App\Mail\BookCartAdminMailer;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class BookCartAdminMailerTest extends TestCase
{
    use RefreshDatabase;

    public function testReplyTo()
    {
        $this->assertEquals([
            'name' => 'John Doe',
            'address' => 'john@example.com',
        ], $this->getMailer()->replyTo[0]);
    }

    public function testGetSubject()
    {
        $this->assertEquals(
            'Book Request from John Doe <john@example.com>',
            $this->getMailer()->getSubject()
        );
    }

    public function testGetTo()
    {
        Config::set('abhayagiri.mail.book_request_to.address', 'admin@example.com');
        Config::set('abhayagiri.mail.book_request_to.name', 'Mr. Admin');
        $to = $this->getMailer()->getTo();
        $this->assertEquals('admin@example.com', $to->email);
        $this->assertEquals('Mr. Admin', $to->name);
    }

    public function testRender()
    {
        $html = $this->getMailer()->render();
        $this->assertStringContainsString('Book Request from John Doe &lt;john@example.com&gt;', $html);
        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringContainsString('New York', $html);
        $this->assertStringContainsString('Dhammapada', $html);
    }

    protected function getMailer()
    {
        $book = factory(Book::class)->create(['title' => 'Dhammapada']);

        $cart = collect([
            (object) ['book' => $book, 'quantity' => 2]
        ]);

        $shipping = (object) [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'address' => '123 Main St.',
            'city' => 'New York',
            'state' => 'New York',
            'zip' => '10101',
            'country' => 'United States',
            'comments' => '<script>alert(1)</script>',
        ];

        return (new BookCartAdminMailer($cart, $shipping))->build();
    }
}
