<?php

namespace Tests\Feature\Http\Controllers\Admin\Api;

use App\Markdown;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RenderMarkdownControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testInvocation()
    {
        $text = 'Test';

        $response = $this
            ->actingAsAdmin()
            ->postJson(route('admin.api.render-markdown'), [
                'text' => $text,
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('html', Markdown::toHtml($text));
    }
}
