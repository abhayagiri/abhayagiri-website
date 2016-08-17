<?php

require_once __DIR__ . '/www-bootstrap.php';

$data = [
    'Abhayagiri\\getGitVersion()' => Abhayagiri\getGitVersion(),
    'base_path()' => base_path(),
    'realpath(base_path())' => realpath(base_path()),
    '__DIR__' => __DIR__,
    'realpath(__DIR__)' => realpath(__DIR__),
    'ini_get(\'opcache.use_cwd\')' => ini_get('opcache.use_cwd'),
    'ini_get(\'opcache.revalidate_path\')' => ini_get('opcache.revalidate_path'),
    'ini_get(\'opcache.revalidate_freq\')' => ini_get('opcache.revalidate_freq'),
];

phpinfo();

echo '<pre>';
var_dump($data);
echo '</pre>';

?>
