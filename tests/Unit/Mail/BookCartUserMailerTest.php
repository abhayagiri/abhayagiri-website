<?php

namespace Tests\Unit;

use App\Mail\BookCartUserMailer;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookCartUserMailerTest extends TestCase
{
    use RefreshDatabase;

    public function testGetSubject()
    {
        $this->assertEquals(
            'Abhayagiri Monastery - Book Request Received',
            $this->getMailer()->locale('en')->getSubject()
        );
    }

    public function testGetTo()
    {
        $to = $this->getMailer()->getTo();
        $this->assertEquals('john@example.com', $to->email);
        $this->assertEquals('John Doe', $to->name);
    }

    public function testRender()
    {
        $html = $this->getMailer()->locale('en')->render();

        $this->assertStringContainsString('Book Request Received', $html);
        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringContainsString('New York', $html);
        $this->assertStringContainsString('Dhammapada', $html);

        $html = $this->getMailer()->locale('th')->render();

        $this->assertStringContainsString('ได้รับคำขอหนังสือแล้ว', $html);
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

        return (new BookCartUserMailer($cart, $shipping))->build();
    }
}
