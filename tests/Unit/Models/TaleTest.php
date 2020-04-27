<?php

namespace Tests\Unit;

use App\Models\Tale;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaleTest extends TestCase
{
    use RefreshDatabase;

    public function testSlug()
    {
        $tale = factory(Tale::class)->make(['title_en' => null]);
        $this->assertEquals($tale->slug, '');
        $tale->title_en = 'One Way Inner Stay';
        $this->assertEquals($tale->slug, 'one-way-inner-stay');
        $tale->save();
        $this->assertEquals(Tale::find($tale->id)->slug, 'one-way-inner-stay');
    }
}
