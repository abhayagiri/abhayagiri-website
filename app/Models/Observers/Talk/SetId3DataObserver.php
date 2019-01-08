<?php

namespace App\Models\Observers\Talk;

use App\Models\Talk;
use App\Facades\Id3WriterHelper;
use Illuminate\Support\Facades\File;

class SetId3DataObserver
{
    public function saved(Talk $talk)
    {
        $fullFileName = base_path('public/media/' . $talk->media_path);
        if (File::exists($fullFileName) && File::extension($fullFileName) == 'mp3') {
            Id3WriterHelper::configureWriter($fullFileName, 'id3v2.3', true, false, 'UTF-8');
            Id3WriterHelper::setTag('title', $talk->title_en);
            Id3WriterHelper::setTag('artist', optional($talk->author)->title_en);
            Id3WriterHelper::setTag('year', optional($talk->recorded_on)->year);
            Id3WriterHelper::writeTags();
        }
    }
}
