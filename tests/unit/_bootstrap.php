<?php

require_once __DIR__.'/../../bootstrap/autoload.php';
$app = require __DIR__.'/../../bootstrap/app.php';
$app->loadEnvironmentFrom('.env');
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
