<?php

namespace App\Models\Traits;

use App\Utilities\HtmlToText;
use App\Utilities\TextSplitter;
use Illuminate\Support\Facades\Cache;

/**
 * Algolia Search Trait
 *
 * Models that use this trait will be searchable via Algolia.
 *
 * The basic structure for Algolia search record across all models is the
 * following:
 *
 * [
 *     'type' => 'foo',
 *     'id' => 123,
 *     'text' => [
 *         'lng' => 'en', // or 'th'
 *         'path' => '/foo/123-hello',
 *         'author' => 'Tan Foo',
 *         'title' => 'Hello There!',
 *         'body' => 'Welcome...',
 *         'body_index' => 3,
 *      ],
 *     ...
 * ]
 *
 * Models may add additional fields to those defined above, but this is the
 * minimum (with the exception of `author`). The `author` field is optional.
 *
 * Algolia imposes a maximum size of about 10kB per record. This means that any
 * sizable field, i.e., body in our case, needs to be split across multiple
 * records.
 *
 * The 'body_index' is a integer that indicates the index of the body segment
 * included in this record.
 *
 * In addition, the en/th model attributes create an additional split. If the th
 * attributes are not present, then no split should be created.
 *
 * The textual fields that are split against need to be in the `text` subarray.
 * This is a workaround due to limitations in the scout-extended package. See
 * https://github.com/algolia/scout-extended/issues/212.
 *
 */
trait IsSearchable
{
    /**
     * The maximum characters in each segment when splitting search text.
     *
     * @var int
     */
    // protected $textSplitterMax = 2000;

    /**
     * The minimum characters in each segment when splitting search text.
     *
     * @var int
     */
    // protected $textSplitterMin = 500;

    /**
     * Get the model's class basename.
     *
     * @return string
     */
    public function getModelBasenameAttribute(): string
    {
        return kebab_case(class_basename(get_called_class()));
    }

    /**
     * Initialize the trait.
     *
     * @return void
     */
    public function initializeIsSearchable(): void
    {
        $this->append('model_basename');
    }

    /**
     * Determine if the model should be searchable.
     *
     * @return bool
     */
    public function shouldBeSearchable(): bool
    {
        if (method_exists($this, 'isPublic')) {
            return $this->isPublic();
        } else {
            return true;
        }
    }

    /**
     * Determine if the model should be searchable in testing environments.
     *
     * @return bool
     */
    public function shouldBeTestingSearchable(): bool
    {
        if ($this->testingSearchMaxRecords < 1) {
            return true;
        }
        $cache = Cache::store('array');
        $cacheKey = 'shouldBeTestingSearchable-' .
                    $this->getModelBasenameAttribute();
        if (($ids = $cache->get($cacheKey)) === null) {
            if (in_array(PostedAtTrait::class, class_uses_recursive($this))) {
                $query = static::public()->postOrdered();
            } else {
                $query = static::orderBy('id', 'desc');
            }
            $ids = $query->limit($this->testingSearchMaxRecords)
                          ->pluck('id')
                          ->mapWithKeys(function ($id) {
                              return [$id => $id];
                          });
            $cache->set($cacheKey, $ids, 10);
        }
        return $ids->has($this->id);
    }

    /**
     * Split the text fields on language and body size so that each record is
     * under 10kb.
     *
     * This method is called by Algolia\ScoutExtended\Jobs\UpdateJob::splitSearchable()
     * during indexing, and is applied to the text field.
     *
     * @see toSearchableArray()
     *
     * @param  array  $text
     *
     * @return array
     */
    public function splitText(array $text): array
    {
        if ($text['title_en'] || $text['body_en']) {
            $en = [
                'lng' => 'en',
                'path' => $text['path_en'],
                'title' => $text['title_en'],
                'body' => $text['body_en'],
            ];
            if (isset($text['author_en'])) {
                $en['author'] = $text['author_en'];
            }
            $records = $this->splitSearchableRecords($en, 'body', 'body_index');
        } else {
            $records = [];
        }
        if ($text['title_th'] || $text['body_th']) {
            $th = [
                'lng' => 'th',
                'path' => $text['path_th'],
                'title' => $text['title_th'] ?: $text['title_en'],
                'body' => $text['body_th'] ?: $text['body_en'],
            ];
            if (isset($text['author_en'])) {
                $th['author'] = $text['author_th'] ?: $text['author_en'];
            }
            $records = array_merge(
                $records,
                $this->splitSearchableRecords($th, 'body', 'body_index')
            );
        }
        return $records;
    }

    /**
     * Return the Aloglia indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
        return $this->getBaseSearchableArray();
    }

    /**
     * Return a base Aloglia indexable data array for the model. Models can use
     * this method in toSearchableArray() to make additional customizations.
     *
     * @see toSearchableArray()
     *
     * @param  string  $bodyAttribute
     *
     * @return array
     */
    protected function getBaseSearchableArray($bodyAttribute = 'body'): array
    {
        $result = [
            'type' => $this->getModelBasenameAttribute(),
            'id' => $this->getKey(),
            'text' => [
                'path_en' => $this->getPath('en'),
                'path_th' => $this->getPath('th'),
                'title_en' => $this->title_en,
                'title_th' => $this->title_th,
            ],
        ];
        if ($bodyAttribute) {
            $result['text']['body_en'] =
                HtmlToText::toText($this->{"{$bodyAttribute}_html_en"});
            $result['text']['body_th'] =
                HtmlToText::toText($this->{"{$bodyAttribute}_html_th"});
        }
        if ($this->author) {
            $result['text']['author_en'] = $this->author->title_en;
            $result['text']['author_th'] = $this->author->title_th;
            $result['author_id'] = $this->author->id;
        }
        return $result;
    }

    /**
     * Split a record by the attribute using TextSplitter. For each resulting
     * record, add an increment index keyed by $indexName.
     *
     * @see $textSplitterMax
     * @see $textSplitterMin
     * @see \App\Utilities\TextSplitter
     */
    protected function splitSearchableRecords(
        array $record,
        string $attribute,
        string $indexName
    ): array {
        $records = [];
        $textSplitterMax = $this->textSplitterMax ?: 2000;
        $textSplitterMin = $this->textSplitterMin ?: 500;
        $splitter = new TextSplitter($textSplitterMax, $textSplitterMin, true);
        $text = $record[$attribute];
        $splits = $splitter->splitByParagraphs($text);
        if (count($splits) > 0) {
            foreach ($splits as $i => $segment) {
                $newRecord = $record;
                $newRecord[$attribute] = $segment;
                $newRecord[$indexName] = $i;
                $records[] = $newRecord;
            }
        } else {
            $newRecord = $record;
            $newRecord[$indexName] = 0;
            $records[] = $newRecord;
        }
        return $records;
    }
}
