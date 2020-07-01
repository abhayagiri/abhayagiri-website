<?php

namespace Tests\Feature\Http\Controllers\Admin\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ValidateUrlControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testValidYoutubeInvocation()
    {
        $response = $this
            ->actingAsAdmin()
            ->postJson(route('admin.api.validate-url'), [
                'url' => 'http://www.youtube.com/?v=7wFjFgklTtY',
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('valid', true);
    }

    public function testInvalidYoutubeInvocation()
    {
        $response = $this
            ->actingAsAdmin()
            ->postJson(route('admin.api.validate-url'), [
                'url' => 'http://www.test.com/?v=7wFjFgklTtY',
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('valid', false);
    }

    public function testValidGalleryInvocation()
    {
        $response = $this
            ->actingAsAdmin()
            ->postJson(route('admin.api.validate-url'), [
                'url' => 'https://www.abhayagiri.org/gallery/228-winter-retreat-2020',
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('valid', true);
    }

    public function testInvalidGalleryInvocation()
    {
        $response = $this
            ->actingAsAdmin()
            ->postJson(route('admin.api.validate-url'), [
                'url' => 'https://www.abhayagiri.org/g-allery/228-winter-retreat-2020',
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('valid', false);
    }

    public function testValidTalkInvocation()
    {
        $response = $this
            ->actingAsAdmin()
            ->postJson(route('admin.api.validate-url'), [
                'url' => 'https://www.abhayagiri.org/talks/228-winter-retreat-2020',
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('valid', true);
    }

    public function testInvalidTalkInvocation()
    {
        $response = $this
            ->actingAsAdmin()
            ->postJson(route('admin.api.validate-url'), [
                'url' => 'https://www.abhayagiri.org/t-alk/228-winter-retreat-2020',
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('valid', false);
    }
}
