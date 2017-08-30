<?php
$I = new ApiTester($scenario);
$I->wantTo('get genres via API');

$I->callArtisan('db:seed');

$I->sendGET('/subjects');
$I->seeResponseContainsJson([
    [
        'titleEn' => 'Suffering and Hindrances'
    ],
    [
        'titleEn' => 'Spiritual Strengths and Factors of Awakening'
    ],
]);


$I->sendGET('/subjects/1');
$I->seeResponseContainsJson([
    [
        'titleEn' => 'Suffering'
    ],
    [
        'titleEn' => 'Aging, Sickness, and Death'
    ],
]);

$I->sendGET('/subjects/2');
$I->seeResponseContainsJson([
    [
        'titleTh' => 'กัลยาณมิตร'
    ],
    [
        'titleTh' => 'ทาน'
    ],
]);
