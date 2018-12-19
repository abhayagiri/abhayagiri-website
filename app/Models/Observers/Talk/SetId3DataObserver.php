<?php

namespace App\Models\Observers\Talk;

use App\Models\Talk;
use App\Facades\Id3WriterHelper;

class SetId3DataObserver
{
    public function saved(Talk $talk)
    {
        if (str_contains($talk->media_path, 'audio/youtube')) {
            $fullFileName = base_path('public/media/' . $talk->media_path);
            Id3WriterHelper::configureWriter($fullFileName, 'id3v2.3', true, false, 'UTF-8');
            Id3WriterHelper::setTag('title', $talk->title_en);
            Id3WriterHelper::setTag('artist', $talk->author->title_en);
            Id3WriterHelper::setTag('year', $talk->recorded_on->year);
            Id3WriterHelper::writeTags();
        }
    }
}
