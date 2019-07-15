<?php

namespace App\Utilities;

use elFinderVolumeDriver;
use Barryvdh\elFinderFlysystemDriver\Driver as elFinderFlysystemDriver;

class elFinderDigitalOceanSpacesDriver extends elFinderFlysystemDriver
{
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
            $stat = array(
                'size' => $object['size'],
                'ts' => time(),
                'read' => 1,
                'write' => 1,
                'mime' => ($object['type'] !== 'file') ? 'directory' :
                          elFinderVolumeDriver::mimetypeInternalDetect($object['basename']),
                'name' => $object['basename'],
                'hash' => $this->encode($object['path']),
                'phash' => $this->encode($this->dirnameCE($object['path'])),
            );
            $files[] = $stat;
        }

        return $files;
    }
}
