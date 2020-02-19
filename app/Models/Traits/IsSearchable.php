<?php

namespace App\Models\Traits;

use App\Utilities\HtmlToText;
use Laravel\Scout\Searchable;
use App\Utilities\TextSplitter;

/**
 * Algolia Search Trait.
 *
 * Models that use this trait will be searchable via Algolia.
 *
 * The basic structure for Algolia search record across all models is the
 * following:
 *
 * [
 *     'class' => 'App/Models/Foo',
 *     'id' => 123,
 *     'text' => [
 *         'lng' => 'en', // or 'th'
 *         'path' => '/news/123-hello',
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
 */
trait IsSearchable
{
    use Searchable;

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
        return true;
    }

    /**
     * Split the text fields on language and body size so that each record is
     * under 10kb.
     *
     * @see toSearchableArray()
     *
     * @param array $text
     *
     * @return array
     */
    public function splitText(array $text): array
    {
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

        if ($text['title_th'] || $text['body_th']) {
            $th = [
                'lng' => 'th',
                'path' => $text['path_th'],
                'title' => $text['title_th'] ?: $text['title_en'],
                'body' => $text['body_th'] ?: $text['body_en'],
            ];

            if (isset($text['author_en'])) {
                $th['author'] = $text['author_th'] ?: text['author_en'];
            }
            $records = array_merge($records, $this->splitSearchableRecords($th, 'body', 'body_index'));
        }

        return $records;
    }

    /**
     * Return a base Aloglia indexable data array for the model. Models can use
     * this method to add additional fields to use in search.
     *
     * @see splitText()
     * @see toSearchableArray()
     *
     * @param string $bodyAttribute
     *
     * @return array
     */
    public function getBaseSearchableArray($bodyAttribute = 'body'): array
    {
        $result = [
            'class' => get_class($this),
            'model_rank' => $this->modelRank(),
            'id' => $this->id,
            'text' => [
                'path_en' => $this->getPath('en'),
                'path_th' => $this->getPath('th'),
                'title_en' => $this->title_en,
                'title_th' => $this->title_th,
                'body_en' => HtmlToText::toText($this->{"{$bodyAttribute}_html_en"}),
                'body_th' => HtmlToText::toText($this->{"{$bodyAttribute}_html_th"}),
            ],
        ];

        if (isset($this->author_id)) {
            $result['text']['author_en'] = $this->author->title_en;
            $result['text']['author_th'] = $this->author->title_th;
        }

        return $result;
    }

    /**
     * Return the Aloglia indexable data array for the model.
     *
     * @see splitText()
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
        return $this->getBaseSearchableArray();
    }

    /**
     * Get the model's rank for search ranking.
     *
     * @return int
     */
    protected function modelRank(): int
    {
        return array_search(get_class($this), config('search.model_ranks'));
    }

    /**
     * Split a record by the attribute using TextSplitter. For each resulting
     * record, add an increment index keyed by $indexName.
     *
     * @todo The numbers 2000 and 500 need to be configurable.
     *
     * @see \App\Utilities\TextSplitter
     */
    protected function splitSearchableRecords(
        array $record,
        string $attribute,
        string $indexName
    ): array {
        $records = [];
        $splitter = new TextSplitter(2000, 500, true);
        $text = $record[$attribute];

        foreach ($splitter->splitByParagraphs($text) as $i => $segment) {
            $newRecord = $record;
            $newRecord[$attribute] = $segment;
            $newRecord[$indexName] = $i;
            $records[] = $newRecord;
        }

        return $records;
    }
}
