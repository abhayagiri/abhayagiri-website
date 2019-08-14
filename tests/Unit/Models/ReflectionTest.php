<?php

namespace Tests\Unit;

use App\Models\Reflection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ReflectionTest extends TestCase
{
    use DatabaseTransactions;

    public function testBodyHtmlIsNotNull()
    {
        $reflection = factory(Reflection::class)->create();
        $this->assertNotNull($reflection->body_html);
    }
}
