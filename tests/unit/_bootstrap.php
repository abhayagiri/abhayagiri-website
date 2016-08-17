<?php

$app = require_once __DIR__.'/../../bootstrap/app.php';

$app->loadEnvironmentFrom('.env.testing');

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
