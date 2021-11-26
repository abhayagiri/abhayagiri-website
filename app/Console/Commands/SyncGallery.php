<?php

namespace App\Console\Commands;

use App\Models\Album;

use App\Models\Photo;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
     * The base url of the gallery API.
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
        $this->syncGallery();
    }

    protected function syncGallery()
    {
        $allPhotoIds = [];
        $allAlbumIds = [];
        $albumDatas = $this->getAlbumDatas();
        foreach ($albumDatas as $albumData) {
            $photoDatas = $this->getAlbumPhotoDatas($albumData['id']);
            if (count($photoDatas) < 1) {
                continue;
            }
            $allAlbumIds[] = $albumData['id'];
            $thumbnailId = null;
            $photosToSync = [];
            $photoRank = 1;
            foreach ($photoDatas as $photoData) {
                $allPhotoIds[] = $photoData['id'];
                if ($photoData['id'] === $albumData['thumbnail_id']) {
                    $thumbnailId = $photoData['id'];
                }
                $photo = $this->updateOrCreate(Photo::class, $photoData);
                $photosToSync[$photo['id']] = ['rank' => $photoRank];
                $photoRank += 1;
            }
            if ($thumbnailId === null) {
                $thumbnailId = array_keys($photosToSync)[0];
            }
            $albumData['thumbnail_id'] = $thumbnailId;
            $album = $this->updateOrCreate(Album::class, $albumData);
            $this->info('Syncing photos on album ' . $album->id);
            $album->photos()->sync($photosToSync);
        }
        Album::whereNotIn('id', $allAlbumIds)->each(function ($album) {
            $this->info('Deleting Album(' . $album->id . ')');
            $album->delete();
        });
        $allPhotoIds = array_unique($allPhotoIds);
        Photo::whereNotIn('id', $allPhotoIds)->each(function ($photo) {
            $this->info('Deleting Photo(' . $photo->id . ')');
            $photo->delete();
        });
    }

    protected function updateOrCreate($className, $data)
    {
        $friendlyName = $className . '(' . $data['id'] . ')';
        try {
            $model = $className::find($data['id']);
        } catch(\Exception $e) {
            $this->info('SYNC GALLERY:' . $e->getMessage());

            $this->info(json_encode(config('database.connections.mysql')));

            throw $e;
        }

        if ($model) {
            $update = false;
            foreach ($data as $key => $value) {
                if ($model->{$key} !== $value) {
                    $model->{$key} = $value;
                    $update = true;
                }
            }
            if ($update) {
                $this->info('Updating ' . $friendlyName);
                $model->save();
            }
        } else {
            $this->info('Creating ' . $friendlyName);
            $model = $className::create($data);
        }
        return $model;
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
                    'small_height' => (int) $image->derivatives->xsmall->height,
                    'medium_url' => $image->derivatives->large->url,
                    'medium_width' => (int) $image->derivatives->large->width,
                    'medium_height' => (int) $image->derivatives->large->height,
                    'large_url' => $image->derivatives->xxlarge->url,
                    'large_width' => (int) $image->derivatives->xxlarge->width,
                    'large_height' => (int) $image->derivatives->xxlarge->height,
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
