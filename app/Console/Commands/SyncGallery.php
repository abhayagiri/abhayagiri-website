<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use App\Models\Album;
use App\Models\Photo;

class SyncGallery extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-gallery';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update albums, photos and album_photo tables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $baseUrl = 'https://gallery.abhayagiri.org/ws.php?format=json&';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        DB::transaction(function() { $this->syncGallery(); });
    }

    protected function syncGallery()
    {
        Album::getQuery()->delete();
        Photo::getQuery()->delete();
        $albumDatas = $this->getAlbumDatas();
        foreach ($albumDatas as $albumData) {
            $photoDatas = $this->getAlbumPhotoDatas($albumData['id']);
            if (count($photoDatas) < 1) {
                continue;
            }
            $thumbnailId = null;
            $photos = [];
            foreach ($photoDatas as $photoData) {
                if ($photoData['id'] === $albumData['thumbnail_id']) {
                    $thumbnailId = $photoData['id'];
                }
                $photos[] = Photo::updateOrCreate(['id' => $photoData['id']], $photoData);
            }
            if ($thumbnailId === null) {
                $thumbnailId = $photo[0]->id;
            }
            $albumData['thumbnail_id'] = $thumbnailId;
            $album = Album::updateOrCreate(['id' => $albumData['id']], $albumData);
            $photoRank = 1;
            foreach ($photos as $photo) {
                $album->photos()->attach($photo->id, ['rank' => $photoRank]);
                $photoRank += 1;
            }
        }
    }

    protected function getAlbumDatas()
    {
        $url = $this->baseUrl . 'method=pwg.categories.getList';
        $json = file_get_contents($url);
        $obj = json_decode($json);
        $albumDatas = [];
        if ($obj && $obj->stat === 'ok') {
            foreach ($obj->result->categories as $category) {
                $albumData = [
                    'id' => (int) $category->id,
                    'title_en' => $category->name,
                    'title_th' => null,
                    'description_en' => $category->comment,
                    'description_th' => null,
                    'thumbnail_id' => (int) $category->representative_picture_id,
                    'rank' => (int) $category->global_rank,
                ];
                if (empty(trim($albumData['description_en']))) {
                    $albumData['description_en'] = null;
                }
                $albumDatas[] = $albumData;
            }
        }
        return $albumDatas;
    }

    protected function getAlbumPhotoDatas($id)
    {
        $url = $this->baseUrl . 'method=pwg.categories.getImages&per_page=500&cat_id=' . $id;
        $json = file_get_contents($url);
        $obj = json_decode($json);
        $photoDatas = [];
        if ($obj && $obj->stat === 'ok') {
            foreach ($obj->result->images as $image) {
                $photoData = [
                    'id' => $image->id,
                    'caption_en' => $image->name,
                    'caption_th' => null,
                    'original_url' => $image->element_url,
                    'original_width' => (int) $image->width,
                    'original_height' => (int) $image->height,
                    'small_url' => $image->derivatives->xsmall->url,
                    'small_width' => (int) $image->derivatives->xsmall->width,
                    'small_height' => (int) $image->derivatives->xsmall->width,
                    'medium_url' => $image->derivatives->large->url,
                    'medium_width' => (int) $image->derivatives->large->width,
                    'medium_height' => (int) $image->derivatives->large->width,
                    'large_url' => $image->derivatives->xxlarge->url,
                    'large_width' => (int) $image->derivatives->xxlarge->width,
                    'large_height' => (int) $image->derivatives->xxlarge->width,
                ];
                if (empty(trim($photoData['caption_en']))) {
                    $photoData['caption_en'] = null;
                }
                $photoDatas[] = $photoData;
            }
        }
        return $photoDatas;
    }
}
