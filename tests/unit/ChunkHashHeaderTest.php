<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

class ChunkHashHeaderTest extends TestCase
{
    public function testChunkHashHeader()
    {
        File::shouldReceive('exists')
            ->once()
            ->andReturn(true);
        File::shouldReceive('get')
            ->once()
            ->with(base_path('.stamp.json'))
            ->andReturn('{"chunkHash":"123"}');
        $this->get('/api/playlists')
            ->assertSuccessful()
            ->assertHeader('app-chunk-hash', '123');
    }
}
