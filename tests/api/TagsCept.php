<?php
$I = new ApiTester($scenario);
$I->wantTo('get genres via API');

$I->callArtisan('db:seed');

$I->sendGET('/tags/suffering-and-hindrances');
$I->seeResponseContainsJson([
    [
        'titleEn' => 'Suffering'
    ],
    [
        'titleEn' => 'Aging, Sickness, and Death'
    ],
]);

$I->sendGET('/tags/spirital-strengths-and-factors-of-awakening');
$I->seeResponseContainsJson([
    [
        'titleTh' => 'กัลยาณมิตร'
    ],
    [
        'titleTh' => 'ทาน'
    ],
]);
