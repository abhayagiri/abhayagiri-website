<?php
$I = new ApiTester($scenario);
$I->wantTo('get pages via API');

// TODO Verify 404
// $I->sendGET('/pages/nowhere');
// $I->seeResponseCodeIs(\Codeception\Util\HttpCode::NotFound);

$I->sendGET('/pages/home');
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
$I->seeResponseIsJson();
$I->seeResponseContainsJson([
    'page_title' => 'Home',
    'page_icon' => 'icon-home',
    'banner_url' => '/media/images/banner/home.jpg',
    'page_body' => '',
    'subpages' => [],
]);

$I->sendGET('/pages/about');
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
$I->seeResponseIsJson();
$I->seeResponseContainsJson([
    'page_title' => 'About',
    'page_icon' => 'icon-flag',
    'banner_url' => '/media/images/banner/about.jpg',
    'page_body' => '',
]);
$subpages = $I->grabDataFromResponseByJsonPath('$subpages');
$I->assertTrue(count($subpages) > 0);
