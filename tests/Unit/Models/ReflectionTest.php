<?php

namespace Tests\Unit;

use App\Models\Reflection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReflectionTest extends TestCase
{
    use RefreshDatabase;

    public function testBodyHtmlIsNotNull()
    {
        $this->seed();
        $reflection = factory(Reflection::class)->create();
        $this->assertNotNull($reflection->body_html);
    }
}
