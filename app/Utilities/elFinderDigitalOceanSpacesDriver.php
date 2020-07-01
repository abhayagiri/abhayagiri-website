<?php

namespace App\Utilities;

use Barryvdh\elFinderFlysystemDriver\Driver as elFinderFlysystemDriver;
use elFinderVolumeDriver;
use Illuminate\Support\Facades\Log;

class elFinderDigitalOceanSpacesDriver extends elFinderFlysystemDriver
{
    /**
     * Write a string to a file
     *
     * This contains a temporary fix to set the file visibility to public for
     * Digital Ocean Spaces (#114).
     *
     * @param  string $path file path
     * @param  string $content new file content
     *
     * @return bool
     */
    protected function _filePutContents($path, $content)
    {
        $result = parent::_filePutContents($path, $content);
        if ($result) {
            $this->fs->setVisibility($path, 'public');
        }
        return $result;
    }

    /**
     * Create new file and write into it from file pointer.
     * Return new file path or false on error.
     *
     * This contains a temporary fix to set the file visibility to public for
     * Digital Ocean Spaces (#114).
     *
     * @param  resource $fp file pointer
     * @param  string $dir target dir path
     * @param  string $name file name
     * @param  array $stat file stat (required by some virtual fs)
     *
     * @return bool|string
     */
    protected function _save($fp, $dir, $name, $stat)
    {
        $result = parent::_save($fp, $dir, $name, $stat);
        if ($result !== false) {
            $path = $this->_joinPath($dir, $name);
            $this->fs->setVisibility($path, 'public');
        }
        return $result;
    }

    /**
     * Return required dir's files info.
     *
     * This is an optimization for Digital Ocean Spaces to avoid calling stat
     * on every single directory item.
     *
     * Currently there are a couple caveats. First, caching is not performed.
     * Second, the timestamp is always the current time.
     *
     * @param  string $path dir path
     *
     * @return array
     */
    protected function getScandir($path)
    {
        $files = [];

        foreach ($this->fs->listContents($path, false) as $object) {
            $stat = [
                'size' => $object['size'],
                'ts' => time(),
                'read' => 1,
                'write' => 1,
                'mime' => ($object['type'] !== 'file') ? 'directory' :
                          elFinderVolumeDriver::mimetypeInternalDetect($object['basename']),
                'name' => $object['basename'],
                'hash' => $this->encode($object['path']),
                'phash' => $this->encode($this->dirnameCE($object['path'])),
            ];
            $files[] = $stat;
        }

        if (count($files) < config('elfinder.maximum_thumbnail_directory_size', 400)) {
            $files = parent::getScanDir($path);
        }

        return $files;
    }
}
