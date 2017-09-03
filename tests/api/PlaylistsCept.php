<?php
$I = new ApiTester($scenario);
$I->wantTo('get playlists via API');

$I->sendGET('/playlists/1');
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
$I->seeResponseIsJson();

$I->sendGET('/playlists');
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
$I->seeResponseIsJson();
$playlists = $I->grabDataFromResponseByJsonPath('*');
$I->assertGreaterThan(10, count($playlists));
