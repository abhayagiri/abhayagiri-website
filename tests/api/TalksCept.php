<?php
$I = new ApiTester($scenario);
$I->wantTo('get talks via API');

$I->sendGET('/talks', ['author' => 3, 'endDate' => 1236211400]);
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
$I->seeResponseIsJson();
$I->seeResponseContainsJson([[
    'title' => 'Āṭānāṭiya Paritta',
    'media_url' => '/media/audio/Chanting2009/C19AtanatiyaParitta.mp3',
    // TODO need much better test
]]);

// Temporary Category Test
// 27 = Ajahn Kampong

$I->sendGET('/talks', ['author' => 27]);
$I->assertEquals(4, count($I->grabDataFromResponseByJsonPath('$.*')));

$I->sendGET('/talks', ['category' => 'Dhamma Talk', 'author' => 27]);
$I->seeResponseContainsJson([
    [ 'title' => 'Practicing the Uposatha' ],
    [ 'title' => 'ธรรมเทศนาโดยหลวงพ่อคำผอง' ],
]);
