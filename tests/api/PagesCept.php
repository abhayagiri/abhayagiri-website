<?php
$I = new ApiTester($scenario);
$I->wantTo('get pages via API');

$I->sendGET('/pages/home');
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
$I->seeResponseIsJson();
$I->seeResponseContains('"page_title":"Home"');
$I->seeResponseContains('"page_icon":"icon-home"');
$I->seeResponseContains('"banner_url":"\/media\/images\/banner\/home.jpg"');
$I->seeResponseContains('"page_body":""');
