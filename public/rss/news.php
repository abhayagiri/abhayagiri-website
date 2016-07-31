<?php

require_once __DIR__ . '/../bootstrap.php';

header('Content-Type: application/rss+xml; charset=utf-8', true);

echo Abhayagiri\Feed::getNewsFeed();

?>
