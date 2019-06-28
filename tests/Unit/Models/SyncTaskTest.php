<?php

namespace Tests\Unit;

use App\Models\SyncTask;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SyncTaskTest extends TestCase
{
    use DatabaseTransactions;

    public function testCreateWithLock()
    {
        $syncTask = SyncTask::createWithLock('abc');
        $this->assertNotNull($syncTask);
        $this->assertNotNull($syncTask->locked_until);
        $this->assertNull(SyncTask::createWithLock('abc'));
        $syncTask->releaseLock();
        $this->assertNull($syncTask->locked_until);
        $this->assertNull(SyncTask::createWithLock('abc'));
    }

    public function testFindOrCreateWithLock()
    {
        $syncTask = SyncTask::findOrCreateWithLock('abc');
        $this->assertNotNull($syncTask);
        $this->assertNotNull($syncTask->locked_until);
        $altTask = SyncTask::findOrCreateWithLock('abc');
        $this->assertNull($altTask);
        $syncTask->releaseLock();
        $this->assertNull($syncTask->locked_until);
        $altTask = SyncTask::findOrCreateWithLock('abc');
        $this->assertNotNull($altTask);
        $this->assertNotNull($altTask->locked_until);
        $this->assertEquals($syncTask->id, $altTask->id);
    }

    public function testFindWithLock()
    {
        $this->assertNull(SyncTask::findWithLock('abc'));
        $syncTask = SyncTask::create(['key' => 'abc']);
        $this->assertNull($syncTask->locked_until);
        $syncTask = SyncTask::findWithLock('abc');
        $this->assertNotNull($syncTask);
        $this->assertNotNull($syncTask->locked_until);
    }

    public function testGetAndReleaseLock()
    {
        $syncTask = SyncTask::create(['key' => 'abc']);
        $this->assertTrue($syncTask->exists);
        $this->assertTrue($syncTask->getLock());
        $this->assertFalse($syncTask->getLock());
        $this->assertNotNull($syncTask->locked_until);
        $altTask = SyncTask::where('key', 'abc')->firstOrFail();
        $this->assertFalse($altTask->getLock());
        $this->assertNotNull($syncTask->locked_until);
        $syncTask->releaseLock();
        $this->assertNull($syncTask->locked_until);
        $altTask->refresh();
        $this->assertNull($altTask->locked_until);

        $this->assertTrue($syncTask->getLock(-1)); // Ensure in the past.
        $this->assertTrue($syncTask->getLock());
    }

    public function testGetStateAttribute()
    {
        $syncTask = new SyncTask;
        $this->assertEquals('queued', $syncTask->state);
        $syncTask->started_at = Carbon::now();
        $this->assertEquals('running', $syncTask->state);
        $syncTask->completed_at = Carbon::now();
        $this->assertEquals('complete', $syncTask->state);
        $syncTask->started_at = null;
        $this->assertEquals('unknown', $syncTask->state);
    }

    public function testLogs()
    {
        $syncTask = SyncTask::create(['key' => 'abc']);
        $this->assertEquals(0, $syncTask->logs->count());
        $syncTask->logs()->create(['log' => 'a']);
        $syncTask->refresh();
        $this->assertEquals(1, $syncTask->logs->count());
        $this->assertEquals('a', $syncTask->logs->first()->log);
    }

    public function testValidation()
    {
        $syncTask = new SyncTask;
        $this->assertFalse($syncTask->save());
        $syncTask->key = 'abc';
        $this->assertTrue($syncTask->save());
    }
}
