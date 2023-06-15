<?php

namespace App\Models;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Support\Facades\Config;
use App\Models\Setting\ImageSetting;
use App\Models\Setting\MarkdownSetting;
use App\Models\Setting\NumberSetting;
use App\Models\Setting\PlaylistGroupSetting;
use App\Models\Setting\SettingNotFoundException;
use App\Models\Setting\StringSetting;
use App\Models\Setting\TextSetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Parental\HasChildren;

class Setting extends Model
{
    use CrudTrait;
    use HasChildren;

    /**
     * The mapping of types to classes.
     *
     * @var array
     */
    protected $childTypes = [
        'image' => ImageSetting::class,
        'markdown' => MarkdownSetting::class,
        'number' => NumberSetting::class,
        'playlist_group' => PlaylistGroupSetting::class,
        'string' => StringSetting::class,
        'text' => TextSetting::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['key', 'type', 'value'];

    /**
     * The cache of all settings indexed by key.
     *
     * @var array
     */
    protected static $settingsCache = null;

    /**
     * Callback for adding admin CRUD fields.
     *
     * @param  \Backpack\CRUD\app\Http\Controllers\CrudController  $controller
     *
     * @return void
     */
    public function addCrudFields(CrudController $controller): void
    {
    }

    /**
     * Return HTML for the value column in the admin CRUD list.
     *
     * @return string
     */
    public function getCrudListHtml(): string
    {
        return $this->limitCrudListHtml((string) $this->value);
    }

    /**
     * Get the admin CRUD validation rules.
     *
     * @return array
     */
    public function getCrudRules(): array
    {
        return [
            'value' => 'required',
        ];
    }

    /**
     * Return a setting from cache or database.
     *
     * If the setting doesn't exist in the cache/database, and the corresponding
     * default exists, then this will create it with the defaults and return it.
     *
     * If no defaults exist, then this will throw an exception.
     *
     * @param  string  $key
     *
     * @throws \App\Models\Setting\SettingNotFoundException
     *
     * @return self
     */
    public static function getByKey(string $key): self
    {
        if (static::$settingsCache === null) {
            static::updateCache();
        }
        $setting = static::$settingsCache->get($key);
        if (!$setting) {
            $setting = static::where('key', '=', $key)->first();
            if ($setting) {
                static::resetCache();
            }
        }
        if (!$setting) {
            $params = config('settings.' . $key);
            if ($params && array_key_exists('type', $params)) {
                $setting = static::create([
                    'type' => $params['type'],
                    'key' => $key,
                    'value' => $params['default_value'] ?? null,
                ]);
                static::resetCache();
            } else {
                throw new SettingNotFoundException('Could not find key ' . $key);
            }
        }
        return $setting;
    }

    /**
     * Migrate old settings and create defaults.
     *
     * @return array
     */
    public static function sync(): array
    {
        return array_merge(
            static::migrateOldSettings(),
            static::createDefaultSettings()
        );
    }

    /**
     * Reset the settings cache.
     *
     * @return Collection
     */
    public static function resetCache(): void
    {
        static::$settingsCache = null;
    }

    /**
     * Update the settings cache.
     *
     * @return Collection
     */
    public static function updateCache(): Collection
    {
        static::$settingsCache = static::orderBy('key')->get()
                                       ->mapWithKeys(function ($setting) {
                                           return [$setting->key => $setting];
                                       });
        return static::$settingsCache;
    }

    /**
     * Truncate and escape text for the value column in the admin CRUD list.
     *
     * @return string
     */
    protected function limitCrudListHtml(string $text): string
    {
        return e(Str::limit($text, 50, '[...]'));
    }

    /**
     * Create any missing setitngs.
     *
     * @return array
     */
    protected static function createDefaultSettings(): array
    {
        $log = [];
        foreach (static::getFlatConfig() as $key => $params) {
            $existing = static::where('key', '=', $key)->first();
            if (!$existing) {
                static::create([
                    'type' => $params['type'],
                    'key' => $key,
                    'value' => $params['default_value'] ?? null,
                ]);
                static::resetCache();
                $log[] = 'Created new ' . $key;
            }
        }
        return $log;
    }

    /**
     * Return a flattened array (by key) on Config::get('settings').
     *
     * @return array
     */
    protected static function getFlatConfig(): array
    {
        $flatten = function ($array) use (&$flatten) {
            $result = [];
            foreach ($array as $key => $value) {
                if (is_array($value) && !array_key_exists('type', $value)) {
                    foreach ($flatten($value) as $vkey => $vvalue) {
                        $result[$key . '.' . $vkey] = $vvalue;
                    }
                } else {
                    $result[$key] = $value;
                }
            }
            return $result;
        };
        return $flatten(Config::get('settings'));
    }

    /**
     * Migrate old settings.
     *
     * @return array
     */
    protected static function migrateOldSettings(): array
    {
        $log = [];
        foreach (static::getFlatConfig() as $key => $params) {
            $oldKey = $params['old_key'] ?? null;
            if (!$oldKey) {
                continue;
            }
            $type = $params['type'];
            $defaultValue = $params['default_value'] ?? null;
            $oldTh = null;
            $value = null;
            if ($type == 'text' || $type == 'markdown') {
                $old = StringSetting::where('key', '=', $oldKey['text_en'])->first();
                $oldTh = StringSetting::where('key', '=', $oldKey['text_th'])->first();
                $value = [
                    'text_en' => $old->value ?? null,
                    'text_th' => $oldTh->value ?? null,
                ];
            } else {
                $old = StringSetting::where('key', '=', $oldKey)->first();
                $value = $old->value ?? null;
            }
            if ($old) {
                $existing = static::where('key', '=', $key)->first();
                if ($existing) {
                    $existing->delete();
                    $log[] = 'Deleted existing ' . $key;
                }
                static::create([
                    'type' => $type,
                    'key' => $key,
                    'value' => $value,
                ]);
                static::resetCache();
                $log[] = 'Created new ' . $key;
                $old->delete();
                $log[] = 'Deleted old ' . $old->key;
            }
            if ($oldTh) {
                $oldTh->delete();
                $log[] = 'Deleted old ' . $oldTh->key;
            }
        }
        return $log;
    }

    public function playlist_group(): BelongsTo
    {
        return $this->belongsTo('App\Models\PlaylistGroup', 'value');
    }
}
