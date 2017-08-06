<?php
$I = new ApiTester($scenario);
$I->wantTo('get subpages via API');

// TODO Verify 404
// $I->sendGET('/subpages/home/nowhere');
// $I->seeResponseCodeIs(\Codeception\Util\HttpCode::NotFound);

$I->sendGET('/subpages/about/purpose');
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
$I->seeResponseIsJson();
$I->seeResponseContainsJson([
    'titleEn' => 'Purpose',
]);
// TODO Verify body
