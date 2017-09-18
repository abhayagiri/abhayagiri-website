<?php
$I = new ApiTester($scenario);
$I->wantTo('get playlist groups via API');

$I->sendGET('/playlist-groups');
$I->seeResponseContainsJson([
    [ 'titleEn' => 'Winter Retreats' ],
]);

$I->sendGET('/playlist-groups/1');
$I->seeResponseContainsJson(
    [ 'titleEn' => 'Winter Retreats' ]
);

$I->sendGET('/playlist-groups/1/playlists');
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
$I->seeResponseIsJson();
$playlists = $I->grabDataFromResponseByJsonPath('*');
$I->assertGreaterThan(0, count($playlists));
