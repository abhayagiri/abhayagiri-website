<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Resident;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResidentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testImage()
    {
        $this->withoutImageCreation();
        $resident = factory(Resident::class)->create(['id' => 123]);
        $response = $this->get(route('residents.image', [$resident, 'resident', 'jpg']));
        $response->assertOk();
    }
}
