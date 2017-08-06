<?php
$I = new ApiTester($scenario);
$I->wantTo('get genres via API');

$I->callArtisan('db:seed');

$I->sendGET('/genres');
$I->seeResponseContainsJson([
    [
        'titleEn' => 'Suffering and Hindrances'
    ],
    [
        'titleEn' => 'Spiritual Strengths and Factors of Awakening'
    ],
]);
