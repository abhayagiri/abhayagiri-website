<?php

require_once __DIR__ . '/../bootstrap.php';

// Seed some global variables with empty values

$request_keys = [
    '_page', '_subpage', '_subsubpage', '_entry', '_album', '_event',
    '_resident', 'url', 'sSearch', 'sSearch_0', 'sSearch_1',
    'action', 'book', 'quantity',
    'delete'
];

$global_variables = [
    '_action', '_page', '_page_title', '_subpage', '_subpage_title',
    '_subsubpage', '_subsubpage_title', '_meta_description', '_type',
    '_icon', 'order', 'url'
];

foreach ($request_keys as $key) {
    if (!array_key_exists($key, $_REQUEST)) {
        $_REQUEST[$key] = '';
        $_GET[$key] = '';
        $_POST[$key] = '';
    }
}

foreach($global_variables as $key) {
    $$key = '';
}

// Setup headers

header("Cache-Control: max-age=0, no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");

session_start();

?>
