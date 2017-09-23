<?php

require base_path('legacy/bootstrap.php');
require base_path('legacy/php/main.php');

use App\Legacy;

$page = $_REQUEST['page'];

$className = array_get([
    'news' => \App\Models\News::class,
    'books' => \App\Models\Book::class,
    'reflections' => \App\Models\Reflection::class,
], $page);

if (!$className) {
    abort(404);
}

if (!isset($_language)) {
    $_language = 'English';
}

$table = str_plural(strtolower(array_slice(explode('\\', $className), -1)[0]));
list($models, $output) = call_user_func([$className, 'getLegacyDatatables'], $_GET);

foreach ($models as $key => $model) {
    $row = $model->toLegacyArray($_language);
    $data = include base_path("legacy/ajax/format_$table.php");
    $output['aaData'][] = [ $data ];
}

echo json_encode($output);
