<?php
$I = new ApiTester($scenario);
$I->wantTo('get authors via API');

$authorId = App\Models\Author::where('slug', 'abhayagiri-sangha')->first()->id;
$I->sendGET('/authors/' .$authorId);
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
$I->seeResponseIsJson();
$I->seeResponseContainsJson([
    'titleEn' => 'Abhayagiri Sangha',
]);

$I->sendGET('/authors');
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
$I->seeResponseIsJson();
$authors = $I->grabDataFromResponseByJsonPath('*');
$I->assertGreaterThan(60, count($authors));

$I->sendGET('/authors', ['minTalks' => 1]);
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
$I->seeResponseIsJson();
$authors = $I->grabDataFromResponseByJsonPath('*');
$I->assertLessThan(60, count($authors));

$I->sendGET('/authors', ['minTalks' => 100]);
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
$I->seeResponseIsJson();
$authors = $I->grabDataFromResponseByJsonPath('*');
$I->assertLessThan(10, count($authors));
