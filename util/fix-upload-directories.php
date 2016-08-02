#!/usr/bin/env php
<?php

require_once __DIR__ . '/../bootstrap.php';

$uploadDirectories = array(
    Abhayagiri\getMediaDir() . '/mahaguild',
    Abhayagiri\getMediaDir() . '/audio',
    Abhayagiri\getMediaDir() . '/books',
    Abhayagiri\getMediaDir() . '/images/books',
    Abhayagiri\getMediaDir() . '/images/residents',
    Abhayagiri\getMediaDir() . '/images/uploads',
);

foreach ($uploadDirectories as $dir) {
    print "chmod 0777 $dir\n";
    chmod($dir, 0777);
}

?>
