<?php

if (!defined('LARAVEL_START')) {
    require __DIR__.'/vendor/autoload.php';
    $app = require __DIR__.'/bootstrap/app.php';
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
}
