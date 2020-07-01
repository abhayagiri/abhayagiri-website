<?php

namespace Tests\Unit\Utilities;

use Tests\TestCase;
use App\Utilities\ValidateUrlForEmbed;

class ValidateUrlForEmbedTest extends TestCase
{
    public function testValidate()
    {
        $this->assertTrue(ValidateUrlForEmbed::validate('https://youtu.be/m02JGV8_WZg'));
        $this->assertTrue(ValidateUrlForEmbed::validate('https://www.abhayagiri.org/gallery/228-winter-retreat-2020'));
        $this->assertTrue(ValidateUrlForEmbed::validate('https://www.abhayagiri.org/talks/7339-freedom-from-fear-anxiety'));

        $this->assertFalse(ValidateUrlForEmbed::validate('https://metu.be/m02JGV8_WZg'));
        $this->assertFalse(ValidateUrlForEmbed::validate('https://www.abhayagiri.org/galllery/228-winter-retreat-2020'));
        $this->assertFalse(ValidateUrlForEmbed::validate('https://www.abhayagiri.org/tallks/7339-freedom-from-fear-anxiety'));

        $this->assertFalse(ValidateUrlForEmbed::validate('A random string'));
        $this->assertFalse(ValidateUrlForEmbed::validate('https://www.abhayagiri.org/news'));
    }

    public function testForYouTube()
    {
        $this->assertEquals('7wFjFgklTtY', ValidateUrlForEmbed::forYouTube('https://www.youtube.com/watch?v=7wFjFgklTtY&feature=youtu.be'));
        $this->assertEquals('7wFjFgklTtY', ValidateUrlForEmbed::forYouTube('https://youtu.be/7wFjFgklTtY&feature=youtu.be'));
        $this->assertEquals('7wFjFgklTtY', ValidateUrlForEmbed::forYouTube('http://www.youtube.com/embed/7wFjFgklTtY'));
        $this->assertEquals('7wFjFgklTtY', ValidateUrlForEmbed::forYouTube('http://www.youtube.com/?v=7wFjFgklTtY'));
        $this->assertEquals('7wFjFgklTtY', ValidateUrlForEmbed::forYouTube('http://www.youtube.com/e/7wFjFgklTtY'));
        $this->assertEquals('7wFjFgklTtY', ValidateUrlForEmbed::forYouTube('http://www.youtube.com/?feature=player_embedded&v=7wFjFgklTtY'));
    }

    public function testForGallery()
    {
        $this->assertEquals('228-winter-retreat-2020', ValidateUrlForEmbed::forGallery('https://www.abhayagiri.org/gallery/228-winter-retreat-2020'));
    }

    public function testForTalk()
    {
        $this->assertEquals('7339-freedom-from-fear-anxiety', ValidateUrlForEmbed::forTalk('https://www.abhayagiri.org/talks/7339-freedom-from-fear-anxiety'));
    }
}
