<?php
$I = new ApiTester($scenario);
$I->wantTo('get subjects via API');

$I->sendGET('/subjects');
$I->seeResponseContainsJson([
    [ 'titleEn' => 'Suffering' ],
    [ 'titleEn' => 'Aging, Sickness, and Death' ],
    [ 'titleEn' => 'Spiritual Friendships' ],
    [ 'titleEn' => 'Generosity' ],
]);

$I->sendGET('/subjects', [ 'subjectGroupId' => 2 ]);
$I->dontSeeResponseContainsJson([
    [ 'titleEn' => 'Suffering' ],
    [ 'titleEn' => 'Aging, Sickness, and Death' ],
]);

$I->sendGET('/subjects/4');
$I->seeResponseContainsJson(
    [ 'titleEn' => 'Aversion' ]
);
