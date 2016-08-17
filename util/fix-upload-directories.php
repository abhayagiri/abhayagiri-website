#!/usr/bin/env php
<?php

require_once __DIR__.'/../bootstrap.php';

$uploadDirectories = array(
    public_path('media/mahaguild'),
    public_path('media/audio'),
    public_path('media/books'),
    public_path('media/images/books'),
    public_path('media/images/residents'),
    public_path('media/images/uploads'),
);

foreach ($uploadDirectories as $dir) {
    print "chmod 0777 $dir\n";
    chmod($dir, 0777);
}
