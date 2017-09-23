<?php
$I = new ApiTester($scenario);
$I->wantTo('get pages via API');

$I->sendGET('/subpages/about');
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
$I->seeResponseIsJson();
$subpages = $I->grabDataFromResponseByJsonPath('*');
$I->assertGreaterThan(5, count($subpages));
