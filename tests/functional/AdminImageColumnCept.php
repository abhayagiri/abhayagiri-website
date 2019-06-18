<?php

$I = new FunctionalTester($scenario);
$I->wantTo('make sure image columns in the admin has images');

$I->amASuperAdmin();
$I->amOnPage('/admin/playlist-groups');
$I->see('Playlist groups');

$I->sendAjaxPostRequest('/admin/playlist-groups/search');
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);

$imageColumnHtml = $I->grabDataFromResponseByJsonPath('$.data[0][0]')[0];
$I->assertContains('<img', $imageColumnHtml);
