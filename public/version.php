<?php

require_once __DIR__ . '/www-bootstrap.php';

$data = [
    'Abhayagiri\\getGitVersion()' => Abhayagiri\getGitVersion(),
    'Abhayagiri\\Config::get(\'rootDir\')' => Abhayagiri\Config::get('rootDir'),
    'realpath(Abhayagiri\\Config::get(\'rootDir\'))' => realpath(Abhayagiri\Config::get('rootDir')),
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
