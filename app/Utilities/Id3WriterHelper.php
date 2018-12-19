<?php

namespace App\Utilities;

class Id3WriterHelper
{
    private $getId3;

    private $getId3Writer;

    private $tags = [];

    public function __construct()
    {
        $this->getId3 = new \getID3;
        $this->getId3Writer = new \getid3_writetags;
    }

    public function configureWriter(string $fullFileName, string $tagFormat = 'id3v2.3', bool $overwriteTags, bool $removeOtherTags, string $tagEncoding = 'UTF-8')
    {
        $this->getId3Writer->filename = $fullFileName;
        $this->getId3Writer->tagformats = array_wrap($tagFormat);
        $this->getId3Writer->overwrite_tags = $overwriteTags;
        $this->getId3Writer->remove_other_tags = $removeOtherTags;
        $this->getId3Writer->tag_encoding = $tagEncoding;

        return $this->getId3Writer;
    }

    public function setTag(string $key, $value)
    {
        return array_set($this->tags, $key, array_wrap($value));
    }

    public function writeTags()
    {
        $this->getId3Writer->tag_data = $this->tags;

        if (! $this->getId3Writer->WriteTags()) {
            throw new \ErrorException("There was an error while writing tags to the file: " . implode($this->getId3Writer->errors));
        }

        $this->tags = [];
        return true;
    }
}
