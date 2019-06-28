<?php

namespace Tests\Unit;

use App\Models\SyncTask;
use App\Models\SyncTaskLog;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SyncTaskLogTest extends TestCase
{
    use DatabaseTransactions;

    public function testCreatedAtWithMicroseconds()
    {
        $syncTask = SyncTask::create(['key' => 'abc']);
        $this->assertTrue($syncTask->exists);
        $syncTaskLog = SyncTaskLog::create(['sync_task_id' => $syncTask->id]);
        $this->assertTrue($syncTaskLog->exists);
        $syncTaskLog->refresh();
        $this->assertRegExp('/^....-..-.. ..:..:..\.\d{6}$/',
            $syncTaskLog->getAttributes()['created_at']);
    }
}
