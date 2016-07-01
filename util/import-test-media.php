#!/usr/bin/env php
<?php

$maxage = 60 * 60 * 24; // 1 day
$tmpdir = __DIR__ . '/../tmp';
$local_media_path = "$tmpdir/media-latest.zip";
$public_media_url = 'https://dev.abhayagiri.org/export/media-latest.zip';
$media_dir = __DIR__ . '/../public/media';

if (!file_exists($local_media_path) ||
    time() - filemtime($local_media_path) > $maxage) {
    echo("curl -o '$local_media_path' '$public_media_url'");
    system("curl -o '$local_media_path' '$public_media_url'");
}

system("mkdir -p '$media_dir' && cd '$media_dir' && unzip -o '$local_media_path'");

?>
