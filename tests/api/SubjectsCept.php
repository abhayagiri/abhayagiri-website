<?php
$I = new ApiTester($scenario);
$I->wantTo('get subjects via API');

$I->sendGET('/subject-groups');
$I->seeResponseContainsJson([
    [ 'titleEn' => 'Spiritual Strengths and Factors of Awakening' ],
    [ 'titleEn' => 'Suffering and Hindrances' ],
]);

$I->sendGET('/subject-groups/1');
$I->seeResponseContainsJson([ 'subjects' => [
    [ 'titleEn' => 'Suffering' ],
    [ 'titleEn' => 'Aging, Sickness, and Death' ],
]]);

$I->sendGET('/subject-groups/1/subjects');
$I->seeResponseContainsJson([
    [ 'titleEn' => 'Suffering' ],
    [ 'titleEn' => 'Aging, Sickness, and Death' ],
]);

$I->sendGET('/subjects');
$I->seeResponseContainsJson([
    [ 'titleEn' => 'Suffering' ],
    [ 'titleEn' => 'Aging, Sickness, and Death' ],
    [ 'titleEn' => 'Spiritual Friendships' ],
    [ 'titleEn' => 'Generosity' ],
]);

$I->sendGET('/subjects/4');
$I->seeResponseContainsJson(
    [ 'titleEn' => 'Aversion' ]
);
