<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\LongRunningRequest;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class LongRunningRequestTest extends TestCase
{
    public function testLongRunningRequest()
    {
        Config::shouldReceive('get')->with('abhayagiri.long_running_request_time_limit')
                                    ->andReturn(500);
        $this->withTimeLimit(0, function() {
            $this->withLongRunningRequest(function() {
                $this->assertEquals(0, ini_get('max_execution_time'));
            });
            $this->assertEquals(0, ini_get('max_execution_time'));
        });
        $this->withTimeLimit(30, function() {
            $this->withLongRunningRequest(function() {
                $this->assertEquals(500, ini_get('max_execution_time'));
            });
            $this->assertEquals(30, ini_get('max_execution_time'));
        });
        $this->withTimeLimit(1000, function() {
            $this->withLongRunningRequest(function() {
                $this->assertEquals(1000, ini_get('max_execution_time'));
            });
            $this->assertEquals(1000, ini_get('max_execution_time'));
        });
    }

    protected function withLongRunningRequest(Closure $closure)
    {
        $request = new Request;
        $middleware = new LongRunningRequest;
        $middleware->handle($request, function($req) use ($closure) {
            $closure();
        });
    }

    protected function withTimeLimit(int $timeLimit, Closure $closure)
    {
        $originalLimit = ini_get('max_execution_time');
        try {
            set_time_limit($timeLimit);
            $closure();
        } finally {
            set_time_limit($originalLimit);
        }
    }
}
