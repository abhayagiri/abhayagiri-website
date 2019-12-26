<?php

namespace App\Models;

use Carbon\Carbon;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class SyncTask extends Model
{
    /**
     * The default time (in seconds) for a lock.
     *
     * @var int
     */
    const DEFAULT_LOCK_TIMEOUT = 60;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'extra' => '[]',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'ended_at' => 'datetime',
        'extra' => 'array',
        'locked_until' => 'datetime',
        'started_at' => 'datetime',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /*
     * Accessors and Mutators *
     */

    /**
     * Return the state of the task.
     *
     * - queued: The task is ready to processed.
     * - running: The task is currently running.
     * - complete: The task is complete.
     * - unknown: Indeterminate (error) state.
     *
     * @return string
     */
    public function getStateAttribute() : string
    {
        if ($this->started_at) {
            if ($this->completed_at) {
                return 'complete';
            } else {
                return 'running';
            }
        } else {
            if ($this->completed_at) {
                return 'unknown';
            } else {
                return 'queued';
            }
        }
    }

    /*
     * Relationships *
     */

    public function logs() : HasMany
    {
        return $this->hasMany('App\Models\SyncTaskLog')
                    ->orderBy('sync_task_logs.created_at');
    }

    /*
     * Scopes *
     */

    public function scopeQueued(Builder $query) : Builder
    {
        return $query->whereNull('started_at')->whereNull('completed_at');
    }

    public function scopeComplete(Builder $query) : Builder
    {
        return $query->whereNotNull('started_at')
                     ->whereNotNull('completed_at');
    }

    public function scopeUnlocked(Builder $query) : Builder
    {
        return $query->where(function ($query) {
            return $query->whereNull('locked_until')
                         ->orWhere('locked_until', '<=', Carbon::now());
        });
    }

    /*
     * Validations *
     */

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot() : void
    {
        parent::boot();

        static::saving(function ($update) {
            if (!$update->valid()) {
                return false;
            }
        });
    }

    /**
     * Return the validity state.
     *
     * @return bool
     */
    public function valid() : bool
    {
        return !!$this->key;
    }

    /*
     * Other *
     */

    /**
     * Add a message to the log.
     *
     * @param string $message
     * @param mixed $vars (Optional) One or more variables to log
     *
     * @return SyncTask
     *
     * @throws RuntimeException
     */
    public function addLog(string $message, ...$vars) : SyncTask
    {
        if (!$this->exists) {
            throw new RuntimeException('Cannot add log to non-persisted SyncTask');
        }
        if (count($vars) > 0) {
            if (count($vars) == 1) {
                $vars = $vars[0];
            }
            $message .= ' ' . json_encode($vars, JSON_PRETTY_PRINT);
        }
        $this->logs()->create(['log' => $message]);
        return $this;
    }

    /**
     * Create a sync task identified by $key, lock it, and return it.
     *
     * This will return null if unsuccessful.
     *
     * @param string $key
     * @param int    $timeout (Optional)
     *
     * @return SyncTask|null
     */
    public static function createWithLock(string $key, int $timeout = null)
                                          : ?SyncTask
    {
        $item = new static;
        $item->key = $key;
        $timeout = $timeout ?? static::DEFAULT_LOCK_TIMEOUT;
        $item->locked_until = Carbon::now()->addSeconds($timeout);
        try {
            if ($item->save()) {
                return $item;
            } else {
                return null;
            }
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                // Duplicate key
                return null;
            } else {
                throw $e;
            }
        }
    }

    /**
     * Find or create the sync task identified by $key, lock it, and return it.
     *
     * This will return null if unsuccessful.
     *
     * @param string $key
     * @param int    $timeout (Optional)
     *
     * @return SyncTask|null
     */
    public static function findOrCreateWithLock(
        string $key,
        int $timeout = null
    ) : ?SyncTask {
        $item = static::findWithLock($key, $timeout);
        if (!$item) {
            $item = static::createWithLock($key, $timeout);
            if (!$item) {
                // Try one more time...
                $item = static::findWithLock($key, $timeout);
            }
        }
        return $item;
    }

    /**
     * Find the sync task identified by $key, lock it, and return it.
     *
     * This will return null if unsuccessful.
     *
     * @param string $key
     * @param int    $timeout (Optional)
     *
     * @return SyncTask|null
     */
    public static function findWithLock(string $key, int $timeout = null)
                                        : ?SyncTask
    {
        $query = static::where('key', $key);
        if ($item = $query->first()) {
            if ($item->getLock($timeout)) {
                return $item;
            }
        }
        return null;
    }

    /**
     * Acquire the lock on this sync task.
     *
     * This will return true if the lock was acquired, false otherwise.
     *
     * The sync task must be persisted (saved) before calling this method.
     *
     * @param int $timeout (Optional)
     *
     * @return bool
     *
     * @throws RuntimeException
     */
    public function getLock(int $timeout = null) : bool
    {
        if (!$this->exists) {
            throw new RuntimeException('SyncTask is not persisted');
        };
        $timeout = $timeout ?? static::DEFAULT_LOCK_TIMEOUT;
        $releaseTime = Carbon::now()->addSeconds($timeout);
        $table = DB::table($this->getTable());
        $count = $table
            ->where('id', $this->id)
            ->where(function ($query) {
                return $query->whereNull('locked_until')
                             ->orWhere('locked_until', '<=', Carbon::now());
            })->update(['locked_until' => $releaseTime]);
        if ($count) {
            $this->locked_until = $releaseTime;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Remove the lock on this sync task.
     *
     * @return SyncLock
     */
    public function releaseLock() : SyncTask
    {
        $table = DB::table($this->getTable());
        $count = $table->where('id', $this->id)
                       ->update(['locked_until' => null]);
        $this->locked_until = null;
        return $this;
    }

    /**
     * Run a handler under a lock.
     *
     * If the lock was obtained, $handler called with this SyncTask as an
     * argument. In addition, started_at and completed_at will be set if the
     * handler returns true.
     *
     * If the lock was not obtained, the $handler is not called and, if
     * supplied, $noLock is called.  No modifications are made to started_at
     * and completed_at.
     *
     * @param Closure $handler
     * @param Closure $noLock  (Optional)
     *
     * @return SyncTask
     */
    public function runWithLock(Closure $handler, Closure $noLock = null)
                                : SyncTask
    {
        if ($this->getLock()) {
            try {
                $this->started_at = Carbon::now();
                $this->completed_at = null;
                $this->save();
                $result = false;
                try {
                    $result = $handler($this);
                } finally {
                    if ($result) {
                        $this->completed_at = Carbon::now();
                    } else {
                        $this->started_at = null;
                    }
                    $this->save();
                }
            } finally {
                $this->releaseLock();
            }
        } else {
            if ($noLock) {
                $noLock($this);
            }
        }
        return $this;
    }
}
