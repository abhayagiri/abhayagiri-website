#!/usr/bin/env php
<?php

require_once __DIR__.'/../bootstrap.php';

$fullAccess = join(',', array(
    'audio',
    'authors',
    'books',
    'calendar',
    'construction',
    'danalist',
    'dropdowns',
    'faq',
    'gallery',
    'logs',
    'mahaguild',
    'messages',
    'misc',
    'news',
    'pages',
    'reflections',
    'request',
    'residents',
    'rss',
    'schedule',
    'settings',
    'subpages',
    'tasks',
    'uploads',
));

$email = $argv[1];

Abhayagiri\DB::getDB()->_insert('mahaguild', array(
    'email' => $email,
    'date' => date("Y-m-d H:i:s"),
    'access' => $fullAccess,
    'user' => 48,
));
