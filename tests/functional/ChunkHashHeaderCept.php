<?php

$I = new FunctionalTester($scenario);
$I->wantTo('ensure that the app-chunk-hash is set');

$I->amOnPage('/api/playlists');
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
$I->seeHttpHeader('app-chunk-hash');
